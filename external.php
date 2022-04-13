<?php
require_once("include/dbcommon.php");
require_once('classes/menupage.php');


if( !isLogged() || isLoggedAsGuest() ) 
{
	Security::tryRelogin();
}

if( !isLogged() )
{
	return;
}

$duration = postvalue( "duration" );
if( !$duration ) {
	$duration = 10;
}
$url =  postvalue( "url" );
if( !$url ) {
	return;
}

// todo 
// add url matching, so only allowed URLs are processed

$payload = array( 
	"username" => Security::getUserName(),
);
$jwt = jwt_encode( $payload, $duration );
if( strpos( $url, '?' ) !== false ) {
	$url .= "&token=" . $jwt;
} else {
	$url .= "?token=" . $jwt;
}
header("Location: " . $url );
?>