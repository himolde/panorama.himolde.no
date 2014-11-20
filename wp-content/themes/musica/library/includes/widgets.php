<?php
/**
 * Widget Registration
 * @package Musica
 */

add_action('widgets_init', 'kopa_widgets_init');

function kopa_widgets_init() {
    register_widget('Kopa_Widget_Text');
    register_widget('Kopa_Widget_Flexslider');
    register_widget('Kopa_Widget_Articles_List');
    register_widget('Kopa_Widget_Articles_Carousel');
    register_widget('Kopa_Widget_Entry_List');
    register_widget('Kopa_Widget_Twitter');
    register_widget('Kopa_Widget_Advertising');
    register_widget('Kopa_Widget_Gallery');
    register_widget('Kopa_Widget_Articles_Tabs_List');
    register_widget('Kopa_Widget_Audio');
    register_widget('Kopa_Widget_Articles_List_2');
    register_widget('Kopa_Widget_Entry_List_2');
    register_widget('Kopa_Widget_Featured_News');
    register_widget('Kopa_Widget_Mailchimp_Subscribe');
    register_widget('Kopa_Widget_Feedburner_Subscribe');
}

add_action('admin_enqueue_scripts', 'kopa_widget_admin_enqueue_scripts');

function kopa_widget_admin_enqueue_scripts($hook) {
    if ('widgets.php' === $hook) {
        $dir = get_template_directory_uri() . '/library';
        wp_enqueue_style('kopa_widget_admin', "{$dir}/css/widget.css");
        wp_enqueue_script('kopa_widget_admin', "{$dir}/js/widget.js", array('jquery'));
    }
}

function kopa_widget_article_build_query($query_args = array()) {
    $args = array(
        'post_type' => array('post'),
        'posts_per_page' => $query_args['number_of_article']
    );

    $tax_query = array();

    if ($query_args['categories']) {
        $tax_query[] = array(
            'taxonomy' => 'category',
            'field' => 'id',
            'terms' => $query_args['categories']
        );
    }
    if ($query_args['tags']) {
        $tax_query[] = array(
            'taxonomy' => 'post_tag',
            'field' => 'id',
            'terms' => $query_args['tags']
        );
    }
    if ($query_args['relation'] && count($tax_query) == 2) {
        $tax_query['relation'] = $query_args['relation'];
    }

    if ($tax_query) {
        $args['tax_query'] = $tax_query;
    }

    switch ($query_args['orderby']) {
        case 'popular':
            $args['meta_key'] = 'kopa_' . kopa_get_domain() . '_total_view';
            $args['orderby'] = 'meta_value_num';
            break;
        case 'most_comment':
            $args['orderby'] = 'comment_count';
            break;
        case 'random':
            $args['orderby'] = 'rand';
            break;
        default:
            $args['orderby'] = 'date';
            break;
    }
    if (isset($query_args['post__not_in']) && $query_args['post__not_in']) {
        $args['post__not_in'] = $query_args['post__not_in'];
    }
    return new WP_Query($args);
}

function kopa_widget_posttype_build_query( $query_args = array() ) {
    $default_query_args = array(
        'post_type'      => 'post',
        'posts_per_page' => -1,
        'post__not_in'   => array(),
        'ignore_sticky_posts' => 1,
        'categories'     => array(),
        'tags'           => array(),
        'relation'       => 'OR',
        'orderby'        => 'lastest',
        'cat_name'       => 'category',
        'tag_name'       => 'post_tag'
    );

    $query_args = wp_parse_args( $query_args, $default_query_args );

    $args = array(
        'post_type'           => $query_args['post_type'],
        'posts_per_page'      => $query_args['posts_per_page'],
        'post__not_in'        => $query_args['post__not_in'],
        'ignore_sticky_posts' => $query_args['ignore_sticky_posts']
    );

    $tax_query = array();

    if ( $query_args['categories'] ) {
        $tax_query[] = array(
            'taxonomy' => $query_args['cat_name'],
            'field'    => 'id',
            'terms'    => $query_args['categories']
        );
    }
    if ( $query_args['tags'] ) {
        $tax_query[] = array(
            'taxonomy' => $query_args['tag_name'],
            'field'    => 'id',
            'terms'    => $query_args['tags']
        );
    }
    if ( $query_args['relation'] && count( $tax_query ) == 2 ) {
        $tax_query['relation'] = $query_args['relation'];
    }

    if ( $tax_query ) {
        $args['tax_query'] = $tax_query;
    }

    switch ( $query_args['orderby'] ) {
    case 'popular':
        $args['meta_key'] = 'kopa_' . kopa_get_domain() . '_total_view';
        $args['orderby'] = 'meta_value_num';
        break;
    case 'most_comment':
        $args['orderby'] = 'comment_count';
        break;
    case 'random':
        $args['orderby'] = 'rand';
        break;
    default:
        $args['orderby'] = 'date';
        break;
    }

    return new WP_Query( $args );
}

class Kopa_Widget_Text extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'kopa_widget_text', 'description' => __('Arbitrary text, HTML or shortcodes', kopa_get_domain()));
        $control_ops = array('width' => 600, 'height' => 400);
        parent::__construct('kopa_widget_text', __('Kopa Text', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $text = apply_filters('widget_text', empty($instance['text']) ? '' : $instance['text'], $instance);

        echo $before_widget;
        if ( !empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }
        ?>
        <?php echo !empty($instance['filter']) ? wpautop($text) : $text; ?>
        <?php
        echo $after_widget;
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        if (current_user_can('unfiltered_html')) {
            $instance['text'] = $new_instance['text'];
        } else {
            $instance['text'] = stripslashes(wp_filter_post_kses(addslashes($new_instance['text'])));
        }
        $instance['filter'] = isset($new_instance['filter']);
        return $instance;
    }

    function form($instance) {
        $instance = wp_parse_args((array) $instance, array('title' => '', 'text' => ''));
        $title = strip_tags($instance['title']);
        $text = esc_textarea($instance['text']);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>        
        <ul class="kopa_shortcode_icons">
            <?php
            $shortcodes = array(
                'one_half' => 'One Half Column',
                'one_third' => 'One Thirtd Column',
                'two_third' => 'Two Third Column',
                'one_fourth' => 'One Fourth Column',
                'three_fourth' => 'Three Fourth Column',
                'dropcaps' => 'Add Dropcaps Text',
                'button' => 'Add A Button',
                'alert' => 'Add A Alert Box',
                'tabs' => 'Add A Tabs Content',
                'accordions' => 'Add A Accordions Content',
                'toggle' => 'Add A Toggle Content',
                'contact_form' => 'Add A Contact Form',
                'posts_lastest' => 'Add A List Latest Post',
                'posts_popular' => 'Add A List Popular Post',
                'posts_most_comment' => 'Add A List Most Comment Post',
                'posts_random' => 'Add A List Random Post',
                'youtube' => 'Add A Yoube Video Box',
                'vimeo' => 'Add A Vimeo Video Box'
            );
            foreach ($shortcodes as $rel => $title):
                ?>
                <li>
                    <a onclick="return kopa_shortcode_icon_click('<?php echo $rel; ?>', jQuery('#<?php echo $this->get_field_id('text'); ?>'));" href="#" class="<?php echo "kopa-icon-{$rel}"; ?>" rel="<?php echo $rel; ?>" title="<?php echo $title; ?>"></a>
                </li>
            <?php endforeach; ?>
        </ul>        
        <textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>
        <p>
            <input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs', kopa_get_domain()); ?></label>
        </p>
        <?php
    }

}

/**
 * Flexslider Widget Class
 * @since Musica 1.0
 */
