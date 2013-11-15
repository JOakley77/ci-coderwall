<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Username
|--------------------------------------------------------------------------
|
| Set a username here to pull by default.
|
*/
$config[ 'username' ] = 'joakley77';

/*
|--------------------------------------------------------------------------
| Enable CI cache
|--------------------------------------------------------------------------
|
| Enable CodeIgniter cache library to cache API results.
|
*/
$config[ 'enable_cache' ] = TRUE;

/*
|--------------------------------------------------------------------------
| CI Cache Adapter
|--------------------------------------------------------------------------
|
| Which drivers shall we use for caching?
|
*/
$config[ 'cache_adapter' ] = array(
	'primary'	=> 'apc',
	'backup'	=> 'file'
);

/*
|--------------------------------------------------------------------------
| Cache timeout
|--------------------------------------------------------------------------
|
| How long the cache results will live
|
*/
$config[ 'cache_life' ] = 300; # 5 minutes

/* End of file coderwall.php */