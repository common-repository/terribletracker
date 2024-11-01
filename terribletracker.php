<?php
/*
Plugin Name: Terribletracker - Google Analytics tracking
Plugin URI: https://terribletraffic.com
Description: Simply enter your UA-code to integrate Google Analytics into your WordPress site. Build for Site Speed: extremely lightweight, no clutter, no unnecessary code. Just tracking.
Author: terribletraffic
Author URI: https://terribletraffic.com/
Version: 1.1.1
Text Domain: terribletraffic
License: GPL2 - http://www.gnu.org/licenses/gpl-2.0.html
*/

function terrible_settings_init(){
	// tracking setup
    //section name, display name, callback to print description of section, page to which section is attached.
	add_settings_section("tracking_settings", "Tracking Settings", "display_tracking_settings", "settingspage");
	//setting name, display name, callback to print form element, page in which field is displayed, section to which it belongs.
    add_settings_field("ua_code", "Google Analytics UA-code", "terrible_ua_code_input", "settingspage", "tracking_settings");
    //section name, form element name, callback for sanitization
    register_setting("tracking_settings", "ua_code", "terrible_validate_ua_code");

}


// CREATE HANDY CONSTANTS
if(!defined('terrible_URL'))
	define('terrible_URL', plugin_dir_url( __FILE__ ));
if(!defined('terrible_PATH'))
	define('terrible_PATH', plugin_dir_path( __FILE__ ));


// INCLUDE REST OF PHP FILES
require_once( plugin_dir_path( __FILE__ ) . 'tracking.php');



/**
 * Register a custom menu page.
 */
function terrible_add_settings_page()
	{
		$TTicon = terrible_URL . 'assets/images/icon.svg';
		
    	add_menu_page( 
        	__( 'Terribletracker', 'terribletraffic' ),
        	'Terribletracker',
        	'manage_options',
        	'settingspage',
        	'terrible_settings_page',
        	'' /* should be icon WIP */,
        	6
    	); 
    	
	}

 
// build settings page TOPLEVEL
function terrible_settings_page(){	
    ?>
    	<div class='TTwrap'>
    		<div class='TTmain'>
    		<img src=
    		<?php echo terrible_URL . 'assets/images/icon.svg';
    		?> class='TTlogo' />
    		<h1>Welcome to Terribletracker</h1>
    		<p>Lightweight Google Analytics tracking. <a href='https://terribletraffic.com/contact-us/' target=_blank>Feedback? We'd love to hear it - please contact us »</a></p>
    		<p>Let's get started.</p>
    		<div class='TTfeedback'>
    		<?php
    		$ua_code = get_option('ua_code');
    		
    		if ( !ISSET($ua_code) || $ua_code == null ) 
    				{
    					echo "<div class='red'><p><b>Not tracking!</b> - Please enter your UA-code below to start tracking. An UA-code should look like <b>UA-12345-12</b>.</p></div>";
    				}
    		if (ISSET($ua_code) && $ua_code != null )
    				{
    					echo "<div class='green'><img src='" . terrible_URL . "assets/images/ga_icon.png' style='width: 25px; height: 25px; margin: 0 10px -5px 0;'  /><p>Tracking like a boss!</p></div>";
					}
    		?>
			</div>
    		<form method="post" action="options.php">
    		
    		    <?php
                
                    //add_settings_section callback is displayed here. For every new section we need to call settings_fields.
                    settings_fields("tracking_settings");
                    
                    // all the add_settings_field callbacks is displayed here
                    do_settings_sections("settingspage");
                
                    // Add the submit button to serialize the options
                    submit_button(); 
                    
                ?>   
    		
			</form>
			</div>
    	</div>
    	
    <?php
}


function terrible_ua_code_input(){
	// create input field for ua_code
    ?>
        <input type="text" name="ua_code" id="ua_code" value="<?php echo get_option('ua_code'); ?>" />
    <?php
}

// SHOW ERRORS
		function your_admin_notices_action()
			{
    			settings_errors( 'ua_code' );
			}

function isThisAnUAcode($input){
		return (bool) preg_match('/^ua-\d{4,10}(-\d{1,4})?$/i', $input);
	}
	
function terrible_validate_ua_code( $input ){
	
	// FEEDBACK BUILDING
	$message;
	$type;
	
	if( ISSET($input)){
		
		$valid = isThisAnUAcode($input);
		
			if ( $valid ){
					$type = 'success';
					$message = 'UA-code set successfully';
					
				}
			else
				{
					$type = 'error';
					$message = 'format should be UA-12345-12';
					$input = null;
				}
				
		add_settings_error('ua_code', 'ua_code_errors', $message, $type);
		
		
		return $input;
		}
	}


// deprecated
function display_tracking_settings(){}






function terrible_load_styles() { 
	
	// LOCATION OF STYLESHEETS
	$css = terrible_URL . 'assets/style.css';
	// REGISTER STYLE
	wp_register_style( 'admincss', $css );

    
    wp_enqueue_style( 'admincss');   
}

// Display Errors
add_action( 'admin_notices', 'your_admin_notices_action' );

add_action( 'admin_init', 'terrible_settings_init' );
add_action( 'admin_menu', 'terrible_add_settings_page' );
add_action( 'admin_enqueue_scripts', 'terrible_load_styles' );

?>