class Kopa_Widget_Flexslider extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => '', 'description' => __('A Posts Slider Widget', kopa_get_domain()));
        $control_ops = array('width' => '500', 'height' => 'auto');
        parent::__construct('kopa_widget_flexslider', __('Kopa Flexslider', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $query_args['categories'] = $instance['categories'];
        $query_args['relation'] = $instance['relation'];
        $query_args['tags'] = $instance['tags'];
        $query_args['posts_per_page'] = $instance['number_of_article'];
        $query_args['orderby'] = $instance['orderby'];

        echo $before_widget;

        $posts = kopa_widget_posttype_build_query( $query_args );

        if ( $posts->have_posts() ) { ?>
        <div class="loading flexslider home-slider" data-animation="<?php echo $instance['animation']; ?>" data-direction="<?php echo $instance['direction'] ?>" data-slideshow_speed="<?php echo $instance['slideshow_speed']; ?>" data-animation_speed="<?php echo $instance['animation_speed']; ?>" data-autoplay="<?php echo $instance['is_auto_play']; ?>">
            <?php if ( ! empty( $title ) ) {
                echo '<span class="home-slider-title">'.$title.'</span>';
            } ?>
            <ul class="slides">
            <?php while ( $posts->have_posts() ) { $posts->the_post(); ?>
                <li>
                    <article>
                        <a href="<?php the_permalink(); ?>">
                        <?php if ( has_post_thumbnail() ) {
                            the_post_thumbnail( 'flexslider-image-size' ); 
                        } elseif ( 'gallery' == get_post_format() ) {
                            $gallery_ids = kopa_content_get_gallery_attachment_ids( get_the_content() );
                            foreach ( $gallery_ids as $id ) {
                                if ( wp_attachment_is_image( $id ) ) {
                                    echo wp_get_attachment_image( $id, 'flexslider-image-size' );
                                    break;
                                }
                            } // endforeach
                        } // endif
                        ?>
                        </a>
                        <div class="flex-caption">
                            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <?php the_excerpt(); ?>
                        </div>
                        <!-- flex-caption -->
                    </article>
                </li>
            <?php } // endwhile ?>
            </ul>
            <!-- slides -->
        </div>
        <?php
        } // endif $posts->have_posts()

        wp_reset_postdata();

        echo $after_widget;
    }

    function form($instance) {
        $defaults = array(
            'title'             => '',
            'categories'        => array(),
            'relation'          => 'OR',
            'tags'              => array(),
            'number_of_article' => 10,
            'orderby'           => 'lastest',
            'animation'         => 'slide',
            'direction'         => 'horizontal',
            'slideshow_speed'   => '7000',
            'animation_speed'   => '600',
            'is_auto_play'      => 'true'
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = strip_tags( $instance['title'] );

        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['number_of_article'] = (int) $instance['number_of_article'];
        $form['orderby'] = $instance['orderby'];
        $form['animation'] = $instance['animation'];
        $form['direction'] = $instance['direction'];
        $form['slideshow_speed'] = (int) $instance['slideshow_speed'];
        $form['animation_speed'] = (int) $instance['animation_speed'];
        $form['is_auto_play'] = $instance['is_auto_play'];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <div class="kopa-one-half">
            <p>
                <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
                <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                    <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                    <?php
                    $categories = get_categories();
                    foreach ($categories as $category) {
                        printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, $form['categories'])) ? 'selected="selected"' : '');
                    }
                    ?>
                </select>

            </p>
            <p>
                <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation:', kopa_get_domain()); ?></label>                
                <select class="widefat" id="<?php echo $this->get_field_id('relation'); ?>" name="<?php echo $this->get_field_name('relation'); ?>" autocomplete="off">
                    <?php
                    $relation = array(
                        'AND' => __('And', kopa_get_domain()),
                        'OR' => __('Or', kopa_get_domain())
                    );
                    foreach ($relation as $value => $title) {
                        printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['relation']) ? 'selected="selected"' : '');
                    }
                    ?>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:', kopa_get_domain()); ?></label>                
                <select class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                    <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                    <?php
                    $tags = get_tags();
                    foreach ($tags as $tag) {
                        printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $tag->term_id, $tag->name, $tag->count, (in_array($tag->term_id, $form['tags'])) ? 'selected="selected"' : '');
                    }
                    ?>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('number_of_article'); ?>"><?php _e('Number of article:', kopa_get_domain()); ?></label>                
                <input class="widefat" type="number" min="2" id="<?php echo $this->get_field_id('number_of_article'); ?>" name="<?php echo $this->get_field_name('number_of_article'); ?>" value="<?php echo esc_attr( $form['number_of_article'] ); ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
                <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                    <?php
                    $orderby = array(
                        'lastest' => __('Latest', kopa_get_domain()),
                        'popular' => __('Popular by View Count', kopa_get_domain()),
                        'most_comment' => __('Popular by Comment Count', kopa_get_domain()),
                        'random' => __('Random', kopa_get_domain()),
                    );
                    foreach ($orderby as $value => $title) {
                        printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                    }
                    ?>
                </select>
            </p>
        </div>
        <div class="kopa-one-half last">
            <p>
                <label for="<?php echo $this->get_field_id('animation'); ?>"><?php _e('Animation:', kopa_get_domain()); ?></label>                
                <select class="widefat" id="<?php echo $this->get_field_id('animation'); ?>" name="<?php echo $this->get_field_name('animation'); ?>" autocomplete="off">
                    <?php
                    $animation = array(
                        'slide' => __('Slide', kopa_get_domain()),
                        'fade'  => __('Fade', kopa_get_domain()),
                    );
                    foreach ($animation as $value => $title) {
                        printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['animation']) ? 'selected="selected"' : '');
                    }
                    ?>
                </select>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('direction'); ?>"><?php _e('Direction:', kopa_get_domain()); ?></label>                
                <select class="widefat" id="<?php echo $this->get_field_id('direction'); ?>" name="<?php echo $this->get_field_name('direction'); ?>" autocomplete="off">
                    <?php
                    $direction = array(
                        'horizontal' => __('Horizontal', kopa_get_domain()),
                        'vertical'   => __('Vertical', kopa_get_domain()),
                    );
                    foreach ($direction as $value => $title) {
                        printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['direction']) ? 'selected="selected"' : '');
                    }
                    ?>
                </select>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('slideshow_speed'); ?>"><?php _e('Speed of the slideshow cycling:', kopa_get_domain()); ?></label> 
                <input class="widefat" id="<?php echo $this->get_field_id('slideshow_speed'); ?>" name="<?php echo $this->get_field_name('slideshow_speed'); ?>" type="number" value="<?php echo $form['slideshow_speed']; ?>" />
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('animation_speed'); ?>"><?php _e('Speed of animations:', kopa_get_domain()); ?></label>                
                <input class="widefat" id="<?php echo $this->get_field_id('animation_speed'); ?>" name="<?php echo $this->get_field_name('animation_speed'); ?>" type="number" value="<?php echo $form['animation_speed']; ?>" />
            </p>

            <p>
                <input class="" id="<?php echo $this->get_field_id('is_auto_play'); ?>" name="<?php echo $this->get_field_name('is_auto_play'); ?>" type="checkbox" value="true" <?php echo ('true' === $form['is_auto_play']) ? 'checked="checked"' : ''; ?> />
                <label for="<?php echo $this->get_field_id('is_auto_play'); ?>"><?php _e('Auto Play', kopa_get_domain()); ?></label>                                
            </p>
        </div>
        <div class="kopa-clear"></div>
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['relation'] = $new_instance['relation'];
        $instance['tags'] = (empty($new_instance['tags'])) ? array() : array_filter($new_instance['tags']);
        $instance['number_of_article'] = (int) $new_instance['number_of_article'];
        if ( 0 >= $instance['number_of_article'] ) {
            $instance['number_of_article'] = 10;
        }
        $instance['orderby'] = $new_instance['orderby'];
        $instance['animation'] = $new_instance['animation'];
        $instance['direction'] = $new_instance['direction'];
        $instance['slideshow_speed'] = (int) $new_instance['slideshow_speed'];
        $instance['animation_speed'] = (int) $new_instance['animation_speed'];
        $instance['is_auto_play'] = isset($new_instance['is_auto_play']) ? $new_instance['is_auto_play'] : 'false';

        return $instance;
    }
} // end Kopa_Widget_Flexslider

/**
 * Articles List Widget Class
 * @since Musica 1.0
 */
