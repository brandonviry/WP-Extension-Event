<?php

class WPExtensionEvent_Template_Manager {

    public function init() {
        add_action( 'init', array( $this, 'register_template_cpt' ) );
        
        // Register dynamic shortcodes to be used INSIDE the template
        add_shortcode( 'wpee_title', array( $this, 'sc_title' ) );
        add_shortcode( 'wpee_date', array( $this, 'sc_date' ) );
        add_shortcode( 'wpee_price', array( $this, 'sc_price' ) );
        add_shortcode( 'wpee_seats', array( $this, 'sc_seats' ) ); // Added Seats
        add_shortcode( 'wpee_location', array( $this, 'sc_location' ) );
        add_shortcode( 'wpee_image', array( $this, 'sc_image' ) );
        add_shortcode( 'wpee_image_url', array( $this, 'sc_image_url' ) );
        add_shortcode( 'wpee_tag', array( $this, 'sc_tag' ) ); // Added Tag
        add_shortcode( 'wpee_event_url', array( $this, 'sc_event_url' ) ); // Added Event URL
        add_shortcode( 'wpee_description', array( $this, 'sc_description' ) ); // Added Description
        add_shortcode( 'wpee_excerpt', array( $this, 'sc_excerpt' ) ); // Added Excerpt
        add_shortcode( 'wpee_link_start', array( $this, 'sc_link_start' ) ); // For wrapping
        add_shortcode( 'wpee_link_end', array( $this, 'sc_link_end' ) );
        add_shortcode( 'wpee_read_more', array( $this, 'sc_read_more' ) );

        // Filter Building Blocks
        add_shortcode( 'wpee_input_search', array( $this, 'sc_input_search' ) );
        add_shortcode( 'wpee_input_tags', array( $this, 'sc_input_tags' ) );
        add_shortcode( 'wpee_filter_submit', array( $this, 'sc_filter_submit' ) );
        add_shortcode( 'wpee_filter_reset', array( $this, 'sc_filter_reset' ) );
        add_shortcode( 'wpee_filter_tag', array( $this, 'sc_filter_tag' ) ); // Added Tag Filter Button

        // Add columns to Template CPT
        add_filter( 'manage_wpee_template_posts_columns', array( $this, 'add_template_columns' ) );
        add_action( 'manage_wpee_template_posts_custom_column', array( $this, 'render_template_columns' ), 10, 2 );
    }

    public function add_template_columns( $columns ) {
        $new_columns = array();
        $new_columns['cb'] = $columns['cb'];
        $new_columns['title'] = $columns['title'];
        $new_columns['shortcut'] = __( 'Shortcode à utiliser', 'wpextensionevent' );
        $new_columns['date'] = $columns['date'];
        return $new_columns;
    }

    public function render_template_columns( $column, $post_id ) {
        if ( 'shortcut' === $column ) {
             echo '<code style="user-select:all;">[display_events template="' . $post_id . '"]</code>';
        }
    }

    public function register_template_cpt() {
        register_post_type( 'wpee_template', array(
            'labels' => array(
                'name' => __( 'Templates de Carte', 'wpextensionevent' ),
                'singular_name' => __( 'Template', 'wpextensionevent' ),
            ),
            'public' => true,
            'show_in_rest' => true, // Fix for Elementor and Block Editor loading
            'show_ui' => true,
            'show_in_menu' => 'edit.php?post_type=event', // Put it under Events menu
            'supports' => array( 'title', 'editor', 'elementor' ), // Support Elementor
            'rewrite' => array( 'slug' => 'wpee-template' ),
        ) );
    }

