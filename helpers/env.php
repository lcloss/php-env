<?php
use LCloss\Env\Environment;

if ( !function_exists('env') ) {
    function env( $key, $default = '' ) {
        $env = Environment::getInstance();
        $data = $env::get( $key );
        if ( !is_array( $data ) ) {
            if ( '' == $data ) {
                return $default;
            } else {
                return $data;
            }
        } else {
            return $data;
        }
    }
}