class Kopa_Widget_Articles_List extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'kopa-article-list-widget', 'description' => __('Display Latest Articles Widget', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_articles_list', __('Kopa Articles List', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $query_args['categories'] = $instance['categories'];
        $query_args['relation'] = $instance['relation'];
        $query_args['tags'] = $instance['tags'];
        $query_args['posts_per_page'] = $instance['number_of_article'];
        $query_args['orderby'] = $instance['orderby'];

        echo $before_widget;

        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }

        $posts = kopa_widget_posttype_build_query( $query_args );
        ?>

        <?php if ( $posts->have_posts() ) { ?>
            <?php $post_index = 1; ?>
            <ul class="clearfix">
                <li>
            
            <?php while ( $posts->have_posts() ) { 
                $posts->the_post();

                $terms = get_the_terms( get_the_ID(), 'category' );
                $first_term = array_shift( $terms );
                ?>
                <?php if ( 1 == $post_index ) { ?>

                    <article class="entry-item clearfix">
                        <div class="entry-thumb">
                            <?php if ( 'gallery' == get_post_format() ) { ?>
                                <?php $gallery_ids = kopa_content_get_gallery_attachment_ids( get_the_content() ); ?>
                                <?php if ( ! empty( $gallery_ids ) ) { ?>
                                    <div class="small-loading flexslider kp-post-slider">
                                        <ul class="slides">
                                        <?php foreach ( $gallery_ids as $attachment_id ) { ?>
                                            <li>
                                                <?php echo wp_get_attachment_image( $attachment_id, 'article-list-image-size' ); ?>
                                            </li>
                                        <?php } ?>
                                        </ul>
                                    </div>
                                    <!-- kp-post-slider -->
                                <?php } ?>
                            <?php } elseif ( 'video' == get_post_format() ) { ?> 
                                <?php the_post_thumbnail( 'article-list-image-size' ); ?> 
                                <?php 
                                $video = kopa_content_get_video( get_the_content() ); 
                                if ( isset( $video[0] ) ) {
                                    $video = $video[0];
                                } else {
                                    $video = '';
                                }

                                if ( isset( $video['url'] ) ) { ?>
                                    <div class="mask"><a rel="prettyPhoto" href="<?php echo $video['url']; ?>" data-icon="&#xe163;"></a></div>
                                <?php } ?>
                            <?php } else { ?>
                                <?php if ( has_post_thumbnail() ) { ?>
                                    <?php the_post_thumbnail( 'article-list-image-size' ); ?> 
                                    <div class="mask"><a href="<?php the_permalink(); ?>" data-icon="&#xe125;"></a></div>
                                <?php } ?>
                            <?php } // endif ?>
                            
                        </div>
                        <!-- entry-thumb -->
                        <div class="entry-content">
                            <header>
                                <h5 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                                <span class="entry-date">&mdash; <?php the_time( get_option( 'date_format' ) ); ?></span>
                            </header>
                            <?php the_excerpt(); ?>
                        </div>
                        <!-- entry-content -->
                    </article>
                    <!-- entry-item -->

                    <?php if ( $posts->post_count > 1 ) {
                        echo '<ul class="older-post clearfix">';
                    } ?>

                <?php } else { ?>

                    <li class="<?php echo ( $post_index % 2 == 0 ) ? 'odd' : 'even'; ?>">
                        <article class="entry-item clearfix">
                            <div class="entry-content">
                                <h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                <span class="entry-date"><span class="kopa-minus"></span>&mdash; <?php the_time( get_option( 'date_format' ) ); ?></span>
                                <?php the_excerpt(); ?>
                            </div>
                        </article>
                    </li>
                
                    <?php if ( $post_index == $posts->post_count ) {
                        echo '</ul>';
                    } ?>

                <?php } // endif ?>

                <?php $post_index++; ?>

            <?php } // endwhile ?>

                </li>

            </ul>

        <?php } // endif ?>

        <?php
        wp_reset_postdata();

        echo $after_widget;
    }

    function form($instance) {
        $defaults = array(
            'title'             => '',
            'categories'        => array(),
            'relation'          => 'OR',
            'tags'              => array(),
            'number_of_article' => 4,
            'orderby'           => 'lastest',
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = strip_tags( $instance['title'] );

        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['number_of_article'] = $instance['number_of_article'];
        $form['orderby'] = $instance['orderby'];

        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $categories = get_categories();
                foreach ($categories as $category) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, $form['categories'])) ? 'selected="selected"' : '');
                }
                ?>
            </select>

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('relation'); ?>" name="<?php echo $this->get_field_name('relation'); ?>" autocomplete="off">
                <?php
                $relation = array(
                    'AND' => __('And', kopa_get_domain()),
                    'OR' => __('Or', kopa_get_domain())
                );
                foreach ($relation as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['relation']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $tags = get_tags();
                foreach ($tags as $tag) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $tag->term_id, $tag->name, $tag->count, (in_array($tag->term_id, $form['tags'])) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number_of_article'); ?>"><?php _e('Number of article:', kopa_get_domain()); ?></label>                
            <input class="widefat" type="number" min="1" id="<?php echo $this->get_field_id('number_of_article'); ?>" name="<?php echo $this->get_field_name('number_of_article'); ?>" value="<?php echo esc_attr( $form['number_of_article'] ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                <?php
                $orderby = array(
                    'lastest' => __('Latest', kopa_get_domain()),
                    'popular' => __('Popular by View Count', kopa_get_domain()),
                    'most_comment' => __('Popular by Comment Count', kopa_get_domain()),
                    'random' => __('Random', kopa_get_domain()),
                );
                foreach ($orderby as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['relation'] = $new_instance['relation'];
        $instance['tags'] = (empty($new_instance['tags'])) ? array() : array_filter($new_instance['tags']);
        $instance['number_of_article'] = (int) $new_instance['number_of_article'];
        // validate number of article
        if ( 0 >= $instance['number_of_article'] ) {
            $instance['number_of_article'] = 4;
        }
        $instance['orderby'] = $new_instance['orderby'];

        return $instance;
    }
}

/**
 * Articles Carousel Widget Class
 * @since Musica 1.0
 */
