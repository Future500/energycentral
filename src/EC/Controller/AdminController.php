<?php

namespace EC\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolation;

class AdminController
{
    public function usersAction(Request $request, Application $app)
    {
        $amountOfUsers  = $app['user']->count();
        $pagination     = $app['pagination']($amountOfUsers, $request->get('p'), 5);
        $usersToShow    = $app['user']->getUsers(false, $pagination->offset(), $pagination->limit());

        return $app['twig']->render(
            'admin/users.twig',
            array(
                'users'         => $usersToShow,
                'current_page'  => $pagination->currentPage(),
                'pages'         => $pagination->build()
            )
        );
    }

    public function devicesAction(Request $request, Application $app)
    {
        $amountOfDevices = $app['device']->count();
        $pagination      = $app['pagination']($amountOfDevices, $request->get('p'), 5);
        $devicesToShow   = $app['device']->listAll(false, $pagination->offset(), $pagination->limit());
        $userList        = $app['user']->getUsers(true); // Only retrieve all users their name

        return $app['twig']->render(
            'admin/alldevices.twig',
            array(
                'devices'       => $devicesToShow,
                'users'         => json_encode($userList),
                'current_page'  => $pagination->currentPage(),
                'pages'         => $pagination->build()
            )
        );
    }

    protected function validatePassword(Request $request, Application $app)
    {
        $constraint = new Assert\Collection(
            array(
                'new_password' => array(
                    new Assert\Length(
                        array('max' => 100)
                    ),
                ),
                'new_password_confirm' => array(
                    new Assert\Length(
                        array('max' => 100)
                    ),
                    new Assert\EqualTo(
                        array(
                            'value'    => $request->get('new_password'), // must match new password
                            'message'  => 'The new password does not match the confirmation password!'
                        )
                    )
                )
            )
        );

        return $app['validator']->validateValue(
            array(
                'new_password'          => $request->get('new_password'),
                'new_password_confirm'  => $request->get('new_password_confirm')
            ),
            $constraint
        );
    }

    public function changeDevicesAction(Request $request, Application $app)
    {
        if ($request->get('deviceid') && $request->get('accepted')) { // update accepted status
            $deviceId       = $request->get('deviceid');
            $acceptedStatus = $request->get('accepted');

            return $app['device']->setAcceptedStatus($deviceId, $acceptedStatus);
        }

        $validation = array(
            'errors' => new ConstraintViolationList(),
            'success' => true
        );

        $users = array(
            'all'       => $app['user']->getUsers(true), // all usernames
            'current'   => $app['device']->getUsers($request->get('deviceid')),
            'form'      => $request->get('users') ? explode(',', $request->get('users')) : array(), // submitted usernames by form
            'added'     => null,
            'removed'   => null
        );

        $users['added']   = array_diff($users['form'], $users['current']); // devices that should be added
        $users['removed'] = array_diff($users['current'], $users['form']); // devices that should be removed

        if ($request->get('users') != null || ($users['current'] != null && $request->get('users') == null)) { // Update when new users are added, deleted OR when the last user is deleted
            foreach ($users['added'] as $user) { // Check if each device that is about to be added exists
                $validation['success'] = in_array($user, $users['all']);

                if (!$validation['success']) {
                    $validation['errors']->add(
                        new ConstraintViolation('User does not exist: ' . $user, null, array(), null, null, null)
                    ); // Add error to list
                }
            }

            if ($validation['success']) {
                // update device list for user, will add or remove any devices if needed
                $app['device']->updateDeviceUsers(
                    $request->get('deviceid'),
                    $app['user']->getIds($users['added']),
                    $app['user']->getIds($users['removed'])
                );
                return true;
            }
        }

        return $validation['success'];
    }

    public function changeUserAction(Request $request, Application $app)
    {
        $validation = array(
            'errors' => new ConstraintViolationList(),
            'success' => true
        );

        if ($request->get('new_password') != null || $request->get('new_password_confirm') != null) { // Password does not always need to update when saving profile
            $validation['errors'] = $this->validatePassword($request, $app);
            $validation['success'] = !$validation['errors']->count();

            if ($validation['success']) { // Update the profile
                $userId      = $request->get('userid');
                $user        = $app['user.load']($userId);
                $newPassword = $request->get('new_password');

                $encodedPassword = $app['user.encode_password']($user, $newPassword);

                $app['user']->setNewPassword(
                    $userId,
                    $encodedPassword['password'],
                    $encodedPassword['salt']
                );
            }
        }

        $userDevices     = $app['user']->getDevices($request->get('userid'), true);
        $userDeviceNames = $app['device']->getNames($userDevices); // user devices (names)
        $allDevices      = $app['device']->listAll(true); // all devices (names)

        $devices = array(
            'user'      => $userDeviceNames, // user devices (names)
            'all'       => $allDevices, // all devices (names)
            'form'      => $request->get('devices') ? explode(',', $request->get('devices')) : array(), // submitted devices by form (names)
            'added'     => null,
            'removed'   => null,
        );

        $devices['added'] = array_diff($devices['form'], $devices['user']); // devices that should be added
        $devices['removed'] = array_diff($devices['user'], $devices['form']); // devices that should be removed

        if ($request->get('devices') != null || ($devices['user'] != null && $request->get('devices') == null)) { // Update when new devices are added, deleted OR when the last device is deleted
            foreach ($devices['added'] as $device) { // Check if each device that is about to be added exists
                $validation['success'] = in_array($device, $devices['all']);

                if (!$validation['success']) {
                    $validation['errors']->add(
                        new ConstraintViolation('Device does not exist: ' . $device, null, array(), null, null, null)
                    ); // Add error to list
                }
            }

            if ($validation['success']) {
                // update device list for user, will add or remove any devices if needed
                $app['device']->updateUserDevices(
                    $request->get('userid'),
                    $app['device']->getIds($devices['added']),
                    $app['device']->getIds($devices['removed'])
                );
            }
        }

        return $this->viewUserAction($request, $app, $validation['errors'], $validation['success']); // re-render the page and show if the profile was updated*/
    }

    public function viewUserAction(Request $request, Application $app, $errors = null, $profileUpdated = null)
    {
        $user        = $app['user.load']($request->get('userid'));
        $userDevices = $app['user']->getDevices($app->user()->getId(), true);
        $allDevices  = $app['device']->listAll(true); // used in the view for tagging list

        return $app['twig']->render(
            'admin/viewuser.twig',
            array(
                'user'           => $user,
                'user_devices'   => $userDevices,
                'all_devices'    => json_encode($allDevices),
                'errors'         => $errors,
                'profileupdated' => $profileUpdated
            )
        );
    }
}