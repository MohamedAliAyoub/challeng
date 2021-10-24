<?php

if (!function_exists('valid_email')) {
    /**
     * @param $str
     * @return bool
     */
    function valid_email($str)
    {
        return !empty($str) && !!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str);
    }
}

if (!function_exists('current_locale')) {
    /**
     * @return string
     */
    function current_locale()
    {
        $locale = request()->get('lang', config('app.locale'));
        if (is_array(config('app.locales')) && !in_array($locale, array_keys(config('app.locales'))))
            $locale = array_keys(config('app.locales'))[0];
        return $locale;
    }
}

if (!function_exists('locales')) {
    /**
     * @return array
     */
    function locales()
    {
        return array_keys(config('app.locales'));
    }
}
