<?php

namespace EC\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

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
                    new SecurityAssert\UserPassword(
                        array(
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
                'old_password'          => $request->get('old_password'),
                'new_password'          => $request->get('new_password'),
                'new_password_confirm'  => $request->get('new_password_confirm')
            ),
            $constraint
        );
    }

    public function changeProfileAction(Request $request, Application $app)
    {
        $errors = $this->validateDetails($request, $app);
        $validationSuccess = ($errors->count() == null);

        if ($validationSuccess) { // Update the profile
            $app['datalayer.updatepassword'](
                $app->user()->getId(),
                $request->get('new_password')
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