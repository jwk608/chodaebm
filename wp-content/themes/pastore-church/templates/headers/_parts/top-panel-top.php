<?php 
// Get template args
extract(pastore_church_template_get_args('top-panel-top'));

if (in_array('contact_info', $top_panel_top_components) && ($contact_info=trim(pastore_church_get_custom_option('contact_info')))!='') {
	?>
	<div class="top_panel_top_contact_area icon-location-light">
		<?php echo force_balance_tags($contact_info); ?>
	</div>
	<?php
}
?>

<div class="top_panel_top_user_area">
	<?php

	if (in_array('search', $top_panel_top_components) && pastore_church_get_custom_option('show_search')=='yes') {
		?>
		<div class="top_panel_top_search"><?php echo trim(pastore_church_sc_search(array('state'=>'fixed'))); ?></div>
		<?php
	}

		?>
		<ul id="menu_user" class="menu_user_nav">
		<?php

	if (in_array('currency', $top_panel_top_components) && function_exists('pastore_church_is_woocommerce_page') && pastore_church_is_woocommerce_page() && pastore_church_get_custom_option('show_currency')=='yes') {
		?>
		<li class="menu_user_currency">
			<a href="#">$</a>
			<ul>
				<li><a href="#"><b>&#36;</b> <?php esc_html_e('Dollar', 'pastore-church'); ?></a></li>
				<li><a href="#"><b>&euro;</b> <?php esc_html_e('Euro', 'pastore-church'); ?></a></li>
				<li><a href="#"><b>&pound;</b> <?php esc_html_e('Pounds', 'pastore-church'); ?></a></li>
			</ul>
		</li>
		<?php
	}

	if (in_array('language', $top_panel_top_components) && pastore_church_get_custom_option('show_languages')=='yes' && function_exists('icl_get_languages')) {
		$languages = icl_get_languages('skip_missing=1');
		if (!empty($languages) && is_array($languages)) {
			$lang_list = '';
			$lang_active = '';
			foreach ($languages as $lang) {
				$lang_title = esc_attr($lang['translated_name']);	//esc_attr($lang['native_name']);
				if ($lang['active']) {
					$lang_active = $lang_title;
				}
				$lang_list .= "\n"
					.'<li><a rel="alternate" hreflang="' . esc_attr($lang['language_code']) . '" href="' . esc_url(apply_filters('WPML_filter_link', $lang['url'], $lang)) . '">'
						.'<img src="' . esc_url($lang['country_flag_url']) . '" alt="' . esc_attr($lang_title) . '" title="' . esc_attr($lang_title) . '" />'
						. ($lang_title)
					.'</a></li>';
			}
			?>
			<li class="menu_user_language">
				<a href="#"><span><?php echo trim($lang_active); ?></span></a>
				<ul><?php echo trim($lang_list); ?></ul>
			</li>
			<?php
		}
	}

	if (in_array('bookmarks', $top_panel_top_components) && pastore_church_get_custom_option('show_bookmarks')=='yes') {
		// Load core messages
		pastore_church_enqueue_messages();
		?>
		<li class="menu_user_bookmarks"><a href="#" class="bookmarks_show icon-star" title="<?php esc_attr_e('Show bookmarks', 'pastore-church'); ?>"><?php esc_html_e('Bookmarks', 'pastore-church'); ?></a>
		<?php 
			$list = pastore_church_get_value_gpc('pastore_church_bookmarks', '');
			if (!empty($list)) $list = json_decode($list, true);
			?>
			<ul class="bookmarks_list">
				<li><a href="#" class="bookmarks_add icon-star-empty" title="<?php esc_attr_e('Add the current page into bookmarks', 'pastore-church'); ?>"><?php esc_html_e('Add bookmark', 'pastore-church'); ?></a></li>
				<?php 
				if (!empty($list) && is_array($list)) {
					foreach ($list as $bm) {
						echo '<li><a href="'.esc_url($bm['url']).'" class="bookmarks_item">'.($bm['title']).'<span class="bookmarks_delete icon-cancel" title="'.esc_attr__('Delete this bookmark', 'pastore-church').'"></span></a></li>';
					}
				}
				?>
			</ul>
		</li>
		<?php 
	}

	if (in_array('login', $top_panel_top_components) && pastore_church_get_custom_option('show_login')=='yes') {
		if ( !is_user_logged_in() ) {
			// Load core messages
			pastore_church_enqueue_messages();
			// Anyone can register ?
			if ( (int) get_option('users_can_register') > 0) {
				?><li class="menu_user_register"><a href="#popup_registration" class="popup_link popup_register_link icon-pencil-light"><?php esc_html_e('Register', 'pastore-church'); ?></a></li><?php
			}
			?><li class="menu_user_login"><a href="#popup_login" class="popup_link popup_login_link icon-key-light"><?php esc_html_e('Login', 'pastore-church'); ?></a></li><?php
		} else {
			$current_user = wp_get_current_user();
			?>
			<li class="menu_user_controls">
				<a href="#"><?php
					$user_avatar = '';
					$mult = pastore_church_get_retina_multiplier();
					if ($current_user->user_email) $user_avatar = get_avatar($current_user->user_email, 16*$mult);
					if ($user_avatar) {
						?><span class="user_avatar"><?php echo trim($user_avatar); ?></span><?php
					}?><span class="user_name"><?php echo trim($current_user->display_name); ?></span></a>
				<ul>
					<?php if (current_user_can('publish_posts')) { ?>
					<li><a href="<?php echo esc_url(home_url('/')); ?>/wp-admin/post-new.php?post_type=post" class="icon icon-doc"><?php esc_html_e('New post', 'pastore-church'); ?></a></li>
					<?php } ?>
					<li><a href="<?php echo get_edit_user_link(); ?>" class="icon icon-cog"><?php esc_html_e('Settings', 'pastore-church'); ?></a></li>
				</ul>
			</li>
			<li class="menu_user_logout"><a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>" class="icon icon-logout"><?php esc_html_e('Logout', 'pastore-church'); ?></a></li>
			<?php 
		}
	}

	if (in_array('cart', $top_panel_top_components) && function_exists('pastore_church_exists_woocommerce') && pastore_church_exists_woocommerce() && (pastore_church_is_woocommerce_page() && pastore_church_get_custom_option('show_cart')=='shop' || pastore_church_get_custom_option('show_cart')=='always') && !(is_checkout() || is_cart() || defined('WOOCOMMERCE_CHECKOUT') || defined('WOOCOMMERCE_CART'))) { 
		?>
		<li class="menu_user_cart">
			<?php get_template_part(pastore_church_get_file_slug('templates/headers/_parts/contact-info-cart.php')); ?>
		</li>
		<?php
	}

	if (pastore_church_get_custom_option('show_extra_button')=='yes' && ($button_text=trim(pastore_church_get_custom_option('extra_button_text')))!='' && ($button_link=trim(pastore_church_get_custom_option('extra_button_link')))!='') {
		?>
		<li class="menu_extra_button">
			<a class="icon-heart-light" href="<?php echo esc_url($button_link); ?>"><?php echo esc_html($button_text); ?></a>
		</li>
		<?php
		}
	?>

	</ul>

</div>