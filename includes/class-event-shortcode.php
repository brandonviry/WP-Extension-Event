<?php

class WPExtensionEvent_Shortcode {

    public function init() {
        add_action( 'init', array( $this, 'register_shortcode' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }

    public function register_shortcode() {
        add_shortcode( 'display_events', array( $this, 'render_shortcode' ) );
        add_shortcode( 'wpee_filter', array( $this, 'render_filter_shortcode' ) );
    }

    public function enqueue_scripts() {
        wp_register_style( 'wpee-style', WP_EXTENSION_EVENT_URL . 'assets/css/wpee-style.css', array(), '1.0.0' );
        wp_register_script( 'wpee-script', WP_EXTENSION_EVENT_URL . 'assets/js/wpee-script.js', array(), '1.0.0', true );
    }

    public function render_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'tags' => '',
            'ui_filter' => 'true',
            'limit' => -1,
            'template' => '', // New attribute for Template ID
        ), $atts, 'display_events' );

        wp_enqueue_style( 'wpee-style' );
        wp_enqueue_script( 'wpee-script' );

        // Handle Filters
        $search_query = isset( $_GET['wpee_search'] ) ? sanitize_text_field( $_GET['wpee_search'] ) : '';
        $tag_filter = isset( $_GET['wpee_tag'] ) ? sanitize_text_field( $_GET['wpee_tag'] ) : '';

        // Override shortcode tag if URL param is present, otherwise use shortcode param
        if ( empty( $tag_filter ) && ! empty( $atts['tags'] ) ) {
            $tag_filter = $atts['tags'];
        }

        // Query Arguments
        $args = array(
            'post_type' => 'event',
            'posts_per_page' => intval( $atts['limit'] ),
            'post_status' => 'publish',
        );

        if ( ! empty( $search_query ) ) {
            $args['s'] = $search_query;
        }

