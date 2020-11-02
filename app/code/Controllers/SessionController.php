<?php

namespace Controllers;

class SessionController
{
    public static function init() {
        if(session_id() == '' || !isset($_SESSION)) {
            // session isn't started
            ini_set('session.cookie_lifetime', 60 * 60 * 24 * 7);
            session_start();
        }
    }

    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function get($key) {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
    }

    public static function destroy() {
        //unset($_SESSION);
        session_destroy();
    }
}