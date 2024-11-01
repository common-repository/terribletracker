<?php
/**
*	this file contains all the tracking codes & scripts
* 	AND gets them loaded in the FOOTER of the site
*/

// GOOGLE ANALYTICS TRACKING
function terrible_loadscripts() { 
	
	// LOCATION OF GOOGLE ANALYTICS SNIPPET
	$ga = terrible_URL . 'assets/js/ga.js';
    wp_register_script( 'ga-tracking', $ga , '', '', true );

	// FETCH GOOGLE ANALYTICS UA CODE FROM WP SETTINGS API
	$uacode = get_option('ua_code');

	// PASS UA CODE TO $GA
    $scriptData = $uacode;
    wp_localize_script('ga-tracking', 'uacode', $scriptData);
    
	// LOAD $GA IN HEADER
    wp_enqueue_script( 'ga-tracking');   
}

// ADD FUNCTION TO WP
add_action("wp_enqueue_scripts", "terrible_loadscripts");

?>