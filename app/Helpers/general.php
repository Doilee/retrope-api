<?php
if (!function_exists('frontend'))
{
    function frontend($path = null)
    {
        return url(config('app.frontend') . '/' . $path);
    }
}