class Kopa_Widget_Articles_Carousel extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'kopa-related-post-widget', 'description' => __('Display Articles Carousel Widget', kopa_get_domain()));
        $control_ops = array('width' => '500', 'height' => 'auto');
        parent::__construct('kopa_widget_articles_carousel', __('Kopa Articles Carousel', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $query_args['categories'] = $instance['categories'];
        $query_args['relation'] = $instance['relation'];
        $query_args['tags'] = $instance['tags'];
        $query_args['posts_per_page'] = $instance['number_of_article'];
        $query_args['orderby'] = $instance['orderby'];

        echo $before_widget;
        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }
        
        $posts = kopa_widget_posttype_build_query( $query_args );

        if ( $posts->have_posts() ) { ?>
            <div class="list-carousel responsive" >
                <ul class="kopa-related-post-carousel" data-pagination-id="#<?php echo $this->get_field_id('pager2'); ?>" data-scroll-items="<?php echo $instance['scroll_items']; ?>" data-columns="<?php echo $instance['columns']; ?>" data-autoplay="<?php echo $instance['autoplay']; ?>" data-duration="<?php echo $instance['duration']; ?>" data-timeout-duration="<?php echo $instance['timeout_duration']; ?>">
                <?php while ( $posts->have_posts() ) { $posts->the_post(); 
                    $terms = get_the_terms( get_the_ID(), 'category' );
                    $first_term = array_shift( $terms );
                    if ( 'video' == get_post_format() ) {
                        $data_icon = '&#xe163;';
                    } else {
                        $data_icon = '&#xe125;';
                    }
                    ?>
                    <li>
                        <article class="entry-item clearfix">
                            <div class="entry-thumb">
                            <?php if ( 'video' == get_post_format() ) { ?>
                            
                                <?php 
                                $video = kopa_content_get_video( get_the_content() ); 
                                if ( isset( $video[0] ) ) {
                                    $video = $video[0];
                                } else {
                                    $video = '';
                                }
                                ?>

                                <?php if ( has_post_thumbnail() ) {
                                    the_post_thumbnail( 'article-list-image-size' );
                                } elseif ( isset( $video['type'] ) && isset( $video['url'] ) ) { ?>
                                    <img src="<?php echo kopa_get_video_thumbnails_url( $video['type'], $video['url'] ); ?>" alt="<?php the_title(); ?>">
                                <?php } ?> 

                                <?php if ( isset( $video['url'] ) ) { ?>
                                    <div class="mask"><a href="<?php echo $video['url']; ?>" data-icon="<?php echo $data_icon; ?>" rel="prettyPhoto"></a></div>
                                <?php } ?>  

                            <?php } elseif ( has_post_thumbnail() ) { ?>

                                <?php the_post_thumbnail( 'article-list-image-size' ); ?>
                                <div class="mask"><a href="<?php the_permalink(); ?>" data-icon="<?php echo $data_icon; ?>"></a></div>

                            <?php } elseif ( 'gallery' == get_post_format() ) { ?>

                                <?php 
                                $gallery_ids = kopa_content_get_gallery_attachment_ids( get_the_content() ); 
                                if ( ! empty( $gallery_ids ) ) {
                                    foreach ( $gallery_ids as $id ) {
                                        if ( wp_attachment_is_image( $id ) ) {
                                            echo wp_get_attachment_image( $id, 'article-list-image-size' ); 
                                            ?>
                                            <div class="mask"><a href="<?php the_permalink(); ?>" data-icon="<?php echo $data_icon; ?>"></a></div>
                                        <?php
                                            break;
                                        } // endif
                                    } // endforeach
                                } // endif ?>
                            <?php } // endif ?>
                            </div>
                            <!-- entry-thumb -->
                            <div class="entry-content">
                                <header>
                                    <h5 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                                    <span class="entry-date">&mdash; <?php the_time( get_option( 'date_format' ) ); ?></span>
                                </header>
                                <?php the_excerpt(); ?>
                            </div>
                            <!-- entry-content -->
                        </article>
                    </li>
                <?php } // endwhile ?>  
                </ul><!--kopa-featured-news-carousel-->
                <div class="clearfix"></div>
                <div id="<?php echo $this->get_field_id( 'pager2' ); ?>" class="carousel-pager clearfix"></div>
            </div><!--list-carousel-->
            <?php
        } // endif $posts->have_posts()

        wp_reset_postdata();
        
        echo $after_widget;
    }

    function form($instance) {
        $defaults = array(
            'title'             => __( 'News', kopa_get_domain() ),
            'categories'        => array(),
            'relation'          => 'OR',
            'tags'              => array(),
            'number_of_article' => 8,
            'orderby'           => 'lastest',
            'scroll_items'      => 1,
            'columns'           => 5,
            'autoplay'          => 'false',
            'duration'          => 500,
            'timeout_duration'  => 2500,
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = strip_tags( $instance['title'] );

        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['number_of_article'] = (int) $instance['number_of_article'];
        $form['orderby'] = $instance['orderby'];
        $form['scroll_items'] = $instance['scroll_items'];
        $form['columns'] = $instance['columns'];
        $form['autoplay'] = $instance['autoplay'];
        $form['duration'] = $instance['duration'];
        $form['timeout_duration'] = $instance['timeout_duration'];
        ?>
        <div class="kopa-one-half">
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $categories = get_categories();
                foreach ($categories as $category) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, $form['categories'])) ? 'selected="selected"' : '');
                }
                ?>
            </select>

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('relation'); ?>" name="<?php echo $this->get_field_name('relation'); ?>" autocomplete="off">
                <?php
                $relation = array(
                    'AND' => __('And', kopa_get_domain()),
                    'OR' => __('Or', kopa_get_domain())
                );
                foreach ($relation as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['relation']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $tags = get_tags();
                foreach ($tags as $tag) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $tag->term_id, $tag->name, $tag->count, (in_array($tag->term_id, $form['tags'])) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number_of_article'); ?>"><?php _e('Number of article:', kopa_get_domain()); ?></label>                
            <input class="widefat" type="number" min="2" id="<?php echo $this->get_field_id('number_of_article'); ?>" name="<?php echo $this->get_field_name('number_of_article'); ?>" value="<?php echo esc_attr( $form['number_of_article'] ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                <?php
                $orderby = array(
                    'lastest' => __('Latest', kopa_get_domain()),
                    'popular' => __('Popular by View Count', kopa_get_domain()),
                    'most_comment' => __('Popular by Comment Count', kopa_get_domain()),
                    'random' => __('Random', kopa_get_domain())
                );
                foreach ($orderby as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        </div>

        <div class="kopa-one-half last">
        <p>
            <label for="<?php echo $this->get_field_id('scroll_items'); ?>"><?php _e('Scroll Items:', kopa_get_domain()); ?></label>                
            <input class="widefat" type="number" min="1" id="<?php echo $this->get_field_id('scroll_items'); ?>" name="<?php echo $this->get_field_name('scroll_items'); ?>" value="<?php echo esc_attr( $form['scroll_items'] ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('columns'); ?>"><?php _e('Number of Columns:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id( 'columns' ); ?>" name="<?php echo $this->get_field_name( 'columns' ); ?>">
                <?php $columns = array( 3, 4, 5 );
                foreach ( $columns as $value ) { ?>
                    <option value="<?php echo $value; ?>" <?php selected( $form['columns'], $value ); ?>><?php echo $value; ?></option>
                <?php } ?>
            </select>
        </p>
        <p>
            <input class="" id="<?php echo $this->get_field_id('autoplay'); ?>" name="<?php echo $this->get_field_name('autoplay'); ?>" type="checkbox" value="true" <?php checked( $form['autoplay'], 'true' ); ?>>
            <label for="<?php echo $this->get_field_id('autoplay'); ?>"><?php _e('Auto Play', kopa_get_domain()); ?></label>                                
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('duration'); ?>"><?php _e('Duration of the transition (milliseconds):', kopa_get_domain()); ?></label>                
            <input class="widefat" type="number" min="100" step="100" id="<?php echo $this->get_field_id('duration'); ?>" name="<?php echo $this->get_field_name('duration'); ?>" value="<?php echo esc_attr( $form['duration'] ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('timeout_duration'); ?>"><?php _e('The amount of milliseconds the carousel will pause:', kopa_get_domain()); ?></label>                
            <input class="widefat" type="number" min="100" step="100" id="<?php echo $this->get_field_id('timeout_duration'); ?>" name="<?php echo $this->get_field_name('timeout_duration'); ?>" value="<?php echo esc_attr( $form['timeout_duration'] ); ?>">
        </p>
        </div>
        <div class="kopa-clear"></div>
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['relation'] = $new_instance['relation'];
        $instance['tags'] = (empty($new_instance['tags'])) ? array() : array_filter($new_instance['tags']);
        $instance['number_of_article'] = (int) $new_instance['number_of_article'];
        if ( 0 >= $instance['number_of_article'] ) {
            $instance['number_of_article'] = 8;
        }
        $instance['orderby'] = $new_instance['orderby'];
        $instance['scroll_items'] = (int) $new_instance['scroll_items'];
        if ( 0 >= $instance['scroll_items'] ) {
            $instance['scroll_items'] = 1;
        }

        $instance['columns'] = $new_instance['columns'];

        $instance['autoplay'] = isset( $new_instance['autoplay'] ) ? $new_instance['autoplay'] : 'false';

        $instance['duration'] = (int) $new_instance['duration'] ? (int) $new_instance['duration'] : 500;
        $instance['timeout_duration'] = (int) $new_instance['timeout_duration'] ? (int) $new_instance['timeout_duration'] : 2500;

        return $instance;
    }
}

/**
 * Entry List Widget Class
 * @since Musica 1.0
 */
class Kopa_Widget_Entry_List extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'kp-video-widget', 'description' => __('Display Featured Articles Widget', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_entry_list', __('Kopa Entry List', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $query_args['categories'] = $instance['categories'];
        $query_args['relation'] = $instance['relation'];
        $query_args['tags'] = $instance['tags'];
        $query_args['posts_per_page'] = $instance['number_of_article'];
        $query_args['orderby'] = $instance['orderby'];

        echo $before_widget;

        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }

        $posts = kopa_widget_posttype_build_query( $query_args );
        ?>

        <?php if ( $posts->have_posts() ) { ?>
            <?php $post_index = 1; ?>
            <ul class="clearfix">
            
            <?php while ( $posts->have_posts() ) { 
                $posts->the_post();

                $terms = get_the_terms( get_the_ID(), 'category' );
                $first_term = array_shift( $terms );
            ?>
                <li>
                    <article class="entry-item clearfix">
                        <div class="entry-thumb">
                        <?php if ( 'gallery' == get_post_format() ) { ?>
                            <?php $gallery_ids = kopa_content_get_gallery_attachment_ids( get_the_content() ); ?>
                            <?php if ( ! empty( $gallery_ids ) ) { ?>
                                <div class="small-loading flexslider kp-post-slider">
                                    <ul class="slides">
                                    <?php foreach ( $gallery_ids as $attachment_id ) { ?>
                                        <li>
                                            <?php echo wp_get_attachment_image( $attachment_id, 'article-list-image-size' ); ?>
                                        </li>
                                    <?php } ?>
                                    </ul>
                                </div>
                                <!-- kp-post-slider -->
                            <?php } elseif( has_post_thumbnail() ) { ?>
                                <?php the_post_thumbnail( 'article-list-image-size' ); ?> 
                                <div class="mask"><a href="<?php the_permalink(); ?>" data-icon="&#xe125;"></a></div>
                            <?php } ?>
                        <?php } elseif ( 'video' == get_post_format() ) { ?> 
                            <?php 
                            $video = kopa_content_get_video( get_the_content() ); 
                            if ( isset( $video[0] ) ) {
                                $video = $video[0];
                            } else {
                                $video = '';
                            } 
                            ?>

                            <?php if ( isset( $video['url'] ) ) { ?>
                            <a rel="prettyPhoto" href="<?php echo esc_url( $video['url'] ); ?>">
                            <?php } else { ?>
                            <a href="<?php the_permalink(); ?>">
                            <?php } ?>
                                <?php if ( has_post_thumbnail() ) {
                                    the_post_thumbnail( 'article-list-image-size' ); 
                                } elseif( isset( $video['type'] ) && isset( $video['type'] ) ) { ?>
                                    <img src="<?php echo kopa_get_video_thumbnails_url( $video['type'], $video['url'] ); ?>" alt="<?php the_title(); ?>">
                                <?php } ?>
                            </a>

                            <?php if ( isset( $video['url'] ) ) { ?>
                            <a rel="prettyPhoto" href="<?php echo esc_url( $video['url'] ); ?>" class="play-icon"></a>
                            <?php } ?>
                        <?php } else { ?>
                            <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail( 'article-list-image-size' ); ?> 
                            </a>
                        <?php } // endif ?>
                        </div>
                        <!-- entry-thumb -->
                        <div class="entry-content">
                            <header>
                                <h5 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                                <span class="entry-date">&mdash; <?php the_time( get_option( 'date_format' ) ); ?></span>
                            </header>
                        </div>
                        <!-- entry-content -->
                    </article>
                    <!-- entry-item -->
                </li>

            <?php } // endwhile ?>

            </ul>
        <?php } // endif ?>

        <?php
        wp_reset_postdata();

        echo $after_widget;
    }

    function form($instance) {
        $defaults = array(
            'title'             => '',
            'categories'        => array(),
            'relation'          => 'OR',
            'tags'              => array(),
            'number_of_article' => 3,
            'orderby'           => 'lastest',
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = strip_tags( $instance['title'] );

        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['number_of_article'] = $instance['number_of_article'];
        $form['orderby'] = $instance['orderby'];

        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $categories = get_categories();
                foreach ($categories as $category) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, $form['categories'])) ? 'selected="selected"' : '');
                }
                ?>
            </select>

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('relation'); ?>" name="<?php echo $this->get_field_name('relation'); ?>" autocomplete="off">
                <?php
                $relation = array(
                    'AND' => __('And', kopa_get_domain()),
                    'OR' => __('Or', kopa_get_domain())
                );
                foreach ($relation as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['relation']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $tags = get_tags();
                foreach ($tags as $tag) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $tag->term_id, $tag->name, $tag->count, (in_array($tag->term_id, $form['tags'])) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number_of_article'); ?>"><?php _e('Number of article:', kopa_get_domain()); ?></label>                
            <input class="widefat" type="number" min="1" id="<?php echo $this->get_field_id('number_of_article'); ?>" name="<?php echo $this->get_field_name('number_of_article'); ?>" value="<?php echo esc_attr( $form['number_of_article'] ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                <?php
                $orderby = array(
                    'lastest' => __('Latest', kopa_get_domain()),
                    'popular' => __('Popular by View Count', kopa_get_domain()),
                    'most_comment' => __('Popular by Comment Count', kopa_get_domain()),
                    'random' => __('Random', kopa_get_domain()),
                );
                foreach ($orderby as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['relation'] = $new_instance['relation'];
        $instance['tags'] = (empty($new_instance['tags'])) ? array() : array_filter($new_instance['tags']);
        $instance['number_of_article'] = (int) $new_instance['number_of_article'];
        // validate number of article
        if ( 0 >= $instance['number_of_article'] ) {
            $instance['number_of_article'] = 3;
        }
        $instance['orderby'] = $new_instance['orderby'];

        return $instance;
    }
}

