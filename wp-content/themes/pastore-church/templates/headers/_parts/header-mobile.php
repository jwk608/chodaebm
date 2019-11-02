<?php
$header_options = pastore_church_storage_get('header_mobile');
$contact_address_1 = trim(pastore_church_get_custom_option('contact_address_1'));
$contact_address_2 = trim(pastore_church_get_custom_option('contact_address_2'));
$contact_phone = trim(pastore_church_get_custom_option('contact_phone'));
$contact_email = trim(pastore_church_get_custom_option('contact_email'));
?>
	<div class="header_mobile">
		<div class="content_wrap">
			<div class="menu_button icon-menu"></div>
			<?php 
			pastore_church_show_logo(); 
			if ($header_options['woo_cart']){
				if (function_exists('pastore_church_exists_woocommerce') && pastore_church_exists_woocommerce() && (pastore_church_is_woocommerce_page() && pastore_church_get_custom_option('show_cart')=='shop' || pastore_church_get_custom_option('show_cart')=='always') && !(is_checkout() || is_cart() || defined('WOOCOMMERCE_CHECKOUT') || defined('WOOCOMMERCE_CART'))) { 
					?>
					<div class="menu_main_cart top_panel_icon">
						<?php get_template_part(pastore_church_get_file_slug('templates/headers/_parts/contact-info-cart.php')); ?>
					</div>
					<?php
				}
			}
			?>
		</div>
		<div class="side_wrap">
			<div class="close"><?php esc_html_e('Close', 'pastore-church'); ?></div>
			<div class="panel_top">
				<nav class="menu_main_nav_area">
					<?php
						$menu_main = pastore_church_get_nav_menu('menu_main', '', 'mobile');
						if (empty($menu_main)) $menu_main = pastore_church_get_nav_menu('', '', 'mobile');
							echo trim($menu_main);
					?>
				</nav>
				<?php 
				if ($header_options['search'] && pastore_church_get_custom_option('show_search')=='yes')
					echo trim(pastore_church_sc_search(array()));
				
				if ($header_options['login']) {
					if ( is_user_logged_in() ) { 
						?>
						<div class="login"><a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>" class="popup_link"><?php esc_html_e('Logout', 'pastore-church'); ?></a></div>
						<?php
					} else {
						// Load core messages
						pastore_church_enqueue_messages();
						// Load Popup engine
						pastore_church_enqueue_popup();
						?>
						<div class="login"><a href="#popup_login" class="popup_link popup_login_link icon-user"><?php esc_html_e('Login', 'pastore-church'); ?></a><?php
							if (pastore_church_get_theme_option('show_login')=='yes') {
								get_template_part(pastore_church_get_file_slug('templates/headers/_parts/login.php'));
							}?>
						</div>
						<?php
						// Anyone can register ?
						if ( (int) get_option('users_can_register') > 0) {
							?>
							<div class="login"><a href="#popup_registration" class="popup_link popup_register_link icon-pencil"><?php esc_html_e('Register', 'pastore-church'); ?></a><?php
								if (pastore_church_get_theme_option('show_login')=='yes') {
									get_template_part(pastore_church_get_file_slug('templates/headers/_parts/register.php'));
								}?>
							</div>
							<?php 
						}
					}
				}
				?>
			</div>
			
			<?php if ($header_options['contact_address'] || $header_options['contact_phone_email'] || $header_options['open_hours']) { ?>
			<div class="panel_middle">
				<?php
				if ($header_options['contact_address'] && (!empty($contact_address_1) || !empty($contact_address_2))) {
					?><div class="contact_field contact_address">
								<span class="contact_icon icon-home"></span>
								<span class="contact_label contact_address_1"><?php echo force_balance_tags($contact_address_1); ?></span>
								<span class="contact_address_2"><?php echo force_balance_tags($contact_address_2); ?></span>
							</div><?php
				}
						
				if ($header_options['contact_phone_email'] && (!empty($contact_phone) || !empty($contact_email))) {
					?><div class="contact_field contact_phone">
						<span class="contact_icon icon-phone"></span>
						<span class="contact_label contact_phone"><?php echo force_balance_tags($contact_phone); ?></span>
						<span class="contact_email"><?php echo force_balance_tags($contact_email); ?></span>
					</div><?php
				}
				
				pastore_church_template_set_args('top-panel-top', array(
					'top_panel_top_components' => array(
						($header_options['open_hours'] ? 'open_hours' : '')
					)
				));
				//get_template_part(pastore_church_get_file_slug('templates/headers/_parts/top-panel-top.php'));
				?>
			</div>
			<?php } ?>

		</div>
		<div class="mask"></div>
	</div>

<?php if ( is_user_logged_in() ) { ?>
	<script>jQuery('html').addClass('bar');</script>
<?php } ?>