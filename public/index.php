<?php

/**
 * Front controller
 *
 * PHP version 7.0
 */


/**
 *  FORCE HTTPS EVERYWHER EXCEPT WHITE LIST
 */
	
	$whitelist = array(
		'127.0.0.1',
		'::1'
	);
	if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
		if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off") {
			$redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			header('HTTP/1.1 301 Moved Permanently');
			header('Location: ' . $redirect);
			exit();
		}
	}

/**
 *
 *  PREVENT BROWSER CACHE
 */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");// HTTP 1.1.
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.


/**
 * Composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';


/**
 * Date/Time Settings
 */
date_default_timezone_set( 'America/New_York' );

/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');


/**
 * Sessions
 */
session_start();


/**
 * Routing
 */
$router = new Core\Router();

// Add 'Vanity' Custom Routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('login', ['controller' => 'Login', 'action' => 'new']);
$router->add('logout', ['controller' => 'Login', 'action' => 'destroy']);
$router->add('password/reset/{token:[\da-f]+}', ['controller' => 'Password', 'action' => 'reset']);
$router->add('signup/activate/{token:[\da-f]+}', ['controller' => 'Signup', 'action' => 'activate']);

//Generic Routes
$router->add('{controller}/{action}');
$router->add( '{controller}/{id:[a-z\-\d+]+}/{action}' );


//Special Namespace Routes
$router->add( 'admin/{controller}/{action}', [ 'namespace' => 'Admin' ] );
$router->add( 'admin/{controller}/{id:[a-z\-\d+]+}/{action}', [ 'namespace' => 'Admin' ] );

$router->dispatch($_SERVER['QUERY_STRING']);
