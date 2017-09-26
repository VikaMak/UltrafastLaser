<?php

class Config
{
    public static function getValue($path = null)
    {
        if ($path) {
            $config = $GLOBALS['config'];
            $path = explode('/', $path);

            foreach ($path as $v) {

                if (isset($config[$v])) {
                    $config = $config[$v];
                }
            }

            return $config;
        }        

    }
}