/**
 * Twitter Widget Class
 * @since News Mix 1.0
 */
class Kopa_Widget_Twitter extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'kopa-twitter-widget', 'description' => __('Display your latest twitter status', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_twitter', __('Kopa Twitter', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $number_of_tweets = $instance['number_of_tweets'];

        echo $before_widget;

        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }

        if ( ! empty ( $instance['twitter_username'] ) ) {
        ?>
            <div class="kp-tweets-container clearfix" data-username="<?php echo esc_attr( $instance['twitter_username'] ); ?>" data-limit="<?php echo esc_attr( $number_of_tweets ); ?>"></div>
        <?php 
        }

        echo $after_widget;
    }

    function form( $instance ) {
        $defaults = array(
            'title' => '',
            'twitter_username' => 'kopasoft',
            'number_of_tweets' => 2,
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = strip_tags( $instance['title'] );
        $form['twitter_username'] = $instance['twitter_username'];
        $form['number_of_tweets'] = $instance['number_of_tweets'];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('twitter_username'); ?>"><?php _e('Twitter Username:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('twitter_username'); ?>" name="<?php echo $this->get_field_name('twitter_username'); ?>" type="text" value="<?php echo esc_attr($form['twitter_username']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number_of_tweets'); ?>"><?php _e('Number of tweets:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('number_of_tweets'); ?>" name="<?php echo $this->get_field_name('number_of_tweets'); ?>" type="number" min="1" value="<?php echo esc_attr($form['number_of_tweets']); ?>" />
        </p>

        <?php
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['twitter_username'] = strip_tags( $new_instance['twitter_username'] );
        $instance['number_of_tweets'] = (int) $new_instance['number_of_tweets'];

        if ( 0 >= $instance['number_of_tweets'] ) {
            $instance['number_of_tweets'] = 2;
        }

        return $instance;
    }
}

/**
 * Advertising Widget Class
 * @since Musica 1.0
 */
class Kopa_Widget_Advertising extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'kopa-adv-widget', 'description' => __('Display one advertising image', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_advertising', __('Kopa Advertising', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $image_src = $instance['image_src'];
        $image_url = $instance['image_url'];

        echo $before_widget;

        if ( ! empty( $title ) ) {
           echo $before_title . $title . $after_title;
        }
        ?>

            <?php if ( $image_url ) { ?>
                <a href="<?php echo esc_url($image_url) ?>"><img src="<?php echo esc_url($image_src); ?>" alt=""></a>
            <?php } else { ?>
                <img src="<?php echo esc_url($image_src); ?>" alt="<?php echo $title; ?>">
            <?php } ?>

        <?php
        
        echo $after_widget;
    }

    function form($instance) {
        $defaults = array(
            'title'     => '',
            'image_src' => '',
            'image_url' => ''
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = strip_tags( $instance['title'] );
        $form['image_src'] = $instance['image_src'];
        $form['image_url'] = $instance['image_url'];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('image_src'); ?>"><?php _e('Image Source:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('image_src'); ?>" name="<?php echo $this->get_field_name('image_src'); ?>" type="text" value="<?php echo esc_attr($form['image_src']); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('image_url'); ?>"><?php _e('Url:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('image_url'); ?>" name="<?php echo $this->get_field_name('image_url'); ?>" type="text" value="<?php echo esc_attr($form['image_url']); ?>">
        </p>
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['image_src'] = $new_instance['image_src'];
        $instance['image_url'] = $new_instance['image_url'];

        return $instance;
    }
}

/**
 * Kopa Gallery Widget Class
 * @since Musica 1.0
 */
class Kopa_Widget_Gallery extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'kopa-gallery-widget', 'description' => __('Display latest gallery of selected categories', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_gallery', __('Kopa Gallery', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

        echo $before_widget;

        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }

        $tax_query[] = array(
            'taxonomy' => 'post_format',
            'field'    => 'slug',
            'terms'    => 'post-format-gallery'
        );

        if ( ! empty( $instance['categories'] ) ) {
            $tax_query[] = array(
                'taxonomy' => 'category',
                'field' => 'id',
                'terms' => $instance['categories']
            );
        }

        if ( count( $tax_query ) >= 2 ) {
            $tax_query['relation'] = 'AND';
        }

        $gallery_args['tax_query'] = $tax_query;
        $gallery_args['posts_per_page'] = 1;

        $gallery_post = new WP_Query( $gallery_args );

        if ( $gallery_post->have_posts() ) {
            while ( $gallery_post->have_posts() ) {
                $gallery_post->the_post();
                $gallery_post_id = get_the_ID();
            }
        }

        wp_reset_postdata();

        $gallery_post = get_post( $gallery_post_id );

        $gallery_ids = kopa_content_get_gallery_attachment_ids( $gallery_post->post_content );

        if ( ! empty( $gallery_ids ) ) { 
        ?>

        <div class="gallery-slideshow clearfix">
                
            <div id="<?php echo $this->get_field_id( 'exposure' ); ?>"></div> 
            <div class="panel clearfix">
                <ul class="kopa-images-slideshow" data-controls-id="#<?php echo $this->get_field_id( 'controls' ); ?>" data-exposure-id="#<?php echo $this->get_field_id( 'exposure' ); ?>">
                <?php foreach ( $gallery_ids as $id ) { ?> 
                    <?php if ( wp_attachment_is_image( $id )  ) { 
                        $large_image = wp_get_attachment_image_src( $id, 'exposure-large-image-size' );
                        ?>
                        <li><a href="<?php echo $large_image[0]; ?>"><?php echo wp_get_attachment_image( $id, 'exposure-thumb-image-size' ); ?></a></li>
                    <?php } // endif ?>
                <?php }  // endforeach ?>
                </ul>
                <div id="<?php echo $this->get_field_id( 'controls' ); ?>"></div>
            </div>          
            <div class="clear"></div>
        </div>

        <?php
        } // endif 
        echo $after_widget;
    }

    function form($instance) {
        $defaults = array(
            'title'        => '',
            'categories'   => array(),
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = strip_tags( $instance['title'] );
        $form['categories'] = $instance['categories'];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $categories = get_categories();
                foreach ($categories as $category) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, $form['categories'])) ? 'selected="selected"' : '');
                }
                ?>
            </select>

        </p>
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);

        return $instance;
    }
}

/**
 * Articles Tabs List Widget Class
 * @since News Mix 1.0
 */
class Kopa_Widget_Articles_Tabs_List extends WP_Widget {
    
