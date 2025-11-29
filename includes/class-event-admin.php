<?php

class WPExtensionEvent_Admin {

    public function init() {
        add_filter( 'manage_event_posts_columns', array( $this, 'add_custom_columns' ) );
        add_action( 'manage_event_posts_custom_column', array( $this, 'render_custom_columns' ), 10, 2 );
        add_filter( 'manage_edit_event_sortable_columns', array( $this, 'sortable_columns' ) );
    }

    public function add_custom_columns( $columns ) {
        $new_columns = array();
        $new_columns['cb'] = $columns['cb'];
        $new_columns['event_id'] = __( 'ID', 'wpextensionevent' );
        $new_columns['title'] = __( 'Titre', 'wpextensionevent' ); // Standard Title
        $new_columns['event_image'] = __( 'Image', 'wpextensionevent' );
        $new_columns['event_description'] = __( 'Description', 'wpextensionevent' ); // Added Description
        $new_columns['shortcut'] = __( 'Code Court', 'wpextensionevent' ); // Added Shortcut
        $new_columns['event_date'] = __( 'Date Événement', 'wpextensionevent' );
        $new_columns['event_location'] = __( 'Position', 'wpextensionevent' );
        $new_columns['event_price'] = __( 'Prix', 'wpextensionevent' );
        $new_columns['event_seats'] = __( 'Places', 'wpextensionevent' );
        $new_columns['taxonomy-event_tag'] = __( 'Tags', 'wpextensionevent' );
        $new_columns['event_url'] = __( 'URL', 'wpextensionevent' );
        $new_columns['date'] = __( 'Date Création', 'wpextensionevent' ); // Standard Date (Created)
        $new_columns['modified'] = __( 'Date Modif.', 'wpextensionevent' );

        return $new_columns;
    }

    public function render_custom_columns( $column, $post_id ) {
        switch ( $column ) {
            case 'event_id':
                echo $post_id;
                break;
            case 'event_image':
                $external_image = get_post_meta( $post_id, '_event_external_image_url', true );
                if ( has_post_thumbnail( $post_id ) ) {
                    echo get_the_post_thumbnail( $post_id, array( 50, 50 ) );
                } elseif ( $external_image ) {
                    echo '<img src="' . esc_url( $external_image ) . '" style="width:50px;height:50px;object-fit:cover;" />';
                } else {
                    echo '-';
                }
                break;
            case 'event_description': // Render Description
                $post = get_post( $post_id );
                $excerpt = ! empty( $post->post_excerpt ) ? $post->post_excerpt : wp_trim_words( $post->post_content, 10 );
                echo esc_html( $excerpt );
                break;
            case 'event_date':
                echo esc_html( get_post_meta( $post_id, '_event_date', true ) );
                break;
            case 'event_location':
                echo esc_html( get_post_meta( $post_id, '_event_location', true ) );
                break;
            case 'event_price':
                echo esc_html( get_post_meta( $post_id, '_event_price', true ) );
                break;
            case 'event_seats':
                echo esc_html( get_post_meta( $post_id, '_event_seats', true ) );
                break;
            case 'event_url':
                $url = get_post_meta( $post_id, '_event_url', true );
                if ( $url ) {
                    echo '<a href="' . esc_url( $url ) . '" target="_blank">Lien</a>';
                }
                break;
            case 'modified':
                echo get_the_modified_date( 'd/m/Y H:i', $post_id );
                break;
            case 'shortcut':
                // If it's a template, show the template ID shortcut. 
                // But wait, this is 'manage_event_posts_columns' which is only for 'event' post type.
                // The user probably wants the general shortcut or a specific one if they want to filter by ID.
                // Let's assume general usage reminders or ID specific.
                echo '<code>[display_events id="' . $post_id . '"]</code>'; 
                break;
        }
    }

    public function sortable_columns( $columns ) {
        $columns['event_date'] = 'event_date';
        $columns['event_price'] = 'event_price';
        return $columns;
    }
}
