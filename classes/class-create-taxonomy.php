<?php

/**
 * Define omCreate Taxonomy class.
 * 
 * @package Taxonomy Class
 * @author webbmakerr
 * @link https://webbmakerr.info
 */

namespace OMCPT\Classes;

/* Security Note: Blocks direct access to the PHP files. */
if( ! defined( 'ABSPATH' ) ) {
    exit();
}

/* Define PATH & URL & VERSION */
define( 'OMCTCLASS_PATH', get_template_directory() );
define( 'OMCTCLASS_URL', get_template_directory_uri() );
define( 'OMCTCLASS_VERSION', '1.0.0' );

/* checks the absence of a omCreateTaxonomy class. */
if( ! class_exists( 'omCreateTaxonomy' ) ) {

    /**
     * Create taxonomy CPT by "omCreateTaxonomy" class.
     */
    class omCreateTaxonomy
    {

        // Properties
        private $taxonomy_name;
        private $post_type;

        // Methods

        /**
         * Hook into the appropriate action(s) when the class is constructed by "__construct" function.
         * 
         * @access public
         * @param string $taxonomy_name
         * @param string $post_type
         * @return void
         */
        function __construct( $taxonomy_name, $post_type )
        {
            $this->post_type             = $post_type;
            $this->taxonomy_lower_name   = strtolower( $taxonomy_name );
            $this->taxonomy_pupular_name = ucfirst( $taxonomy_name );
            if( $this->taxonomy_lower_name == 'categories' ) {
                $this->taxonomy_singular_name = str_replace( 'ies', 'y', $this->taxonomy_pupular_name );
            }
            if( $this->taxonomy_lower_name == 'tags' ) {
                $this->taxonomy_singular_name = str_replace( 's', '', $this->taxonomy_pupular_name );
            }
            $this->taxonomy_labels = array(
                'name'              => __( $this->taxonomy_pupular_name ),
                'singular_name'     => __( $this->taxonomy_singular_name ),
                'menu_name'         => __( $this->taxonomy_pupular_name ),
                'all_items'         => __( 'All ' . $this->taxonomy_singular_name ),
                'edit_item'         => __( 'Edit ' . $this->taxonomy_singular_name ),
                'view_item'         => __( 'View ' . $this->taxonomy_singular_name ),
                'update_item'       => __( 'Update ' . $this->taxonomy_singular_name ),
                'add_new_item'      => __( 'Add New ' . $this->taxonomy_singular_name ),
                'new_item_name'     => __( 'New ' . $this->taxonomy_singular_name . ' Name' ),
                'parent_item'       => __( 'Parent ' . $this->taxonomy_singular_name ),
                'parent_item_colon' => __( 'Parent ' . $this->taxonomy_singular_name ),
                'search_items'      => __( 'Search ' . $this->taxonomy_singular_name ),
                'back_to_items'     => __( '← Back to ' . $this->taxonomy_pupular_name ),
                'back_to_items'     => __( 'No ' . $this->taxonomy_pupular_name . ' tags found.' ),
            );
            $this->taxonomy_args   = array(
                'labels'                => $this->taxonomy_labels,
                'hierarchical'          => true,
                'show_ui'               => true,
                'show_admin_column'     => true,
                'query_var'             => true,
                'update_count_callback' => '_update_post_term_count',
                'rewrite'               => array(
                    'slug'       => $this->post_type,
                    'with_front' => false,
                ),
            );
            // Create taxonomy for the custom post type
            add_action( 'init', array( $this, 'om_create_taxonomy' ), 10, 0 );
        }

        /**
         * Create taxonomy for custom post type by "om_create_taxonomy" function.
         * 
         * @access public
         * @return void
         */
        function om_create_taxonomy()
        {
            register_taxonomy(
                $this->post_type . '_' . $this->taxonomy_lower_name,
                $this->post_type,
                $this->taxonomy_args,
            );
        }

    }

}