    // --- Helper to render a template for a specific event ---
    public static function render_template( $template_id, $event_id ) {
        if ( ! $template_id ) return '<!-- No Template ID provided -->';
        
        $template_post = get_post( $template_id );
        if ( ! $template_post ) return '<!-- Template not found: ' . esc_html( $template_id ) . ' -->';

        // Set global post to event so shortcodes work naturally
        global $post;
        $original_post = $post;
        $post = get_post( $event_id );
        setup_postdata( $post );

        $content = '';

        // Elementor Support
        if ( class_exists( '\\Elementor\\Plugin' ) ) {
             $elementor = \Elementor\Plugin::instance();
             $document = $elementor->documents->get( $template_id );
             if ( $document && $document->is_built_with_elementor() ) {
                 $content = $elementor->frontend->get_builder_content_for_display( $template_id, true ); // true for with_css
             }
        }

        // Breakdance Support (Explicit)
        if ( empty( $content ) && function_exists( 'Breakdance\\Render\\render' ) ) {
            // Breakdance usually requires the global post to be the one we are rendering if we use the_content
            // But if we use their render function:
            $content = \Breakdance\Render\render( $template_id );
            // Breakdance might not run shortcodes in render, so we force it
            $content = do_shortcode( $content );
        }

        // Fallback to standard content (Gutenberg/Classic/Other Builders)
        if ( empty( $content ) ) {
            // Use apply_filters('the_content') to allow other builders/shortcodes to run
            // We temporarily unhook do_shortcode to avoid double processing if the builder does it, 
            // but usually it's fine.
            $content = apply_filters( 'the_content', $template_post->post_content );
            
            // Check if still empty
            if ( empty( trim( $content ) ) ) {
                 $content = '<!-- Template content is empty. Make sure to Publish your template. -->';
            }
        }

        // Reset global post
        $post = $original_post;
        if ( $post ) setup_postdata( $post );
        else wp_reset_postdata();

        return $content;
    }

    // --- Shortcodes Implementation ---

    private function get_current_id() {
        // This relies on the loop setting up the post data correctly
        return get_the_ID();
    }

    public function sc_title( $atts ) {
        return get_the_title();
    }

    public function sc_date( $atts ) {
        $date = get_post_meta( get_the_ID(), '_event_date', true );
        if ( ! $date ) return '';
        $date_obj = date_create( $date );
        return $date_obj ? date_i18n( 'd F Y', $date_obj->getTimestamp() ) : $date;
    }

    public function sc_price( $atts ) {
        return get_post_meta( get_the_ID(), '_event_price', true );
    }

    public function sc_seats( $atts ) {
        return get_post_meta( get_the_ID(), '_event_seats', true );
    }

    public function sc_location( $atts ) {
        return get_post_meta( get_the_ID(), '_event_location', true );
    }

    public function sc_image( $atts ) {
        $id = get_the_ID();
        $external = get_post_meta( $id, '_event_external_image_url', true );
        $img = '';
        if ( has_post_thumbnail( $id ) ) {
            $img = get_the_post_thumbnail( $id, 'medium_large', array( 'style' => 'width:100%;height:auto;' ) );
        } elseif ( $external ) {
            $img = '<img src="' . esc_url( $external ) . '" alt="' . esc_attr( get_the_title() ) . '" style="width:100%;height:auto;" />';
        }
        return $img;
    }

    public function sc_image_url( $atts ) {
        $id = get_the_ID();
        $external = get_post_meta( $id, '_event_external_image_url', true );
        if ( has_post_thumbnail( $id ) ) {
            return get_the_post_thumbnail_url( $id, 'medium_large' );
        }
        return $external;
    }

