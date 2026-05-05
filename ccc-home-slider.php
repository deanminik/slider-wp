<?php
/**
 * Plugin Name: CCC Home Slider
 * Description: A lightweight, custom WordPress slider plugin using Splide.js.
 * Version: 1.0.0
 * Author: Dean Fernández
 * Text Domain: ccc-home-slider
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

define('CCC_SLIDER_VERSION', '1.0.0');
define('CCC_SLIDER_URL', plugin_dir_url(__FILE__));
define('CCC_SLIDER_DIR', plugin_dir_path(__FILE__));

/**
 * Register Custom Post Type: Home Slide
 */
function ccc_slider_register_cpt()
{
	$labels = array(
		'name' => _x('Home Slides', 'post type general name', 'ccc-home-slider'),
		'singular_name' => _x('Home Slide', 'post type singular name', 'ccc-home-slider'),
		'menu_name' => _x('Home Slides', 'admin menu', 'ccc-home-slider'),
		'name_admin_bar' => _x('Home Slide', 'add new on admin bar', 'ccc-home-slider'),
		'add_new' => _x('Add New', 'slide', 'ccc-home-slider'),
		'add_new_item' => __('Add New Slide', 'ccc-home-slider'),
		'new_item' => __('New Slide', 'ccc-home-slider'),
		'edit_item' => __('Edit Slide', 'ccc-home-slider'),
		'view_item' => __('View Slide', 'ccc-home-slider'),
		'all_items' => __('All Slides', 'ccc-home-slider'),
		'search_items' => __('Search Slides', 'ccc-home-slider'),
		'parent_item_colon' => __('Parent Slides:', 'ccc-home-slider'),
		'not_found' => __('No slides found.', 'ccc-home-slider'),
		'not_found_in_trash' => __('No slides found in Trash.', 'ccc-home-slider')
	);

	$args = array(
		'labels' => $labels,
		'public' => false,
		'publicly_queryable' => false,
		'show_ui' => true,
		'show_in_menu' => true,
		'query_var' => false,
		'rewrite' => false,
		'capability_type' => 'post',
		'has_archive' => false,
		'hierarchical' => false,
		'menu_position' => null,
		'menu_icon' => 'dashicons-images-alt2',
		'supports' => array('title', 'thumbnail', 'excerpt')
	);

	register_post_type('home_slide', $args);
}
add_action('init', 'ccc_slider_register_cpt');

/**
 * Register Meta Boxes
 */
function ccc_slider_add_meta_boxes()
{
	add_meta_box(
		'ccc_slide_settings',
		__('Slide Settings', 'ccc-home-slider'),
		'ccc_slider_render_meta_box',
		'home_slide',
		'normal',
		'default'
	);
}
add_action('add_meta_boxes', 'ccc_slider_add_meta_boxes');

/**
 * Render Meta Box HTML
 */
function ccc_slider_render_meta_box($post)
{
	wp_nonce_field('ccc_slider_save_meta_box_data', 'ccc_slider_meta_box_nonce');

	$primary_btn_text = get_post_meta($post->ID, '_ccc_primary_btn_text', true);
	$primary_btn_url = get_post_meta($post->ID, '_ccc_primary_btn_url', true);
	$secondary_text = get_post_meta($post->ID, '_ccc_secondary_text', true);
	$secondary_link = get_post_meta($post->ID, '_ccc_secondary_link', true);
	$secondary_url = get_post_meta($post->ID, '_ccc_secondary_url', true);

	if (empty($primary_btn_text)) {
		$primary_btn_text = 'Ver más';
	}
	?>
	<table class="form-table">
		<tr>
			<th><label for="ccc_primary_btn_text"><?php _e('Primary Button Text', 'ccc-home-slider'); ?></label></th>
			<td><input type="text" id="ccc_primary_btn_text" name="ccc_primary_btn_text"
					value="<?php echo esc_attr($primary_btn_text); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="ccc_primary_btn_url"><?php _e('Primary Button URL', 'ccc-home-slider'); ?></label></th>
			<td><input type="url" id="ccc_primary_btn_url" name="ccc_primary_btn_url"
					value="<?php echo esc_url($primary_btn_url); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label
					for="ccc_secondary_text"><?php _e('Secondary Text (e.g. ¿Tiene preguntas?)', 'ccc-home-slider'); ?></label>
			</th>
			<td><input type="text" id="ccc_secondary_text" name="ccc_secondary_text"
					value="<?php echo esc_attr($secondary_text); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label
					for="ccc_secondary_link"><?php _e('Secondary Link Text (e.g. Contáctenos.)', 'ccc-home-slider'); ?></label>
			</th>
			<td><input type="text" id="ccc_secondary_link" name="ccc_secondary_link"
					value="<?php echo esc_attr($secondary_link); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="ccc_secondary_url"><?php _e('Secondary Link URL', 'ccc-home-slider'); ?></label></th>
			<td><input type="url" id="ccc_secondary_url" name="ccc_secondary_url"
					value="<?php echo esc_url($secondary_url); ?>" class="regular-text" /></td>
		</tr>
	</table>
	<?php
}

/**
 * Save Meta Box Data
 */