    function __construct() {
        $widget_ops = array('classname' => 'kopa-entry-list-widget', 'description' => __('Display tabs of posts for each selected categories', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_articles_tabs_list', __('Kopa Articles Tabs List', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $query_args['categories'] = $instance['categories'];
        $query_args['posts_per_page'] = $instance['number_of_article'];
        $query_args['orderby'] = $instance['orderby'];
        $categories = get_terms( 'category' );
            
        echo $before_widget;
        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }

        $posts = kopa_widget_posttype_build_query( $query_args );

        if ( ! empty( $instance['categories'] ) && $posts->have_posts() ) { ?>

            <div class="list-container-1">
                <ul class="tabs-1 clearfix">
                    <?php 
                    $cat_index = 1;
                    foreach ( $categories as $category ) {
                        if ( in_array($category->term_id, $instance['categories']) ) { ?>
                        <li <?php echo ( $cat_index == 1 ? ' class="active"' : '' ); ?>><a href="#<?php echo $this->get_field_id( 'tab' ) . '-' . $category->term_id; ?>"><?php echo $category->name; ?></a></li>
                    <?php }
                        $cat_index++; // increase category index by 1
                    } ?>
                </ul><!--tabs-1-->
            </div>
            <div class="tab-container-1">
                <?php foreach ( $instance['categories'] as $cat_ID ) {
                    $cat_posts = new WP_Query( array(
                        'cat' => $cat_ID,
                        'posts_per_page' => $instance['number_of_article']
                    ) );

                    if ( $cat_posts->have_posts() ) {
                    ?>
                    <div class="tab-content-1 kp-post-format" id="<?php echo $this->get_field_id('tab') . '-' . $cat_ID; ?>">                        
                        <ul>
                            <?php $post_index = 1; 
                            while ( $cat_posts->have_posts() ) { 
                                $cat_posts->the_post(); 

                                $thumbnail = wp_get_attachment_image( get_post_thumbnail_id(), 'thumbnail' );

                                if ( $post_index == 1 )
                                    $index_class = 'kp-1st-post';
                                elseif ( $post_index == 2 )
                                    $index_class = 'kp-2nd-post';
                                elseif ( $post_index == 3 )
                                    $index_class = 'kp-3rd-post';
                                else
                                    $index_class = 'kp-nth-post';
                                ?>
                                <li>
                                    <article class="entry-item clearfix">
                                        
                                        <span class="entry-thumb"><a href="<?php the_permalink(); ?>"><?php if ( has_post_thumbnail() )
                                                echo $thumbnail; // 53 x 53
                                        ?></a></span>

                                        <div class="entry-content clearfix">
                                            <h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                            <span class="entry-date"><?php the_time( get_option( 'date_format' ) ); ?></span>

                                            <?php if ( 'video' == get_post_format() ) { ?>
                                                <span class="video-icon" data-icon="&#xe07e;"></span>
                                            <?php } ?>
                                        </div>

                                    </article>
                                </li>
                            <?php $post_index++; // increase post index by 1 
                            } // endwhile ?>
                        </ul>

                    </div><!--tab-content-1-->
                    <?php } // endif
                    wp_reset_postdata();
                } // endforeach ?>
            </div><!--tab-container-1-->
            
            <?php
        } // endif $posts->have_posts()

        wp_reset_postdata();

        echo $after_widget;
    }

    function form($instance) {
        $defaults = array(
            'title' => __( 'Trending Now', kopa_get_domain() ),
            'categories' => array(),
            'number_of_article' => 5,
            'orderby' => 'lastest',
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = strip_tags( $instance['title'] );

        $form['categories'] = $instance['categories'];
        $form['number_of_article'] = (int) $instance['number_of_article'];
        $form['orderby'] = $instance['orderby'];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $categories = get_categories();
                foreach ($categories as $category) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, $form['categories'])) ? 'selected="selected"' : '');
                }
                ?>
            </select>

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number_of_article'); ?>"><?php _e('Number of articles on each tab:', kopa_get_domain()); ?></label>                
            <input class="widefat" type="number" min="2" id="<?php echo $this->get_field_id('number_of_article'); ?>" name="<?php echo $this->get_field_name('number_of_article'); ?>" value="<?php echo esc_attr( $form['number_of_article'] ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                <?php
                $orderby = array(
                    'lastest' => __('Latest', kopa_get_domain()),
                    'popular' => __('Popular by View Count', kopa_get_domain()),
                    'most_comment' => __('Popular by Comment Count', kopa_get_domain()),
                    'random' => __('Random', kopa_get_domain())
                );
                foreach ($orderby as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['number_of_article'] = (int) $new_instance['number_of_article'];
        $instance['orderby'] = $new_instance['orderby'];

        if ( $instance['number_of_article'] <= 0 ) {
            $instance['number_of_article'] = 5;
        }

        return $instance;
    }

}

/**
 * Audio Post Format Widget Class
 * @since News Mix 1.0
 */
class Kopa_Widget_Audio extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'kopa-audio-widget', 'description' => __('Display audio format posts', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_audio', __('Kopa Audios', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $query_args['categories'] = $instance['categories'];
        $query_args['posts_per_page'] = $instance['number_of_article'];
        $query_args['orderby'] = $instance['orderby'];

        echo $before_widget;

        if ( ! empty( $title ) )
           echo $before_title . $title . $after_title;

        // build query arguments array
        $audio_args['posts_per_page'] = $query_args['posts_per_page'];

        switch ( $query_args['orderby'] ) {
            case 'popular':
                $audio_args['meta_key'] = 'kopa_' . kopa_get_domain() . '_total_view';
                $audio_args['orderby'] = 'meta_value_num';
                break;
            case 'most_comment':
                $audio_args['orderby'] = 'comment_count';
                break;
            case 'random':
                $audio_args['orderby'] = 'rand';
                break;
            default:
                $audio_args['orderby'] = 'date';
                break;
        }   

        $tax_query[] = array(
            'taxonomy' => 'post_format',
            'field'    => 'slug',
            'terms'    => 'post-format-audio'
        );

        if ( ! empty( $query_args['categories'] ) ) {
            $tax_query[] = array(
                'taxonomy' => 'category',
                'field' => 'id',
                'terms' => $query_args['categories']
            );
        }

        if ( count( $tax_query ) >= 2 )
            $tax_query['relation'] = 'AND';

        $audio_args['tax_query'] = $tax_query;

        $audio_posts = new WP_Query( $audio_args );

        if ( $audio_posts->have_posts() ) : 
            $audio_index = 1;
            while ( $audio_posts->have_posts() ) : $audio_posts->the_post(); 
                $audio = kopa_content_get_audio( get_the_content() );

                if ( $audio_index == 1 ) :
                ?>
                    <article class="entry-item">
                        <h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                        <div class="entry-thumb">
                            <?php if ( isset( $audio[0] ) ) {
                                $audio = $audio[0];

                                if ( isset( $audio['shortcode'] ) ) {
                                    echo do_shortcode( $audio['shortcode'] );
                                }
                            }?>
                        </div>
                    </article><!--entry-item-->
                <?php 
                    echo ( $audio_posts->post_count > 1 ) ? '<ul class="older-post">' : '';
                else : // if $audio_index != 1
                ?>

                    <li class="clearfix">
                        <span data-icon="&#xe050;"></span>
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </li>

                <?php
                endif; // endif $audio_index == 1

                $audio_index++; // increase audio index by 1
            endwhile;

                echo ( $audio_posts->post_count > 1 ) ? '</ul>' : '';
        endif; // endif $audio_posts->have_posts()

        wp_reset_postdata();
        
        echo $after_widget;
    }

    function form($instance) {
        $defaults = array(
            'title' => __( 'Audio', kopa_get_domain() ),
            'categories' => array(),
            'number_of_article' => 4,
            'orderby' => 'lastest'
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = strip_tags( $instance['title'] );

        $form['categories'] = $instance['categories'];
        $form['number_of_article'] = (int) $instance['number_of_article'];
        $form['orderby'] = $instance['orderby'];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $categories = get_categories();
                foreach ($categories as $category) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, $form['categories'])) ? 'selected="selected"' : '');
                }
                ?>
            </select>

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number_of_article'); ?>"><?php _e('Number of articles:', kopa_get_domain()); ?></label>                
            <input class="widefat" type="number" min="2" id="<?php echo $this->get_field_id('number_of_article'); ?>" name="<?php echo $this->get_field_name('number_of_article'); ?>" value="<?php echo esc_attr( $form['number_of_article'] ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                <?php
                $orderby = array(
                    'lastest' => __('Latest', kopa_get_domain()),
                    'popular' => __('Popular by View Count', kopa_get_domain()),
                    'most_comment' => __('Popular by Comment Count', kopa_get_domain()),
                    'random' => __('Random', kopa_get_domain()),
                );
                foreach ($orderby as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['number_of_article'] = ( (int) $new_instance['number_of_article'] ) > 0 ? (int) $new_instance['number_of_article'] : 4;
        $instance['orderby'] = $new_instance['orderby'];

        return $instance;
    }
}

/**
 * Articles List Widget Class
 * @since Musica 1.0
 */
class Kopa_Widget_Articles_List_2 extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'kopa-most-review-widget', 'description' => __('Display Latest Articles Widget', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_articles_list_2', __('Kopa Articles List 2', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $query_args['categories'] = $instance['categories'];
        $query_args['relation'] = $instance['relation'];
        $query_args['tags'] = $instance['tags'];
        $query_args['posts_per_page'] = $instance['number_of_article'];
        $query_args['orderby'] = $instance['orderby'];

        echo $before_widget;

        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }

        $posts = kopa_widget_posttype_build_query( $query_args );
        ?>

        <?php if ( $posts->have_posts() ) { ?>
            <?php $post_index = 1; ?>
            <ul class="clearfix">
            
            <?php while ( $posts->have_posts() ) { 
                $posts->the_post();

                $terms = get_the_terms( get_the_ID(), 'category' );
                $first_term = array_shift( $terms );
            ?>
                <li>
                    <article class="entry-item clearfix">
                        
                        <?php if ( has_post_thumbnail() ) { ?>
                            <div class="entry-thumb">
                                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'thumbnail' ); ?></a>
                            </div>
                        <?php } // endif ?>
                        
                        <div class="entry-content">
                            <h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                            <span class="entry-date"><span class="kopa-minus"></span>&mdash; <?php the_time( get_option( 'date_format' ) ); ?></span>
                        </div>
                    </article>
                </li>

            <?php } // endwhile ?>

            </ul>
        <?php } // endif ?>

        <?php
        wp_reset_postdata();

        echo $after_widget;
    }

    function form($instance) {
        $defaults = array(
            'title'             => '',
            'categories'        => array(),
            'relation'          => 'OR',
            'tags'              => array(),
            'number_of_article' => 3,
            'orderby'           => 'lastest',
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = strip_tags( $instance['title'] );

        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['number_of_article'] = $instance['number_of_article'];
        $form['orderby'] = $instance['orderby'];

        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $categories = get_categories();
                foreach ($categories as $category) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, $form['categories'])) ? 'selected="selected"' : '');
                }
                ?>
            </select>

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('relation'); ?>" name="<?php echo $this->get_field_name('relation'); ?>" autocomplete="off">
                <?php
                $relation = array(
                    'AND' => __('And', kopa_get_domain()),
                    'OR' => __('Or', kopa_get_domain())
                );
                foreach ($relation as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['relation']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $tags = get_tags();
                foreach ($tags as $tag) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $tag->term_id, $tag->name, $tag->count, (in_array($tag->term_id, $form['tags'])) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number_of_article'); ?>"><?php _e('Number of article:', kopa_get_domain()); ?></label>                
            <input class="widefat" type="number" min="1" id="<?php echo $this->get_field_id('number_of_article'); ?>" name="<?php echo $this->get_field_name('number_of_article'); ?>" value="<?php echo esc_attr( $form['number_of_article'] ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                <?php
                $orderby = array(
                    'lastest' => __('Latest', kopa_get_domain()),
                    'popular' => __('Popular by View Count', kopa_get_domain()),
                    'most_comment' => __('Popular by Comment Count', kopa_get_domain()),
                    'random' => __('Random', kopa_get_domain()),
                );
                foreach ($orderby as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['relation'] = $new_instance['relation'];
        $instance['tags'] = (empty($new_instance['tags'])) ? array() : array_filter($new_instance['tags']);
        $instance['number_of_article'] = (int) $new_instance['number_of_article'];
        // validate number of article
        if ( 0 >= $instance['number_of_article'] ) {
            $instance['number_of_article'] = 3;
        }
        $instance['orderby'] = $new_instance['orderby'];

        return $instance;
    }
}