    public function sc_tag( $atts ) {
        $terms = get_the_terms( get_the_ID(), 'event_tag' );
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            return $terms[0]->name;
        }
        return '';
    }

    public function sc_event_url( $atts ) {
        return get_permalink();
    }

    public function sc_description( $atts ) {
        return get_the_content();
    }

    public function sc_excerpt( $atts ) {
        return get_the_excerpt();
    }

    public function sc_link_start( $atts ) {
        $url = get_permalink();
        return '<a href="' . esc_url( $url ) . '" class="wpee-dynamic-link">';
    }

    public function sc_link_end( $atts ) {
        return '</a>';
    }

    public function sc_read_more( $atts ) {
        $atts = shortcode_atts( array( 'text' => 'Voir' ), $atts );
        return '<a href="' . get_permalink() . '" class="wpee-card-button">' . esc_html( $atts['text'] ) . '</a>';
    }

    // --- Filter Components ---

    public function sc_input_search( $atts ) {
        $val = isset( $_GET['wpee_search'] ) ? sanitize_text_field( $_GET['wpee_search'] ) : '';
        $ph = isset( $atts['placeholder'] ) ? $atts['placeholder'] : __( 'Rechercher...', 'wpextensionevent' );
        return '<input type="text" name="wpee_search" class="wpee-search-input" placeholder="' . esc_attr( $ph ) . '" value="' . esc_attr( $val ) . '">';
    }

    public function sc_input_tags( $atts ) {
        $val = isset( $_GET['wpee_tag'] ) ? sanitize_text_field( $_GET['wpee_tag'] ) : '';
        $label = isset( $atts['label'] ) ? $atts['label'] : __( 'Tous les tags', 'wpextensionevent' );
        
        $tags = get_terms( array( 'taxonomy' => 'event_tag', 'hide_empty' => true ) );
        if ( empty( $tags ) || is_wp_error( $tags ) ) return '';

        $out = '<div class="wpee-tag-filter"><select name="wpee_tag" onchange="this.form.submit()">';
        $out .= '<option value="">' . esc_html( $label ) . '</option>';
        foreach ( $tags as $tag ) {
            $selected = selected( $val, $tag->slug, false );
            $out .= '<option value="' . esc_attr( $tag->slug ) . '" ' . $selected . '>' . esc_html( $tag->name ) . '</option>';
        }
        $out .= '</select></div>';
        return $out;
    }

    public function sc_filter_submit( $atts ) {
        $text = isset( $atts['text'] ) ? $atts['text'] : __( 'Filtrer', 'wpextensionevent' );
        return '<button type="submit" class="wpee-card-button" style="border:none; cursor:pointer;">' . esc_html( $text ) . '</button>';
    }

    public function sc_filter_reset( $atts ) {
        $search_query = isset( $_GET['wpee_search'] ) ? $_GET['wpee_search'] : '';
        $tag_filter = isset( $_GET['wpee_tag'] ) ? $_GET['wpee_tag'] : '';
        $text = isset( $atts['text'] ) ? $atts['text'] : __( 'Réinitialiser', 'wpextensionevent' );

        if ( ! empty( $search_query ) || ! empty( $tag_filter ) ) {
             // Get current URL without query params
             $url = strtok( $_SERVER["REQUEST_URI"], '?' );
             return '<a href="' . esc_url( $url ) . '" style="margin-left: 10px; color: #666; text-decoration: none;">' . esc_html( $text ) . '</a>';
        }
        return '';
    }

    public function sc_filter_tag( $atts ) {
        $atts = shortcode_atts( array(
            'slug' => '',
            'text' => '',
        ), $atts );

        if ( empty( $atts['slug'] ) ) return '';
        $text = ! empty( $atts['text'] ) ? $atts['text'] : ucfirst( $atts['slug'] );

        // Check if currently active
        $current_tag = isset( $_GET['wpee_tag'] ) ? $_GET['wpee_tag'] : '';
        $is_active = ( $current_tag === $atts['slug'] );
        
        $active_class = $is_active ? 'wpee-tag-active' : '';
        $style = $is_active ? 'background-color: #333; color: white;' : 'background-color: #eee; color: #333;';

        // Build URL with this tag
        // We need to keep search param if exists
        $url = strtok( $_SERVER["REQUEST_URI"], '?' );
        $params = $_GET;
        $params['wpee_tag'] = $atts['slug'];
        $new_url = $url . '?' . http_build_query( $params );

        return '<a href="' . esc_url( $new_url ) . '" class="wpee-filter-pill ' . $active_class . '" style="display:inline-block; padding:5px 15px; border-radius:20px; text-decoration:none; margin-right:5px; font-size:14px; ' . $style . '">' . esc_html( $text ) . '</a>';
    }
}