        if ( ! empty( $tag_filter ) ) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'event_tag',
                    'field'    => 'slug',
                    'terms'    => explode( ',', $tag_filter ),
                ),
            );
        }

        $query = new WP_Query( $args );

        ob_start();
        ?>
        <div class="wpee-container">
            <?php if ( $atts['ui_filter'] === 'true' ) : ?>
                <form method="GET" class="wpee-filters">
                    <div class="wpee-filter-row">
                        <input type="text" name="wpee_search" class="wpee-search-input" placeholder="<?php _e( 'Rechercher un événement...', 'wpextensionevent' ); ?>" value="<?php echo esc_attr( $search_query ); ?>">
                        
                        <?php
                        $tags = get_terms( array( 'taxonomy' => 'event_tag', 'hide_empty' => true ) );
                        if ( ! empty( $tags ) && ! is_wp_error( $tags ) ) : ?>
                            <div class="wpee-tag-filter">
                                <select name="wpee_tag" onchange="this.form.submit()">
                                    <option value=""><?php _e( 'Tous les tags', 'wpextensionevent' ); ?></option>
                                    <?php foreach ( $tags as $tag ) : ?>
                                        <option value="<?php echo esc_attr( $tag->slug ); ?>" <?php selected( $tag_filter, $tag->slug ); ?>>
                                            <?php echo esc_html( $tag->name ); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endif; ?>
                        
                        <button type="submit" class="wpee-card-button" style="border:none; cursor:pointer;"><?php _e( 'Filtrer', 'wpextensionevent' ); ?></button>
                        <?php if ( ! empty( $search_query ) || ( ! empty( $tag_filter ) && $tag_filter !== $atts['tags'] ) ) : ?>
                            <a href="<?php echo get_permalink(); ?>" style="margin-left: 10px; color: #666; text-decoration: none;"><?php _e( 'Réinitialiser', 'wpextensionevent' ); ?></a>
                        <?php endif; ?>
                    </div>
                </form>
            <?php endif; ?>

            <div class="wpee-grid">
                <?php if ( $query->have_posts() ) : ?>
                    <?php while ( $query->have_posts() ) : $query->the_post(); 
                        if ( ! empty( $atts['template'] ) ) {
                            // Use Custom Template
                            echo '<div class="wpee-custom-card">';
                            echo WPExtensionEvent_Template_Manager::render_template( $atts['template'], get_the_ID() );
                            echo '</div>';
                        } else {
                            // Use Default Card
                            $this->render_event_card();
                        }
                    endwhile; ?>
                <?php else : ?>
                    <p><?php _e( 'Aucun événement trouvé. (Vérifiez que vos événements sont bien publiés)', 'wpextensionevent' ); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php
        wp_reset_postdata();
        return ob_get_clean();
    }

    public function render_filter_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'template' => '',
        ), $atts, 'wpee_filter' );

        wp_enqueue_style( 'wpee-style' );
        wp_enqueue_script( 'wpee-script' );
        
        // If Custom Template
        if ( ! empty( $atts['template'] ) ) {
            // Get current URL base without query args to avoid stacking
            $current_url = strtok( $_SERVER["REQUEST_URI"], '?' );
            ob_start();
            ?>
            <form method="GET" class="wpee-filters-custom" action="<?php echo esc_url( $current_url ); ?>">
                <?php echo WPExtensionEvent_Template_Manager::render_template( $atts['template'], 0 ); // 0 because no specific event needed ?>
            </form>
            <?php
            return ob_get_clean();
        }

        // Default Layout
        $search_query = isset( $_GET['wpee_search'] ) ? sanitize_text_field( $_GET['wpee_search'] ) : '';
        $tag_filter = isset( $_GET['wpee_tag'] ) ? sanitize_text_field( $_GET['wpee_tag'] ) : '';
        $current_url = strtok( $_SERVER["REQUEST_URI"], '?' );
        
        ob_start();
        ?>
        <form method="GET" class="wpee-filters" action="<?php echo esc_url( $current_url ); ?>">
            <div class="wpee-filter-row">
                <input type="text" name="wpee_search" class="wpee-search-input" placeholder="<?php _e( 'Rechercher un événement...', 'wpextensionevent' ); ?>" value="<?php echo esc_attr( $search_query ); ?>">
                
                <?php
                $tags = get_terms( array( 'taxonomy' => 'event_tag', 'hide_empty' => true ) );
                if ( ! empty( $tags ) && ! is_wp_error( $tags ) ) : ?>
                    <div class="wpee-tag-filter">
                        <select name="wpee_tag" onchange="this.form.submit()">
                            <option value=""><?php _e( 'Tous les tags', 'wpextensionevent' ); ?></option>
                            <?php foreach ( $tags as $tag ) : ?>
                                <option value="<?php echo esc_attr( $tag->slug ); ?>" <?php selected( $tag_filter, $tag->slug ); ?>>
                                    <?php echo esc_html( $tag->name ); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>
                
                <button type="submit" class="wpee-card-button" style="border:none; cursor:pointer;"><?php _e( 'Filtrer', 'wpextensionevent' ); ?></button>
                <?php if ( ! empty( $search_query ) || ! empty( $tag_filter ) ) : ?>
                    <a href="<?php echo get_permalink(); ?>" style="margin-left: 10px; color: #666; text-decoration: none;"><?php _e( 'Réinitialiser', 'wpextensionevent' ); ?></a>
                <?php endif; ?>
            </div>
        </form>
        <?php
        return ob_get_clean();
    }

    private function render_event_card() {
        $post_id = get_the_ID();
        $date = get_post_meta( $post_id, '_event_date', true );
        $location = get_post_meta( $post_id, '_event_location', true );
        $price = get_post_meta( $post_id, '_event_price', true );
        $url = get_post_meta( $post_id, '_event_url', true );
        $external_image = get_post_meta( $post_id, '_event_external_image_url', true );
        
        // Image Logic
        $img_src = '';
        if ( has_post_thumbnail() ) {
            $img_src = get_the_post_thumbnail_url( $post_id, 'large' );
        } elseif ( $external_image ) {
            $img_src = $external_image;
        } else {
            $img_src = 'https://via.placeholder.com/800x600?text=No+Image'; // Fallback
        }

        // Tags
        $terms = get_the_terms( $post_id, 'event_tag' );
        $first_tag = ( ! empty( $terms ) && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';

        // Format Date
        $formatted_date = '';
        if ( $date ) {
            $date_obj = date_create( $date );
            if ( $date_obj ) {
                $formatted_date = date_i18n( 'd F Y', $date_obj->getTimestamp() );
            } else {
                $formatted_date = $date;
            }
        }

        ?>
        <div class="wpee-card">
            <div class="wpee-card-image-wrapper">
                <img class="wpee-card-image" src="<?php echo esc_url( $img_src ); ?>" alt="<?php the_title_attribute(); ?>">
                <?php if ( $first_tag ) : ?>
                    <div class="wpee-card-tag">
                        <?php echo esc_html( $first_tag ); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="wpee-card-content">
                <h3 class="wpee-card-title"><?php the_title(); ?></h3>
                
                <?php if ( $formatted_date ) : ?>
                    <div class="wpee-card-meta">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <?php echo esc_html( $formatted_date ); ?>
                    </div>
                <?php endif; ?>

                <?php if ( $location ) : ?>
                    <div class="wpee-card-meta">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <?php echo esc_html( $location ); ?>
                    </div>
                <?php endif; ?>

                <div class="wpee-card-description">
                    <?php the_excerpt(); ?>
                </div>

                <div class="wpee-card-footer">
                    <?php if ( $price ) : ?>
                        <span class="wpee-card-price"><?php echo esc_html( $price ); ?></span>
                    <?php endif; ?>
                    
                    <?php if ( $url ) : ?>
                        <a href="<?php echo esc_url( $url ); ?>" class="wpee-card-button">
                            <?php _e( 'Voir', 'wpextensionevent' ); ?>
                            <svg class="w-4 h-4 ml-2" style="width:1rem;height:1rem;margin-left:0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }
}
