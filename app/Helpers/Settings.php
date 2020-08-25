<?php

if (!function_exists('setting')) {
    function setting($option, $key = null, $default = '')
    {
        $settings = require(base_path('config/settings.php'));
        if (is_null($option)) {
            return $settings;
        }

        $setting = ($settings[$option] ?? $default);

        if ($setting) {
            if (is_null($key)) {
                return $setting;
            } else {
                $setting = (object)($settings[$option] ?? $default);
                return (empty($setting->$key) || is_null($setting->$key)) ? $default : $setting->$key;
            }
        }

        return $default;
    }
}

if (!function_exists('settings')) {
    function settings($option = null, $key = null, $default = '')
    {
        $settings = require(base_path('config/settings.php'));
        if (is_null($option)) {
            return $settings;
        }

        $setting = ($settings[$option] ?? $default);

        if ($setting) {
            if (is_null($key)) {
                return $setting;
            } else {
                $setting = (object)($settings[$option] ?? $default);
                return (empty($setting->$key) || is_null($setting->$key)) ? $default : $setting->$key;
            }
        }

        return $default;
    }
}
