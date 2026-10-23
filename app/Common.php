<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */

if (! function_exists('set_value')) {
    /**
     * Form Value
     *
     * Overrides system set_value to safely accept null default values without
     * throwing TypeError in PHP 8.
     *
     * @param string                   $field      Field name
     * @param list<string>|string|null $default    Default value
     * @param bool                     $htmlEscape Whether to escape HTML special characters or not
     *
     * @return list<string>|string
     */
    function set_value(string $field, $default = '', bool $htmlEscape = true): array|string
    {
        $request = service('request');

        // Try any old input data we may have first
        $value = method_exists($request, 'getOldInput') ? $request->getOldInput($field) : null;

        $postValue = method_exists($request, 'getPost') ? $request->getPost($field) : null;
        $value ??= $postValue ?? ($default ?? '');

        if ($value === null) {
            $value = '';
        }

        return ($htmlEscape) ? esc($value) : $value;
    }
}

if (! function_exists('form_error')) {
    /**
     * Compatibility helper for CI3 form_error()
     */
    function form_error(string $field = '', string $prefix = '', string $suffix = ''): string
    {
        $error = '';
        if (function_exists('validation_show_error')) {
            $err = validation_show_error($field);
            if (!empty($err)) {
                $error = strip_tags($err);
            }
        }
        if (empty($error)) {
            $session = session();
            $errors = $session->getFlashdata('errors') ?? $session->get('errors');
            if (is_array($errors) && !empty($errors[$field])) {
                $error = $errors[$field];
            }
        }
        return !empty($error) ? $prefix . $error . $suffix : '';
    }
}
