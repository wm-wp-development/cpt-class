<?php

/**
 * Define omCreateCustomPostType class.
 * 
 * @package Custom Post Type Class
 * @author webbmakerr
 * @link https://webbmakerr.info
 */

namespace OMCPT\Classes;

/* Scurity Note: Blocks direct access to the PHP files. */
if( ! defined( 'ABSPATH' ) ) {
    exit();
}

/* Define PATH & URL & VERSION */
define( 'OMCPTCLASS_PATH', get_template_directory() );
define( 'OMCPTCLASS_URL', get_template_directory_uri() . '/' );
define( 'OMCPTCLASS_VERSION', '1.0.0' );

/* Include omCreateTaxonomy class. */
include_once( OMCPTCLASS_PATH . '/class-create-taxonomy.php' );

use \OMCPT\Classes\omCreateTaxonomy;

/* Checks the absence of a omCreateCustomPoatType class. */
if( ! class_exists( 'omCreateCustomPostType' ) ) {

    /**
     * Create custom post type by "omCreateCustomPostType" class.
     */
    class omCreateCustomPostType
    {

        // Properties
        private $cpt_pupular_name;
        private $cpt_singular_name;
        private $cpt_icon;
        private $cpt_position;
        private $cpt_capability_type;
        private $cpt_show_in_rest;
        private $cpt_taxonomies_array;
        private $cpt_supports_array;
        private $cpt_editor;

        // Methods

        /**
         * Hook into the appropriate action(s) when the class is constructed by "__construct" function.
         * 
         * @access public
         * @param string $cpt_pupular_name
         * @param string $cpt_singular_name
         * @param string $cpt_icon
         * @param integer $cpt_position
         * @param string $cpt_capability_type
         * @param bool $cpt_show_in_rest
         * @param array $cpt_taxonomies_array
         * @param array $cpt_supports_array
         * @param string $cpt_editor
         * 
         * @return void
         */
        function __construct( $cpt_pupular_name, $cpt_singular_name, $cpt_icon, $cpt_position,
            $cpt_capability_type, $cpt_show_in_rest, $cpt_taxonomies_array, $cpt_supports_array )
        {
            $this->cpt_lower_name       = strtolower( $cpt_pupular_name );
            $this->cpt_pupular_name     = ucfirst( strtolower( $cpt_pupular_name ) );
            $this->cpt_singular_name    = ucfirst( strtolower( $cpt_singular_name ) );
            $this->cpt_icon             = $cpt_icon;
            $this->cpt_position         = $cpt_position;
            $this->cpt_capability_type  = $cpt_capability_type;
            $this->cpt_show_in_rest     = $cpt_show_in_rest;
            $this->cpt_taxonomies_array = $cpt_taxonomies_array;
            $this->cpt_supports_array   = $cpt_supports_array;
            $this->cpt_labels_array     = array(
                'name'               => __( $this->cpt_pupular_name ),
                'singular_name'      => __( $this->cpt_singular_name ),
                'all_items'          => __( "All $this->cpt_pupular_name" ),
                'add_new'            => __( "Add $this->cpt_singular_name" ),
                'add_new_item'       => __( "Add New $this->cpt_singular_name" ),
                'edit_item'          => __( "Edit $this->cpt_singular_name" ),
                'new_item'           => __( "New $this->cpt_singular_name" ),
                'view_item'          => __( "View $this->cpt_singular_name" ),
                'search_items'       => __( "Search $this->cpt_singular_name" ),
                'not_found'          => __( "No $this->cpt_singular_name found" ),
                'not_found_in_trash' => __( "No $this->cpt_singular_name found in Trash" ),
                'parent_item_colon'  => __( "Parent $this->cpt_singular_name:" ),
                'menu_name'          => __( $this->cpt_pupular_name ),
                'name_admin_bar'     => __( $this->cpt_singular_name ),
                'attributes'         => __( "$this->cpt_pupular_name attributes" ),
            );
            $this->cpt_slug             = $this->cpt_lower_name . '/%' . $this->cpt_lower_name . '_categories%';
            $this->cpt_args             = array(
                'supports'            => $this->cpt_supports_array,
                'labels'              => $this->cpt_labels_array,
                'hierarchical'        => true,
                'description'         => "Add $this->cpt_pupular_name Section", // Description
                'show_ui'             => true, // Displays an interface for this post type
                'show_in_menu'        => true, // Displays in the Admin Menu (the left panel)
                'show_in_nav_menus'   => true, // Displays in Appearance -> Menus
                'show_in_admin_bar'   => true, // Displays in the black admin bar
                'menu_position'       => $this->cpt_position, // The position number in the left menu
                'menu_icon'           => $this->cpt_icon, // The URL for the icon used for this post type
                'publicly_queryable'  => true, // Allows queries to be performed on the front-end part if set to true
                'exclude_from_search' => false, // Excludes posts of this type in the front-end search result page if set to true, include them if set to false
                'can_export'          => true, // Allows content export using Tools -> Export
                'public'              => true, // Makes the post type public
                'has_archive'         => false,
                'capability_type'     => $this->cpt_capability_type,
                'query_var'           => true,
                'show_in_rest'        => $this->cpt_show_in_rest,
                'rest_base'           => $this->cpt_lower_name,
                'rewrite'             => array( 'slug' => $this->cpt_slug, 'with_front' => false ),
                'taxonomies'          => array( '' ),
            );
            if( ( $this->cpt_taxonomies_array ) ) {
                foreach( $this->cpt_taxonomies_array as $taxonomy_name ) {
                    new omCreateTaxonomy( $taxonomy_name, $this->cpt_lower_name );
                }
            }
            add_action( 'init', array( $this, 'om_create_custom_post_type' ), 10, 2 );
        }

        // Register Post Type

        /**
         * Create custom post type by "om_create_custom_post_type" function.
         * 
         * @access public
         * 
         * @return void
         */
        function om_create_custom_post_type()
        {
            register_post_type( $this->cpt_lower_name, $this->cpt_args );
        }

    }

}
