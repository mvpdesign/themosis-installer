<?php

namespace MVPDesign\ThemosisInstaller;

use MVPDesign\ThemosisInstaller\InvalidStringLengthException;
use MVPDesign\ThemosisInstaller\InvalidEmailException;
use MVPDesign\ThemosisInstaller\InvalidURLException;

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
        $chars .= '-_[]{}<>~`+=,.;:/?|';

        $string = '';

        for ($i = 0; $i < $length; $i++) {
            $string .= substr($chars, rand(0, strlen($chars) - 1), 1);
        }

        return $string;
    }

    /**
     * format a question
     *
     * @param  string $question
     * @param  string $description
     * @return string
     */
    public static function formatQuestion($question = '', $description = '')
    {
        $formattedQuestion    = '<info>' . $question . '</info>';
        $formattedDescription = '';

        if (strlen($description) > 0) {
            $formattedDescription = ' [<comment>' . $description . '</comment>]';
        }

        return $formattedQuestion . $formattedDescription . ': ';
    }

    /**
     * validate string
     *
     * @return void
     */
    public static function validateString($string)
    {
        if (strlen($string) == 0) {
            throw new InvalidStringLengthException('The string must have a length greater than 0.');
        }

        return $string;
    }

    /**
     * validate email
     *
     * @return void
     */
    public static function validateEmail($email)
    {
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException('The email is not valid.');
        }

        return $email;
    }

    /**
     * validate url
     *
     * @return void
     */
    public static function validateURL($url)
    {
        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidURLException('The url is not valid.');
        }

        return $url;
    }
}
