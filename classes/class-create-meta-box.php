<?php
/**
 * ---------------------------------------------------------------------------------------------
 * @package Meta Box Class
 * @author webbmakerr
 * @link https://webbmakerr.info
 */

if( ! class_exists( 'omCreateMetaBox' ) ) {
    /**
     * omCreateMetaBox class.
     */
    class omCreateMetaBox
    {
        // Properties
        private $meta_box_args;
        // Methods
        /**
         * Hook into the appropriate action(s) when the class is constructed by __construct function.
         * 
         * @access public
         * @param array $meta_box_args
         * @return void
         */
        function __construct( $meta_box_args ) {
            // initial meta box variable
            $this->meta_box_id = $meta_box_args['meta_box_id'];
            $this->meta_box_title = $meta_box_args['meta_box_title'];
            $this->meta_box_screen = $meta_box_args['meta_box_post_type'];
            $this->meta_box_context = $meta_box_args['meta_box_context'];
            $this->meta_box_priority = $meta_box_args['meta_box_priority'];
            // initial meta box id and name and html
            $this->meta_box_id_and_name = $meta_box_args['meta_box_id_and_name'];
            $this->meta_box_tag_label_text = $meta_box_args['meta_box_tag_label_text'];
            $this->meta_box_start_tag_name = $meta_box_args['meta_box_start_tag_name'];
            $this->meta_box_tag_type = $meta_box_args['meta_box_tag_type'];
            $this->meta_box_tag_placeholder = $meta_box_args['meta_box_tag_placeholder'];
            // initial meta box ACTION and NONCE
            $this->meta_box_nonce = '_om_create_meta_box_nonce';
            $this->meta_box_action = '_om_create_meta_box_action';
            // add_action( 'admin_init', array( $this, 'om_create_meta_box' ), 10, 2 );
            add_action( 'add_meta_boxes', array( $this, 'om_create_meta_box' ), 10, 2 );
            add_action( 'save_post', array( $this, 'om_save_meta_box' ), 10, 2 );
        }
        // Add Meta Box
        /**
         * Adds the meta box container by "om_create_meta_box" function.
         * @access public
         * @return void
         */
        function om_create_meta_box() {
            $limited_post_types_array = array( 'portfolio', 'courses', 'projects' );
            $meta_box_callback = array( $this, 'render_project_name_meta_box_callback' );
            $meta_box_callback_args = '';
            if( in_array( $this->meta_box_screen, $limited_post_types_array  ) ) {
                add_meta_box( 
                    $this->meta_box_id, // Meta box id
                    $this->meta_box_title, // Meta box title
                    $meta_box_callback, // Render function for display meta box on relevant post
                    $this->meta_box_screen, // Post type
                    $this->meta_box_context, // Include each of: 'normal', 'side', 'advanced'
                    $this->meta_box_priority, // Meta box display location: 'high', 'low' | Default value: 'default'
                    $meta_box_callback_args, // Meta box arguments (Second parameter) | Default value: 'null'
                );
            }
        }
        /**
         * Save the meta when the post is saved.
         * 
         * @access public
         * @param int $post_id The ID of the post being saved.
         */
        function om_save_meta_box( $post_id ) {
            /**
             * We need to verify this came from the our screen and with proper authorization,
             * because save_post can be triggered at other times.
             */
            // Check if nonce is set and verfiy that the nonce is valid.
            if( ! isset( $_POST[ $this->meta_box_nonce ] ) || 
                ! wp_verify_nonce( $_POST[ $this->meta_box_nonce ], $this->meta_box_action ) ) {
                return;
            }
            /**
             * If this is an autosave, our form has not been submitted,
             * so we don't want to do anything.
             */
            if( defined( 'DOING_AUTOSAVE' ) && DONIG_AUTOSAVE ) {
                return;
            }
            // Check the user's permission.
            if( 'page' == $_POST['post_type'] ) {
                if( ! current_user_can( 'edit_page', $post_id ) ) {
                    return;
                } else {
                    if( ! current_user_can( 'edit_post', $post_id ) ) {
                        return;
                    }
                }
            }
            /* OK, it's safe for us to save the data now. */
            // Sanitize the user input.
            $om_project_name = sanitize_text_field( $_POST[ $this->meta_box_id_and_name ] );
            // Update the meta field.
            update_post_meta( $post_id, $this->meta_box_id_and_name . '_key', $om_project_name );
        }
        /**
         * Render meta box content.
         * 
         * @access public
         * @param WP_Post $post The post object.
         * @return HTML tags
         */
        function render_project_name_meta_box_callback( $post ) {
            // Add an nonce field so we can check for it later.
            wp_nonce_field( $this->meta_box_action, $this->meta_box_nonce );
            // Use get_post_meta to retrieve an existing value from the database.
            $this->om_project_name_value = get_post_meta( $post->ID, $this->meta_box_id_and_name . '_key', true );
            // Display the form, using the current value.
            echo '<div class="om-meta-box-container">';
            // enter field
            echo '<' . esc_attr( $this->meta_box_start_tag_name ) . ' style="width: 100%" ';
            echo 'type="' . esc_attr( $this->meta_box_tag_type ) . '" ';
            echo 'name="' . esc_attr( $this->meta_box_id_and_name ) . '" ';
            echo 'id="' . esc_attr( $this->meta_box_id_and_name ) . '" ';
            echo 'placeholder="' . esc_attr( $this->meta_box_tag_placeholder ) . '" ';
            echo 'value="' . esc_attr( $this->om_project_name_value );
            echo ( $this->meta_box_start_tag_name == 'input') ? '" size="25" />' : '" cols="100" rows="3" ></' . esc_attr( $this->meta_box_start_tag_name ) . '>';
            // label field
            echo '<br><hr><label style="width: 100%" for="' . esc_attr( $this->meta_box_id_and_name ) . '">';
            echo _e( esc_attr( $this->meta_box_tag_label_text ), "textdomain" );
            echo '</label>';
            echo '</div>';
        }
    }
}
