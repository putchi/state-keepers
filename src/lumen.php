<?php

if (!function_exists('config_path')) {
    /**
     * Get the configuration path.
     *
     * @param string $path
     * @return string
     */
    function config_path($path = '') {
        return app()->basePath() . '/config' . ($path ? '/' . $path : $path);
    }
}

if (!function_exists('public_path')) {
    /**
     * Return the path to public dir.
     *
     * @param null $path
     * @return string
     */
    function public_path($path = null) {
        return rtrim(app()->basePath('public/' . $path), '/');
    }
}

if (!function_exists('resource_path')) {
    /**
     * Return the path to public dir.
     *
     * @param null $path
     * @return string
     */
    function resource_path($path = null) {
        return rtrim(app()->basePath('resources/' . $path), '/');
    }
}
