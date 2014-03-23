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
\*--------------------------------------------------*/header('Content-type: application/json');$username = $_GET['screen_name'];$url = "https://twitter.com/intent/user?screen_name=" . $username;if (!@file_get_contents($url)) {
	$data = array(
		'data' => "Invalid screen name",
		'class' => 'user-validator-invalid'
	);} else {	$data = array(
		'data' => "Valid screen name",
		'class' => 'user-validator-valid'
	);
}echo json_encode($data);exit();