<?php

use Composer\Script\Event;

class Composerscript
{

    public static function postUpdate(Event $event)
    {
        // Send the same command as when installing;
        self::postInstall($event);
    }


    public static function postInstall(Event $event)
    {
        $composer = $event->getComposer();
        $io = $event->getIO();

        $io->write( "Copying images..." );
        `mkdir -p web/img`;
        `cp vendor/twitter/bootstrap/img/* web/img/`;

        $io->write( "Copying css..." );
        `mkdir -p web/css`;
        `cp vendor/twitter/bootstrap/css/* web/css/`;

        $io->write( "Copying js..." );
        `mkdir -p web/js`;
        `cp vendor/twitter/bootstrap/js/* web/js/`;
        `cp vendor/jquery/jquery/* web/js/`;
        `cp -r vendor/highstock/js/* web/js/`;

        $io->write( "Copying sma-spot..." );
        `mkdir -p bin`;
        `cp -r vendor/sma-spot bin`;
    }

}
