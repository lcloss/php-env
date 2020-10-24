<?php
namespace LCloss\Env;

final class Environment
{
    private static $instance = NULL;
    private static $env_file = "";
    private static $env = [];

    private function __construct() {}

    public static function getInstance( $env_file = NULL )
    {
        if ( is_null(self::$instance) ) {
            self::$instance = new Environment();
            self::loadSettings();
            self::setEnv( $env_file );

            // Load initial data
            self::$instance->load( $env_file );

            // Setters with magic method
            self::$instance->base_dir = $base;
            self::$instance->env_file = $base . $env_file;
        }

        return self::$instance;
    }

    public static function setDefaultEnv() {
        $path = self::get( 'base_dir' );
        self::setEnv( $path . '.env ');
    }

    public static function setEnv( $env_file = NULL )
    {
        if ( is_null( $env_file ) ) {
            self::setDefaultEnv();
        }
        self::$env_file = $env_file;
    }

    public static function load( $env_file = NULL )
    {
        if ( !is_null( $env_file ) ) {
            self::setEnv( $env_file );
        } else {
            self::setDefaultEnv();
        }
        $file = self::$env_file;
        if ( !file_exists($file) ) {
            throw new \Exception(sprintf('File %s does not exists.', $file));
        }

        self::$env = parse_ini_file( $file, true );
    }

    public static function loadSettings()
    {
        $dir = explode( DIRECTORY_SEPARATOR, __DIR__ );
        if ( count( $dir ) > 4 ) {
            $path = implode( DIRECTORY_SEPARATOR, array_slice( $dir, 0, -4 )) . DIRECTORY_SEPARATOR;
        } else {
            throw \Exception(sprint('Cannot determine base path.'));
        }

        // Load here other system default info
        self::$env['base_dir'] = $path;
    }

    public static function getSection( $section )
    {
        $section = self::get( $section );
        if ( is_array( $section ) ) {
            return $section;
        } else {
            return [];
        }
    }

    public static function getKey( $section, $key )
    {
        if ( array_key_exists( $env[$section] ) ) {
            if ( array_key_exists( $env[$section][$key]) ) {
                return self::$env[ $section ][ $key ];
            } else {
                return '';
            }
        } else {
            return '';
        }
        
    }

    public static function get( $key )
    {
        if ( array_key_exists( $key, self::$env ) ) {
            return self::$env[ $key ];
        } else {
            return '';
        }
        
    }
}