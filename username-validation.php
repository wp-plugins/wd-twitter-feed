<?php
header('Content-type: application/json');

// fill in these 2 variables below
$username = $_GET['screen_name'];

$url="https://twitter.com/intent/user?screen_name=".$username;

if(!@file_get_contents($url)) {
    $data = array( 
    	'data' => "Invalid screen name",
    	'class' => 'user-validator-invalid'
    );
}
else {
	// screen name exists - you can do something here if you want
	$data = array( 
    	'data' => "Valid screen name",
    	'class' => 'user-validator-valid'
    );
}

echo json_encode( $data );

exit();
?>