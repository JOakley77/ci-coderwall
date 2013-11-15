#CodeIgniter Coderwall API Library

A simple requester library to return and cache results from the Coderwall API.

## Requirements
1. PHP 5.2 or greater
2. CodeIgniter 2.1.0+
3. An active account username for [coderwall.com](http://www.coderwall.com).

*Note: for 1.7.x support download v2.2 from Downloads tab*

## Installation
Drag and drop **libraries/coderwall.php** and **config/coderwall.php** files into your application. 

If you are requesting a single profile from Coderwall you can set the username in the configuration file. Otherwise, request it at runtime.

## Usage
Either autoload the library or use CodeIgniters loader class.
	
	$this->load->library( 'coderwall' );
	$results = $this->coderwall->get( 'someuser' );
	
	// if success returns object (FALSE if error)
	var_dump( $results );
	
Often times, CURL adds a bit of overhead when making requests so I've added in CodeIgniters Cache Library. You can enable/disable it via config file/option. Available options are:

| Config  | Type | Options | Default | Desc |
| --------| ---- | ------- | ------- | ---- |
| username | STRING | none | | Username constant |
| enable_cache | BOOLEAN | true/false | true | Enable/disable cache |
| cache_adapter | ARRAY | primary/backup | apc/file | Refer to CI cache docs for options |
| cache_life | INTEGER | none | 300 | How long to save cached data for |

## License
See attached license.txt for more