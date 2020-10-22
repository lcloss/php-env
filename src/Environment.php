<?php
namespace LCloss\Env;

final class Environment
{
    private static $instance = NULL;
    protected $base = "";
    protected $env = [];

    private function __construct() {}

    public static function getInstance( $env_file = '.env' )
    {
        if ( is_null(self::$instance) ) {
            self::$instance = new Environment();

            if ( $env_file == '.env' ) {
                $paths = explode( DIRECTORY_SEPARATOR, __DIR__ );

                if ( count( $paths ) > 4 ) {
                    $paths = array_slice( $paths, 0, -4 );
                    $base = implode( DIRECTORY_SEPARATOR, $paths ) . DIRECTORY_SEPARATOR;
                } else {
                    throw \Exception(sprint('Cannot determine base path.'));
                }
            } else {
                $paths = array_slice( $env_file, 0, -1 );
                $base = implode( DIRECTORY_SEPARATOR, $paths ) . DIRECTORY_SEPARATOR;
            }
            self::$instance->setBase( $base );

            // Load initial data
            self::$instance->load( $env_file );

            // Setters with magic method
            self::$instance->base_dir = $base;
            self::$instance->env_file = $base . $env_file;
        }

        return self::$instance;
    }

    private function load( $env_file = '.env' )
    {
        $file = $this->base . $env_file;
        if ( !file_exists($file) ) {
            throw new \Exception(sprintf('File %s does not exists.', $file));
        }

        $this->env = parse_ini_file( $file, true );
    }

    public function getSection( $section )
    {
        return $this->env[ $section ];
    }

    public function getKey( $section, $key )
    {
        return $this->env[ $section ][ $key ];
    }

    public function __get( $key )
    {
        if ( array_key_exists( $key, $this->env ) ) {
            return $this->env[ $key ];
        } else {
            foreach( $this->env as $section => $data ) {
                if ( array_key_exists( $key, $data )) {
                    return $data[ $key ];
                }
            }
        }
    }

    public function __set( $key, $value ) 
    {
        $this->env[ $key ] = $value;
    }

    private function setBase( $base )
    {
        $this->base = $base;
    }
}