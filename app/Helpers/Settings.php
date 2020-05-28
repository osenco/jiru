<?php

if (!function_exists('setting')) {
    function setting($option, $key = null, $default = '')
    {
        $setting = config('services.settings.'.$option, $default);

        if ($setting) {
            if (is_null($key)) {
                return empty($setting->value) ? $default : $setting->value;
            } else {
                return (empty($setting->value[$key]) || is_null($setting->value[$key])) ? $default : $setting->value[$key];
            }
        }

        return $default;
    }
}

if (!function_exists('settings')) {
    function settings($option = null, $key = null, $default = '')
    {
        if (is_null($option)) {
            return config('services.settings', []);
        }

        $setting = config('services.settings.'.$option, $default);

        if ($setting) {
            if (is_null($key)) {
                return empty($setting->value) ? $default : $setting->value;
            } else {
                return (empty($setting->value[$key]) || is_null($setting->value[$key])) ? $default : $setting->value[$key];
            }
        }

        return $default;
    }
}