/**
 * Entry List Widget Class
 * @since Musica 1.0
 */
class Kopa_Widget_Entry_List_2 extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'kopa-video-widget', 'description' => __('Display Latest Articles Widget with Masonry Style', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_entry_list_2', __('Kopa Entry List 2', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $query_args['categories'] = $instance['categories'];
        $query_args['relation'] = $instance['relation'];
        $query_args['tags'] = $instance['tags'];
        $query_args['posts_per_page'] = $instance['number_of_article'];
        $query_args['orderby'] = $instance['orderby'];

        echo $before_widget;

        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }

        $posts = kopa_widget_posttype_build_query( $query_args );
        ?>

        <?php if ( $posts->have_posts() ) { ?>
            <div class="masonry-wrapper">
                <ul class="entry-list transitions-enabled centered clearfix masonry masonry-container" data-item-class="masonry-box-<?php echo $this->get_field_id('item'); ?>">
            
            <?php while ( $posts->have_posts() ) { 
                $posts->the_post();

                // flag to check whether or not display comments
                $has_printed_thumbnail = false;

                if ( 'video' == get_post_format() ) {
                    $data_icon = '&#xe163;';
                } else {
                    $data_icon = '&#xe125;';
                }

                ?>
                
                <li class="masonry-box masonry-box-<?php echo $this->get_field_id('item'); ?>">
                    <article class="entry-item clearfix">
                        <div class="entry-thumb">
                        <?php if ( 'video' == get_post_format() ) { ?>
                            <?php 
                            $video = kopa_content_get_video( get_the_content() );
                            if ( isset( $video[0] ) ) {
                                $video = $video[0];
                            } else {
                                $video = '';
                            }
                            ?>

                            <?php if ( has_post_thumbnail() ) {
                                the_post_thumbnail( 'article-list-image-size' );
                                $has_printed_thumbnail = true;
                            } elseif ( isset( $video['type'] ) && isset( $video['url'] ) ) { 
                                $video_thumbnail = kopa_get_video_thumbnails_url( $video['type'], $video['url'] );
                                ?>
                                <?php if ( ! empty( $video_thumbnail ) ) { ?>
                                    <img src="<?php echo $video_thumbnail; ?>" alt="<?php the_title(); ?>">
                                    <?php $has_printed_thumbnail = true; ?>
                                <?php } ?>
                            <?php } ?>

                            <?php if ( isset( $video['url'] ) ) { ?>
                                <div class="mask"><a href="<?php echo $video['url']; ?>" data-icon="<?php echo $data_icon; ?>" rel="prettyPhoto"></a></div>
                            <?php } ?>

                        <?php } elseif ( has_post_thumbnail() ) { ?>

                            <?php the_post_thumbnail( 'article-list-image-size' ); ?>
                            <?php $has_printed_thumbnail = true; ?>
                            <div class="mask"><a href="<?php the_permalink(); ?>" data-icon="<?php echo $data_icon; ?>"></a></div>

                        <?php } elseif ( 'gallery' == get_post_format() ) { ?>

                            <?php $gallery_ids = kopa_content_get_gallery_attachment_ids( get_the_content() ); 
                            if ( ! empty( $gallery_ids ) ) {
                                foreach ( $gallery_ids as $id ) {
                                    if ( wp_attachment_is_image( $id ) ) {
                                        echo wp_get_attachment_image( $id, 'article-list-image-size' ); 
                                        ?>
                                        <div class="mask"><a href="<?php the_permalink(); ?>" data-icon="<?php echo $data_icon; ?>"></a></div>
                                    <?php
                                        $has_printed_thumbnail = true;
                                        break;
                                    } // endif
                                } // endforeach
                            } // endif ?>

                        <?php } // endif ?>
                        </div>
                        <!-- entry-thumb -->
                        <div class="entry-content">
                            <header class="clearfix">
                                <h5 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                                <span class="entry-date">&mdash; <?php the_time( get_option( 'date_format' ) ); ?></span>
                            </header>
                            <?php the_excerpt(); ?>
                        </div>
                        <!-- entry-content -->
                    </article>
                </li>

            <?php } // endwhile ?>
            
                </ul>
            </div>

        <?php } // endif ?>

        <?php
        wp_reset_postdata();

        echo $after_widget;
    }

    function form($instance) {
        $defaults = array(
            'title'             => '',
            'categories'        => array(),
            'relation'          => 'OR',
            'tags'              => array(),
            'number_of_article' => 8,
            'orderby'           => 'lastest',
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = strip_tags( $instance['title'] );

        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['number_of_article'] = $instance['number_of_article'];
        $form['orderby'] = $instance['orderby'];

        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $categories = get_categories();
                foreach ($categories as $category) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, $form['categories'])) ? 'selected="selected"' : '');
                }
                ?>
            </select>

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('relation'); ?>" name="<?php echo $this->get_field_name('relation'); ?>" autocomplete="off">
                <?php
                $relation = array(
                    'AND' => __('And', kopa_get_domain()),
                    'OR' => __('Or', kopa_get_domain())
                );
                foreach ($relation as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['relation']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $tags = get_tags();
                foreach ($tags as $tag) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $tag->term_id, $tag->name, $tag->count, (in_array($tag->term_id, $form['tags'])) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number_of_article'); ?>"><?php _e('Number of article:', kopa_get_domain()); ?></label>                
            <input class="widefat" type="number" min="1" id="<?php echo $this->get_field_id('number_of_article'); ?>" name="<?php echo $this->get_field_name('number_of_article'); ?>" value="<?php echo esc_attr( $form['number_of_article'] ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                <?php
                $orderby = array(
                    'lastest' => __('Latest', kopa_get_domain()),
                    'popular' => __('Popular by View Count', kopa_get_domain()),
                    'most_comment' => __('Popular by Comment Count', kopa_get_domain()),
                    'random' => __('Random', kopa_get_domain()),
                );
                foreach ($orderby as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['relation'] = $new_instance['relation'];
        $instance['tags'] = (empty($new_instance['tags'])) ? array() : array_filter($new_instance['tags']);
        $instance['number_of_article'] = (int) $new_instance['number_of_article'];
        // validate number of article
        if ( 0 >= $instance['number_of_article'] ) {
            $instance['number_of_article'] = 8;
        }
        $instance['orderby'] = $new_instance['orderby'];

        return $instance;
    }
}

