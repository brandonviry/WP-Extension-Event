<?php

class WPExtensionEvent_Post_Type {

    public function init() {
        add_action( 'init', array( $this, 'register_post_type' ) );
        add_action( 'init', array( $this, 'register_taxonomy' ) );
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'save_meta_boxes' ) );
    }

    public function register_post_type() {
        $labels = array(
            'name'                  => _x( 'Événements', 'Post Type General Name', 'wpextensionevent' ),
            'singular_name'         => _x( 'Événement', 'Post Type Singular Name', 'wpextensionevent' ),
            'menu_name'             => __( 'Événements', 'wpextensionevent' ),
            'all_items'             => __( 'Tous les événements', 'wpextensionevent' ),
            'add_new_item'          => __( 'Ajouter un événement', 'wpextensionevent' ),
            'edit_item'             => __( 'Modifier l\'événement', 'wpextensionevent' ),
            'new_item'              => __( 'Nouvel événement', 'wpextensionevent' ),
            'view_item'             => __( 'Voir l\'événement', 'wpextensionevent' ),
        );

        $args = array(
            'label'                 => __( 'Événement', 'wpextensionevent' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'excerpt' ), // Added excerpt support
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-calendar-alt',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
        );

        register_post_type( 'event', $args );
    }

    public function register_taxonomy() {
        $labels = array(
            'name'                       => _x( 'Tags Événement', 'Taxonomy General Name', 'wpextensionevent' ),
            'singular_name'              => _x( 'Tag', 'Taxonomy Singular Name', 'wpextensionevent' ),
            'menu_name'                  => __( 'Tags', 'wpextensionevent' ),
        );
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => false,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
        );
        register_taxonomy( 'event_tag', array( 'event' ), $args );
    }

    public function add_meta_boxes() {
        add_meta_box(
            'event_details',
            __( 'Détails de l\'événement', 'wpextensionevent' ),
            array( $this, 'render_meta_box' ),
            'event',
            'normal',
            'high'
        );
    }

    public function render_meta_box( $post ) {
        wp_nonce_field( 'save_event_details', 'event_details_nonce' );

        $date = get_post_meta( $post->ID, '_event_date', true );
        $location = get_post_meta( $post->ID, '_event_location', true );
        $price = get_post_meta( $post->ID, '_event_price', true );
        $url = get_post_meta( $post->ID, '_event_url', true );
        $seats = get_post_meta( $post->ID, '_event_seats', true );
        $external_image = get_post_meta( $post->ID, '_event_external_image_url', true );

        ?>
        <style>
            .event_meta_row { margin-bottom: 15px; display: flex; align-items: center; }
            .event_meta_row label { width: 150px; font-weight: bold; }
            .event_meta_row input { width: 100%; max-width: 400px; }
        </style>
        <div class="event_meta_box">
            <div class="event_meta_row">
                <label for="event_date"><?php _e( 'Date', 'wpextensionevent' ); ?></label>
                <input type="date" id="event_date" name="event_date" value="<?php echo esc_attr( $date ); ?>">
            </div>
            <div class="event_meta_row">
                <label for="event_location"><?php _e( 'Position (Lieu)', 'wpextensionevent' ); ?></label>
                <input type="text" id="event_location" name="event_location" value="<?php echo esc_attr( $location ); ?>">
            </div>
            <div class="event_meta_row">
                <label for="event_price"><?php _e( 'Prix', 'wpextensionevent' ); ?></label>
                <input type="text" id="event_price" name="event_price" value="<?php echo esc_attr( $price ); ?>">
            </div>
            <div class="event_meta_row">
                <label for="event_url"><?php _e( 'URL', 'wpextensionevent' ); ?></label>
                <input type="url" id="event_url" name="event_url" value="<?php echo esc_attr( $url ); ?>">
            </div>
            <div class="event_meta_row">
                <label for="event_seats"><?php _e( 'Nombre de places', 'wpextensionevent' ); ?></label>
                <input type="number" id="event_seats" name="event_seats" value="<?php echo esc_attr( $seats ); ?>">
            </div>
            <div class="event_meta_row">
                <label for="event_external_image_url"><?php _e( 'URL Image Externe', 'wpextensionevent' ); ?></label>
                <input type="url" id="event_external_image_url" name="event_external_image_url" value="<?php echo esc_attr( $external_image ); ?>" placeholder="https://...">
                <p class="description" style="margin-left: 150px;"><?php _e( 'Laissez vide si vous utilisez l\'image mise en avant.', 'wpextensionevent' ); ?></p>
            </div>
        </div>
        <?php
    }

    public function save_meta_boxes( $post_id ) {
        if ( ! isset( $_POST['event_details_nonce'] ) ) {
            return;
        }
        if ( ! wp_verify_nonce( $_POST['event_details_nonce'], 'save_event_details' ) ) {
            return;
        }
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        $fields = array(
            'event_date' => '_event_date',
            'event_location' => '_event_location',
            'event_price' => '_event_price',
            'event_url' => '_event_url',
            'event_seats' => '_event_seats',
            'event_external_image_url' => '_event_external_image_url',
        );

        foreach ( $fields as $input_name => $meta_key ) {
            if ( isset( $_POST[ $input_name ] ) ) {
                update_post_meta( $post_id, $meta_key, sanitize_text_field( $_POST[ $input_name ] ) );
            }
        }
    }
}
