<?php

namespace MVPDesign\ThemosisInstaller;

use Composer\Script\Event;
use MVPDesign\ThemosisInstaller\Themosis;

class Hooks
{
    /**
     * the themosis install hook
     *
     * @param  Event $event
     * @return void
     */
    public static function themosis(Event $event)
    {
        $io = $event->getIO();

        $themosis = new Themosis($io);
        $themosis->askConfigQuestions();
        $themosis->install();
    }
}
