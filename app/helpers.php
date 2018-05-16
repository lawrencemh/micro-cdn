<?php

if (!function_exists('public_path')) {
    /**
     * Get the path to the public dir of the install.
     *
     * @param string $path
     *
     * @return string
     */
    function public_path($path = '')
    {
        return removeDoubleForwardSlash(
            app()->basePath().'/public/'.($path ? '/'.$path : $path)
        );
    }
}

if (!function_exists('removeDoubleForwardSlash')) {
    /**
     * Replace double forward slashes from the given string.
     *
     * @param string $string
     *
     * @return string
     */
    function removeDoubleForwardSlash($string = '')
    {
        return preg_replace('#/+#', '/', $string);
    }
}
