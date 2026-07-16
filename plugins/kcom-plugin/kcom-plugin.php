<?php
/*
Plugin Name: Headless Wordpress Plugin
Description: Accompanying plugin for Headless Wordpress Theme
Author: Mimi Kim
*/

namespace HEADLESS_WORDPRESS;

define( 'HEADLESS_WORDPRESS_TEXT_DOMAIN', 'kcom_plugin' );
define( 'HEADLESS_WORDPRESS_PATH', __FILE__ );
define( 'HEADLESS_WORDPRESS_API_NAMESPACE', 'kc' );
define( 'HEADLESS_WORDPRESS_SITE_URL', 'https://www.testsite.com/' );

require_once( 'src/class-setup.php' );

new Setup();