function ccc_slider_save_meta_box_data($post_id)
{
	if (!isset($_POST['ccc_slider_meta_box_nonce']) || !wp_verify_nonce($_POST['ccc_slider_meta_box_nonce'], 'ccc_slider_save_meta_box_data')) {
		return;
	}

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	if (!current_user_can('edit_post', $post_id)) {
		return;
	}

	if (isset($_POST['ccc_primary_btn_text'])) {
		update_post_meta($post_id, '_ccc_primary_btn_text', sanitize_text_field($_POST['ccc_primary_btn_text']));
	}
	if (isset($_POST['ccc_primary_btn_url'])) {
		update_post_meta($post_id, '_ccc_primary_btn_url', esc_url_raw($_POST['ccc_primary_btn_url']));
	}
	if (isset($_POST['ccc_secondary_text'])) {
		update_post_meta($post_id, '_ccc_secondary_text', sanitize_text_field($_POST['ccc_secondary_text']));
	}
	if (isset($_POST['ccc_secondary_link'])) {
		update_post_meta($post_id, '_ccc_secondary_link', sanitize_text_field($_POST['ccc_secondary_link']));
	}
	if (isset($_POST['ccc_secondary_url'])) {
		update_post_meta($post_id, '_ccc_secondary_url', esc_url_raw($_POST['ccc_secondary_url']));
	}
}
add_action('save_post_home_slide', 'ccc_slider_save_meta_box_data');

/**
 * Register Assets (Not enqueued globally)
 */
function ccc_slider_register_assets()
{
	wp_register_style('splide-css', CCC_SLIDER_URL . 'assets/css/splide.min.css', array(), '4.1.4');
	wp_register_style('ccc-slider-style', CCC_SLIDER_URL . 'assets/css/style.css', array('splide-css'), CCC_SLIDER_VERSION);

	wp_register_script('splide-js', CCC_SLIDER_URL . 'assets/js/splide.min.js', array(), '4.1.4', true);
	wp_register_script('ccc-slider-init', CCC_SLIDER_URL . 'assets/js/init.js', array('splide-js'), CCC_SLIDER_VERSION, true);
}
add_action('wp_enqueue_scripts', 'ccc_slider_register_assets');

/**
 * Shortcode Output
 */
function ccc_slider_shortcode($atts)
{
	// Enqueue assets conditionally because shortcode is used
	wp_enqueue_style('ccc-slider-style');
	wp_enqueue_script('ccc-slider-init');

	$args = array(
		'post_type' => 'home_slide',
		'posts_per_page' => -1,
		'orderby' => 'menu_order',
		'order' => 'ASC'
	);

	$query = new WP_Query($args);

	if (!$query->have_posts()) {
		return '';
	}

	ob_start();
	?>
	<div class="splide ccc-home-slider" aria-label="<?php esc_attr_e('Home Slider', 'ccc-home-slider'); ?>">
		<!-- Static Arrows Overlay -->
		<div class="ccc-slider-arrows-overlay">
			<div class="ccc-slide-content-wrapper">
				<div class="ccc-slide-content">
					<div class="splide__arrows">
						<button class="splide__arrow splide__arrow--prev">
							<span class="sr-only">Previous slide</span>
						</button>
						<button class="splide__arrow splide__arrow--next">
							<span class="sr-only">Next slide</span>
						</button>
					</div>
				</div>
			</div>
		</div>
		<div class="splide__track">
			<ul class="splide__list">
				<?php
				$slide_index = 0;
				while ($query->have_posts()):
					$query->the_post();
					$post_id = get_the_ID();
					$thumbnail_id = get_post_thumbnail_id();

					// Get Image URL
					$img_url = wp_get_attachment_image_url($thumbnail_id, 'full');
					if (!$img_url) {
						$img_url = ''; // fallback
					}

					$primary_btn_text = get_post_meta($post_id, '_ccc_primary_btn_text', true);
					$primary_btn_url = get_post_meta($post_id, '_ccc_primary_btn_url', true);
					$secondary_text = get_post_meta($post_id, '_ccc_secondary_text', true);
					$secondary_link = get_post_meta($post_id, '_ccc_secondary_link', true);
					$secondary_url = get_post_meta($post_id, '_ccc_secondary_url', true);

					// Optimize LCP on first slide
					$loading_attr = ($slide_index === 0) ? 'eager' : 'lazy';
					$fetchpriority = ($slide_index === 0) ? 'high' : 'auto';
					?>
					<li class="splide__slide">
						<div class="ccc-slide-bg">
							<?php if ($img_url): ?>
								<img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>"
									loading="<?php echo esc_attr($loading_attr); ?>"
									fetchpriority="<?php echo esc_attr($fetchpriority); ?>" class="ccc-slide-img" />
							<?php endif; ?>
							<div class="ccc-slide-overlay"></div>
						</div>
						<div class="ccc-slide-content-wrapper">
							<div class="ccc-slide-content">
								<?php if ($slide_index === 0): ?>
									<h1 class="ccc-slide-title"><?php the_title(); ?></h1>
								<?php else: ?>
									<h2 class="ccc-slide-title"><?php the_title(); ?></h2>
								<?php endif; ?>
								<?php /* if (has_excerpt()): ?>
									<div class="ccc-slide-excerpt"><?php the_excerpt(); ?></div>
								<?php endif; */ ?>

								<div class="ccc-slide-buttons">
									<?php if ($primary_btn_url): ?>
										<a href="<?php echo esc_url($primary_btn_url); ?>" class="ccc-btn-primary">
											<?php echo esc_html($primary_btn_text ?: 'Ver más'); ?>
										</a>
									<?php endif; ?>

									<?php if ($secondary_text || ($secondary_link && $secondary_url)): ?>
										<div class="ccc-btn-secondary">
											<?php if ($secondary_text): ?>
												<span class="ccc-secondary-text"><?php echo esc_html($secondary_text); ?></span>
											<?php endif; ?>
											<?php if ($secondary_link && $secondary_url): ?>
												<a href="<?php echo esc_url($secondary_url); ?>"
													class="ccc-secondary-link"><?php echo esc_html($secondary_link); ?></a>
											<?php endif; ?>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</li>
					<?php
					$slide_index++;
				endwhile;
				wp_reset_postdata();
				?>
			</ul>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode('ccc_slider', 'ccc_slider_shortcode');
