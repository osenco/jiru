<?php

use App\User;

if (!function_exists('user')) {
    function user($user = null)
    {
        return is_null($user) ? auth()->user() : User::find($user);
    }
}