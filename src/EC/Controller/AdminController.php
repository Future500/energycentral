<?php

namespace EC\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class AdminController
{
    public function usersAction(Request $request, Application $app)
    {
        return $app['twig']->render(
            'admin/users.twig',
            array('users' => $app['datalayer.users']())
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

    public function changeUserAction(Request $request, Application $app)
    {
        if ($request->get('new_password') != null && $request->get('new_password_confirm') != null) { // Password does not always need to update when saving profile
            $errors = $this->validatePassword($request, $app);
            $validationSuccess = (count($errors) == null);

            if ($validationSuccess) { // Update the profile
                $passwordUpdated = $app['datalayer.updatepassword'](
                    $request->get('userid'),
                    $app['security.encoder.digest']->encodePassword($request->get('new_password'), null)
                );

                // .. password updated check
            }
        }

        if ($request->get('devices') != null) { // Devices do not always need to update when saving profile
            $userDevices = $app['devices.list'](true, $request->get('userid'));
            $zipcodes = $app['devices.getzipcodes']($userDevices); // get zipcodes for each device in the list
            $newDevices = array_diff($request->get('devices'), $zipcodes); // check if there are any new devices entered

            if (count($newDevices) != null) {
                $app['datalayer.add_devices'](
                    $request->get('userid'),
                    $newDevices
                );
            }
        }

        return $this->viewUserAction($request, $app, $errors, $validationSuccess); // re-render the page and show if the profile was updated
    }

    public function viewUserAction(Request $request, Application $app, $errors = null, $profileUpdated = null)
    {
        $zipcodes = $app['devices.getzipcodes'](
            $app['devices.list.all'](true)
        );

        return $app['twig']->render(
            'admin/viewuser.twig',
            array(
                'user'           => $app['datalayer.user']($request->get('userid')),
                'user_devices'   => $app['devices.list'](true, $request->get('userid')),
                'all_devices'    => json_encode($zipcodes),
                'errors'         => null,
                'profileupdated' => null
            )
        );
    }
}