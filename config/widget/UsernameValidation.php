<?php
/**
 * @package   AskupaTwitterFeed
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/twitter-feed/
 * @copyright 2014 Askupa Software
 */

/*--------------------------------------------------*\
 * This file validates a given twitter account username
 * and returns a json object to be used by Javascript.
 * The username is passed in as a $_GET variable
\*--------------------------------------------------*/

// The content type header
header('Content-type: application/json');

// fill in these 2 variables below
$username = $_GET['screen_name'];

// The url to twitter's web-intent requests (no tokens are required for this request)
$url = "https://twitter.com/intent/user?screen_name=" . $username;

// No data was returned, the username was invalid
if (!@file_get_contents($url)) {
	$data = array(
		'data' => "Invalid screen name",
		'class' => 'user-validator-invalid'
	);

// Some data was returned, the username is valid
} else {
	// screen name exists - you can do something here if you want
	$data = array(
		'data' => "Valid screen name",
		'class' => 'user-validator-valid'
	);
}

// Return a json encoded object to be used by Javascript
echo json_encode($data);

// Terminate the request
exit();