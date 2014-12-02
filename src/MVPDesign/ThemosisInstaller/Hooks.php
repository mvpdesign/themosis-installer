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
        $themosis = new Themosis($event);
        $themosis->askConfigQuestions();
        $themosis->install();
    }
}
