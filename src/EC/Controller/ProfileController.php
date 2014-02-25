<?php

namespace EC\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class ProfileController
{
    public function indexAction(Request $request, Application $app)
    {
        return $app['twig']->render(
            'profile.twig',
            array(
                'errors' => null,
                'profileupdated' => null
            )
        );
    }

    protected function validateDetails(Request $request, Application $app)
    {
        $constraint = new Assert\Collection(
            array(
                'old_password' => array(
                    new Assert\NotBlank(),
                    new Assert\EqualTo(
                        array(
                            'value'    => $app['security']->getToken()->getUser()->getPassword(), // must match old password
                            'message'  => 'The old password you entered does not match!'
                        )
                    )
                ),
                'new_password' => array(
                    new Assert\NotBlank(),
                    new Assert\Length(
                        array('max' => 100)
                    ),
                    new Assert\NotEqualTo(
                        array(
                            'value'    => $request->get('old_password'),
                            'message'  => 'Your new password cannot be the same as your old password!'
                        )
                    )
                ),
                'new_password_confirm' => array(
                    new Assert\NotBlank(),
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
                'old_password'          => $app['security.encoder.digest']->encodePassword($request->get('old_password'), null),
                'new_password'          => $request->get('new_password'),
                'new_password_confirm'  => $request->get('new_password_confirm')
            ),
            $constraint
        );
    }

    public function changeProfileAction(Request $request, Application $app)
    {
        $errors = $this->validateDetails($request, $app);
        $validationSuccess = (count($errors) == null);

        if ($validationSuccess) { // Update the profile
            $app['datalayer.updatepassword'](
                $app['security']->getToken()->getUser()->getId(),
                $app['security.encoder.digest']->encodePassword($request->get('new_password'), null)
            );
        }

        return $app['twig']->render(
            'profile.twig',
            array(
                'errors' => $errors,
                'profileupdated' => $validationSuccess
            )
        );
    }
}