class Kopa_Widget_Featured_News extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'kp-featured-news-widget', 'description' => __('Display Featured News Slider Widget', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_featured_news', __('Kopa Featured News', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $query_args['categories'] = $instance['categories'];
        $query_args['relation'] = $instance['relation'];
        $query_args['tags'] = $instance['tags'];
        $query_args['posts_per_page'] = $instance['number_of_article'];
        $query_args['orderby'] = $instance['orderby'];

        echo $before_widget;

        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }

        $posts = kopa_widget_posttype_build_query( $query_args );
        ?>

        <?php if ( $posts->have_posts() ) { ?>
            <div class="flexslider kp-featured-news-slider">
                <ul class="slides">
            
            <?php while ( $posts->have_posts() ) { 
                $posts->the_post();

                $featured_image = '';
                $featured_image_src = '';

                if ( has_post_thumbnail() ) {
                    $featured_image = wp_get_attachment_image( get_post_thumbnail_id(), 'featured-image-size' );
                    $featured_image_src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'featured-image-size' );
                    $featured_image_src = $featured_image_src[0];
                } elseif ( 'video' == get_post_format() ) {
                    $video = kopa_content_get_video( get_the_content() );

                    if ( isset( $video[0] ) ) {
                        $video = $video[0];
                    } else {
                        $video = '';
                    }

                    if ( isset( $video['type'] ) && isset( $video['url'] ) ) {
                        $featured_image_src = kopa_get_video_thumbnails_url( $video['type'], $video['url'] );
                        $featured_image = '<img src="'.$featured_image_src.'" alt="'.get_the_title().'">';
                    }
                } elseif ( 'gallery' == get_post_format() ) {
                    $gallery_ids = kopa_content_get_gallery_attachment_ids( get_the_content() );

                    foreach ( $gallery_ids as $id ) {
                        if ( wp_attachment_is_image( $id ) ) {
                            $featured_image = wp_get_attachment_image( $id, 'featured-image-size' );
                            $featured_image_src = wp_get_attachment_image_src( $id, 'featured-image-size' );
                            $featured_image_src = $featured_image_src[0];
                        }
                    }
                }
                ?>

                <li data-thumb="<?php echo $featured_image_src; ?>">
                    <a href="<?php the_permalink(); ?>"><?php echo $featured_image; ?></a>
                    <div class="entry-content">
                        <header>
                            <h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                            <span class="entry-date">&mdash; <?php the_time( get_option( 'date_format' ) ); ?></span>
                        </header>
                        <?php the_excerpt(); ?>
                    </div>
                </li>   

            <?php } // endwhile ?>
            
               </ul>
            </div>
        <?php } // endif ?>

        <?php
        wp_reset_postdata();

        echo $after_widget;
    }

    function form($instance) {
        $defaults = array(
            'title'             => '',
            'categories'        => array(),
            'relation'          => 'OR',
            'tags'              => array(),
            'number_of_article' => 4,
            'orderby'           => 'lastest',
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = strip_tags( $instance['title'] );

        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['number_of_article'] = $instance['number_of_article'];
        $form['orderby'] = $instance['orderby'];

        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $categories = get_categories();
                foreach ($categories as $category) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, $form['categories'])) ? 'selected="selected"' : '');
                }
                ?>
            </select>

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('relation'); ?>" name="<?php echo $this->get_field_name('relation'); ?>" autocomplete="off">
                <?php
                $relation = array(
                    'AND' => __('And', kopa_get_domain()),
                    'OR' => __('Or', kopa_get_domain())
                );
                foreach ($relation as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['relation']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $tags = get_tags();
                foreach ($tags as $tag) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $tag->term_id, $tag->name, $tag->count, (in_array($tag->term_id, $form['tags'])) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number_of_article'); ?>"><?php _e('Number of Articles:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id( 'number_of_article' ); ?>" name="<?php echo $this->get_field_name( 'number_of_article' ); ?>">
                <?php $number_of_article = array( 1, 2, 3, 4 );
                foreach ( $number_of_article as $value ) { ?>
                    <option value="<?php echo $value; ?>" <?php selected( $form['number_of_article'], $value ); ?>><?php echo $value; ?></option>
                <?php } ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                <?php
                $orderby = array(
                    'lastest' => __('Latest', kopa_get_domain()),
                    'popular' => __('Popular by View Count', kopa_get_domain()),
                    'most_comment' => __('Popular by Comment Count', kopa_get_domain()),
                    'random' => __('Random', kopa_get_domain()),
                );
                foreach ($orderby as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['relation'] = $new_instance['relation'];
        $instance['tags'] = (empty($new_instance['tags'])) ? array() : array_filter($new_instance['tags']);
        $instance['number_of_article'] = (int) $new_instance['number_of_article'];
        // validate number of article
        if ( 0 >= $instance['number_of_article'] ) {
            $instance['number_of_article'] = 8;
        }
        $instance['orderby'] = $new_instance['orderby'];

        return $instance;
    }
}

/**
 * Mailchimp Subscribe Widget Class
 * @since Forceful 1.0
 */
class Kopa_Widget_Mailchimp_Subscribe extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'kopa-newsletter-widget', 'description' => __('Display mailchimp newsletter subscription form', kopa_get_domain()));
        $control_ops = array('width' => '400', 'height' => 'auto');
        parent::__construct('kopa_widget_mailchimp_subscribe', __('Kopa Mailchimp Subscribe', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $mailchimp_form_action = $instance['mailchimp_form_action'];
        $mailchimp_enable_popup = $instance['mailchimp_enable_popup'];
        $description = $instance['description'];

        echo $before_widget;

        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }

        if ( ! empty( $mailchimp_form_action ) ) :

        ?>

        <form action="<?php echo esc_url( $mailchimp_form_action ); ?>" method="post" class="newsletter-form clearfix" <?php echo $mailchimp_enable_popup ? 'target="_blank"' : ''; ?>>
            <p class="input-email clearfix">
                <input type="text" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;" name="EMAIL" value="<?php _e( 'Subscribe to newsletter...', kopa_get_domain() ); ?>" class="email" size="40">
                <input type="submit" value="<?php _e( 'Subscribe', kopa_get_domain() ); ?>" class="submit">
            </p>
            <p><?php echo $description; ?></p>
        </form>

        <?php
        endif;
        
        echo $after_widget;
    }

    function form( $instance ) {
        $defaults = array(
            'title'                  => '',
            'mailchimp_form_action'  => '',
            'mailchimp_enable_popup' => false,
            'description'            => ''
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = strip_tags( $instance['title'] );
        $form['mailchimp_form_action'] = $instance['mailchimp_form_action'];
        $form['mailchimp_enable_popup'] = $instance['mailchimp_enable_popup'];
        $form['description'] = $instance['description'];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('mailchimp_form_action'); ?>"><?php _e('Mailchimp Form Action:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('mailchimp_form_action'); ?>" name="<?php echo $this->get_field_name('mailchimp_form_action'); ?>" type="text" value="<?php echo esc_attr($form['mailchimp_form_action']); ?>">
        </p>
        <p>
            <input type="checkbox" value="true" id="<?php echo $this->get_field_id( 'mailchimp_enable_popup' ); ?>" name="<?php echo $this->get_field_name( 'mailchimp_enable_popup' ); ?>" <?php checked( true, $form['mailchimp_enable_popup'] ); ?>>
            <label for="<?php echo $this->get_field_id( 'mailchimp_enable_popup' ); ?>"><?php _e( 'Enable <strong>evil</strong> popup mode', kopa_get_domain() ); ?></label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php _e( 'Description', kopa_get_domain() ); ?></label>
            <textarea class="widefat" name="<?php echo $this->get_field_name('description') ?>" id="<?php echo $this->get_field_id('description') ?>" rows="5"><?php echo esc_textarea( $form['description'] ); ?></textarea>
        </p>
        <?php
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['mailchimp_form_action'] = $new_instance['mailchimp_form_action'];
        $instance['mailchimp_enable_popup'] = (bool) $new_instance['mailchimp_enable_popup'] ? true : false;
        $instance['description'] = strip_tags( $new_instance['description'] );

        return $instance;
    }
}

/**
 * FeedBurner Subscribe Widget Class
 * @since Forceful 1.0
 */
class Kopa_Widget_Feedburner_Subscribe extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'kopa-newsletter-widget', 'description' => __('Display Feedburner subscription form', kopa_get_domain()));
        $control_ops = array('width' => '400', 'height' => 'auto');
        parent::__construct('kopa_widget_feedburner_subscribe', __('Kopa Feedburner Subscribe', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $feedburner_id = $instance['feedburner_id'];
        $description = $instance['description'];

        echo $before_widget;

        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }

        if ( ! empty( $feedburner_id ) ) {

        ?>

        <form action="http://feedburner.google.com/fb/a/mailverify" method="post" class="newsletter-form clearfix" target="popupwindow" onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=<?php echo esc_attr( $feedburner_id ); ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true">

            <input type="hidden" value="<?php echo esc_attr( $feedburner_id ); ?>" name="uri">

            <p class="input-email clearfix">
                <input type="text" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;" name="email" value="<?php _e( 'Subscribe to newsletter...', kopa_get_domain() ); ?>" class="email" size="40">
                <input type="submit" value="<?php _e( 'Subscribe', kopa_get_domain() ); ?>" class="submit">
            </p>
            
            <p><?php echo $description; ?></p>
        </form>


        <?php
        } // endif
        
        echo $after_widget;
    }

    function form( $instance ) {
        $defaults = array(
            'title'         => '',
            'feedburner_id' => '',
            'description'   => ''
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = strip_tags( $instance['title'] );
        $form['feedburner_id'] = $instance['feedburner_id'];
        $form['description'] = $instance['description'];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('feedburner_id'); ?>"><?php _e('Feedburner ID (http://feeds.feedburner.com/<strong>wordpress/kopatheme</strong>):', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('feedburner_id'); ?>" name="<?php echo $this->get_field_name('feedburner_id'); ?>" type="text" value="<?php echo esc_attr($form['feedburner_id']); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php _e( 'Description', kopa_get_domain() ); ?></label>
            <textarea class="widefat" name="<?php echo $this->get_field_name('description') ?>" id="<?php echo $this->get_field_id('description') ?>" rows="5"><?php echo esc_textarea( $form['description'] ); ?></textarea>
        </p>
        <?php
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['feedburner_id'] = strip_tags( $new_instance['feedburner_id'] );
        $instance['description'] = strip_tags( $new_instance['description'] );

        return $instance;
    }
}