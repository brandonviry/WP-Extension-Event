<?php

class WPExtensionEvent_Template {

    public function init() {
        add_filter( 'the_content', array( $this, 'inject_event_details' ) );
    }

    public function inject_event_details( $content ) {
        if ( ! is_singular( 'event' ) || ! in_the_loop() || ! is_main_query() ) {
            return $content;
        }

        $post_id = get_the_ID();
        $date = get_post_meta( $post_id, '_event_date', true );
        $location = get_post_meta( $post_id, '_event_location', true );
        $price = get_post_meta( $post_id, '_event_price', true );
        $url = get_post_meta( $post_id, '_event_url', true );
        $seats = get_post_meta( $post_id, '_event_seats', true );
        $external_image = get_post_meta( $post_id, '_event_external_image_url', true );

        // Determine Image Source
        $img_src = '';
        if ( has_post_thumbnail() ) {
            $img_src = get_the_post_thumbnail_url( $post_id, 'full' );
        } elseif ( $external_image ) {
            $img_src = $external_image;
        }

        // Format Date
        $formatted_date = $date;
        if ( $date ) {
            $date_obj = date_create( $date );
            if ( $date_obj ) {
                $formatted_date = date_i18n( 'l j F Y', $date_obj->getTimestamp() );
            }
        }

        ob_start();
        ?>
        <div class="wpee-single-event">
            <?php if ( $img_src ) : ?>
                <div class="wpee-single-hero" style="background-image: url('<?php echo esc_url( $img_src ); ?>');">
                    <div class="wpee-single-hero-overlay"></div>
                    <div class="wpee-single-hero-content">
                        <h1 class="wpee-single-title"><?php the_title(); ?></h1>
                        <?php if ( $formatted_date ) : ?>
                            <div class="wpee-single-date">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <?php echo esc_html( $formatted_date ); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="wpee-single-info-bar">
                <?php if ( $location ) : ?>
                    <div class="wpee-info-item">
                        <span class="wpee-label"><?php _e( 'Lieu', 'wpextensionevent' ); ?></span>
                        <span class="wpee-value"><?php echo esc_html( $location ); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if ( $seats ) : ?>
                    <div class="wpee-info-item">
                        <span class="wpee-label"><?php _e( 'Places dispo.', 'wpextensionevent' ); ?></span>
                        <span class="wpee-value"><?php echo esc_html( $seats ); ?></span>
                    </div>
                <?php endif; ?>

                <?php if ( $price ) : ?>
                    <div class="wpee-info-item wpee-price-item">
                        <span class="wpee-price"><?php echo esc_html( $price ); ?></span>
                    </div>
                <?php endif; ?>

                <?php if ( $url ) : ?>
                    <div class="wpee-info-item">
                        <a href="<?php echo esc_url( $url ); ?>" class="wpee-card-button wpee-cta-button">
                            <?php _e( 'Réserver / Voir', 'wpextensionevent' ); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="wpee-single-content">
                <?php echo $content; ?>
            </div>
            
            <div class="wpee-single-tags">
                 <?php echo get_the_term_list( $post_id, 'event_tag', __( 'Tags: ', 'wpextensionevent' ), ', ', '' ); ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}
