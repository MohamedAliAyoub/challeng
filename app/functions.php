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
