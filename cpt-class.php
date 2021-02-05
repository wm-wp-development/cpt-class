<?php

/*
  Plugin Name: Custom Post Type class
  Plugin URI: https://github.com/wm-wp-development/CPT-class
  Description: Create custom post type by omCreateCPT class, very useful for save time.
  Author: webbmakerr
  Author URI: https://webbmakerr.info
  Version: 1.0.0
  Text Domain: cpt-class
  License: GPLv2 or later
  License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

namespace OMCPT;

/* Security Note: Blocks direct access to the PHP files. */
if( ! defined( 'ABSPATH' ) ) {
    exit();
}

/* Define PATH & URL & VERSION */
define( 'OMCPT_PATH', get_template_directory() );
define( 'OMCPT_URL', get_template_directory_uri() );
define( 'OMCPT_VERSION', '1.0.0' );

/* Include omCreateCPT class */
require_once OMCPT_PATH . '/classes/' . 'class-create-cpt.php';
