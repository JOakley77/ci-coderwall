<?php

/**
 * CodeIgniter Coderwall API Class
 *
 * Request user project feeds from the Coderwall API
 *
 * @package         CodeIgniter
 * @subpackage      Libraries
 * @category        Libraries
 * @author          Jason Oakley
 * @license         http://www.jasonthedev.com
 * @link            https://github.com/JOakley77/ci-coderwall
 */

class Coderwall {

    // coderwall api url
    public $api_url = 'http://coderwall.com/%s.json';
    
    
    protected $_ci;         # codeigniter instance
    protected $_config;     # holds library configuration key/values
    protected $_username;   # username

    /**
     * Class constructor
     * 
     * @param array $options optional on-demand configuration key/value array
     */
    public function __construct( $options = NULL )
    {
        // load CI instance
        $this->_ci =& get_instance();

        // load the config & store values in $config
        $this->_config = $this->_ci->load->config( 'coderwall', TRUE, TRUE );

        // if $options is not null, merge valid options with main
        // config array
        if ( ! is_null( $options ) && is_array( $options ) AND count( $options ) > 0 ) {
            foreach ( $options AS $option_key => $option_value ) {
                if ( in_array( $option_key, $this->_config ) ) {
                    $this->_config[ $option_key ] = $option_value;
                }
            }
        }

        // load CACHE library (if opt in)
        if ( $this->_config[ 'enable_cache' ] ) {
            $this->_ci->load->driver( 'cache', array( 'adapter' => $this->_config[ 'cache_adapter' ][ 'primary' ], 'backup' => $this->_config[ 'cache_adapter' ][ 'backup' ] ) );
        }

        // create initial response object
        $this->response = new stdClass;
    }

    /**
     * Get API results
     * 
     * @param  string $username     the requested username
     * @return object ON success / NULL on error
     */
    public function get( $username = NULL )
    {
        // set user
        $_username = is_null( $username ) ?

            // test config username is present
            ( ( ! is_null( $this->_config[ 'username' ] ) && ! empty( $this->_config[ 'username' ] ) ) ? 
                $this->_config[ 'username' ]    # use the config username
                : FALSE )                       # set as NULL to error out

        // use the passed $username value
        : $username;

        // if $_username error return FALSE leaving it
        // up to the user to handle how they want to
        // respond.
        if ( $_username == FALSE )
            return FALSE;

        // call the API
        $resp = self::_request( sprintf( $this->api_url, $_username ) );

        // decode the JSON response
        $json = json_decode( $resp );

        // invalid response
        if ( is_null( $json ) || $json->username != $_username )
            return FALSE;

        // save the values in the class variables
        $user = new stdClass;
        $user->username     = $json->username;
        $user->name         = $json->name;
        $user->location     = $json->location;
        $user->endorsements = $json->endorsements;
        $user->accounts     = $json->accounts;
        $user->badges       = $json->badges;
        
        if ( $this->_config[ 'enable_cache' ] ) {
            if ( ! $cached = $this->_ci->cache->get( $_username ) ) {
                $cached = $user;
                $this->_ci->cache->save( $_username, $cached, $this->_config[ 'cache_life' ] );
            }
            return $cached;
        } 

        // return user object
        else return $user;
    }

    /**
     * Handles API requests
     * 
     * @param  string $url  the URL to call
     * @return mixed        the API response
     */
    protected function _request( $url )
    {
        // request using CURL
        if ( function_exists( 'curl_init' ) ) 
            return self::_request_byCurl( $url );

        // CURL not available - use alternative method
        else return self::_request_byAlt( $url );
    }

    /**
     * Calls the API using CURL
     * 
     * @param  string $url the URL to call
     * @return string      the response
     */
    private function _request_byCurl( $url )
    {
        // CURL -> create handle
        $ch = curl_init();

        // CURL -> Set options
        curl_setopt_array( $ch, array(
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HEADER         => FALSE,
            CURLOPT_FOLLOWLOCATION => TRUE,
            CURLOPT_ENCODING       => "",
            CURLOPT_AUTOREFERER    => FALSE,
            CURLOPT_CONNECTTIMEOUT => 120,
            CURLOPT_TIMEOUT        => 120,
            CURLOPT_MAXREDIRS      => 10,
        ));

        // CURL -> Go
        if ( $resp = curl_exec( $ch ) ) {
            curl_close( $ch );

            return $resp;
        }
        return NULL;
    }

    /**
     * Calls the API with file_get_contents
     * 
     * @param  string $url the URL to call
     * @return string      the response
     */
    private function _request_byAlt( $url )
    {
        return @file_get_contents( $url );
    }
}