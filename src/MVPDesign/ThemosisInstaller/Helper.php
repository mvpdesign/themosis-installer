<?php

namespace MVPDesign\ThemosisInstaller;

class Helper
{
    /**
     * generate a random string of characters
     *
     * @param  string $length
     * @return string
     */
    public static function generateRandomString($length = 64)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $chars .= '!@#$%^&*()';
        $chars .= '-_ []{}<>~`+=,.;:/?|';

        $string = '';

        for ($i = 0; $i < $length; $i++) {
            $string .= substr($chars, rand(0, strlen($chars) - 1), 1);
        }

        return $string;
    }
}
