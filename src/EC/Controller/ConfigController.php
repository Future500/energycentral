<?php

namespace EC\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class ConfigController
{
    public function languageAction(Application $app, $lang)
    {
        $sessionLocale = $app['session']->get('locale');

        if ($sessionLocale != $lang) { // Set language if not set yet
            $app['translator']->setLocale($lang);
            $app['session']->set('locale', $lang);
        }

        return true;
    }
}