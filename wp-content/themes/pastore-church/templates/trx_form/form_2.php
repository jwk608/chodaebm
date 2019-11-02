<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'pastore_church_template_form_2_theme_setup' ) ) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_template_form_2_theme_setup', 1 );
	function pastore_church_template_form_2_theme_setup() {
		pastore_church_add_template(array(
			'layout' => 'form_2',
			'mode'   => 'forms',
			'title'  => esc_html__('Contact Form 2', 'pastore-church')
			));
	}
}

// Template output
if ( !function_exists( 'pastore_church_template_form_2_output' ) ) {
	function pastore_church_template_form_2_output($post_options, $post_data) {
		$address_1 = pastore_church_get_theme_option('contact_address_1');
		$address_2 = pastore_church_get_theme_option('contact_address_2');
		$phone = pastore_church_get_theme_option('contact_phone');
		$fax = pastore_church_get_theme_option('contact_fax');
		$email = pastore_church_get_theme_option('contact_email');
		$open_hours = pastore_church_get_theme_option('contact_open_hours');
		?>
		<div class="sc_columns columns_wrap">
			<div class="sc_form_fields column-2_3">
				<?php
				echo (!empty($post_options['title'])
				? '<h5 class="sc_form_title sc_item_title">' . trim(pastore_church_strmacros($post_options['title'])) . '</h5>'
				: '');
				?>
				<form <?php echo !empty($post_options['id']) ? ' id="'.esc_attr($post_options['id']).'_form"' : ''; ?> data-formtype="<?php echo esc_attr($post_options['layout']); ?>" method="post" action="<?php echo esc_url($post_options['action'] ? $post_options['action'] : admin_url('admin-ajax.php')); ?>">
					<?php pastore_church_sc_form_show_fields($post_options['fields']); ?>
					<div class="sc_form_info">
						<div class="sc_form_item sc_form_field label_over left"><label class="required" for="sc_form_username"><?php esc_html_e('Name', 'pastore-church'); ?></label><input id="sc_form_username" type="text" name="username" placeholder="<?php esc_attr_e('Name *', 'pastore-church'); ?>"></div>
						<div class="sc_form_item sc_form_field label_over right"><label class="required" for="sc_form_email"><?php esc_html_e('E-mail', 'pastore-church'); ?></label><input id="sc_form_email" type="text" name="email" placeholder="<?php esc_attr_e('E-mail *', 'pastore-church'); ?>"></div>
						<div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_subj"><?php esc_html_e('Subject', 'pastore-church'); ?></label><input id="sc_form_subj" type="text" name="subject" placeholder="<?php esc_attr_e('Subject', 'pastore-church'); ?>"></div>
					</div>
					<div class="sc_form_item sc_form_message label_over"><label class="required" for="sc_form_message"><?php esc_html_e('Message', 'pastore-church'); ?></label><textarea id="sc_form_message" name="message" placeholder="<?php esc_attr_e('Message', 'pastore-church'); ?>"></textarea></div>
					<div class="sc_form_item sc_form_button"><button><?php esc_html_e('Send', 'pastore-church'); ?></button></div>
					<div class="result sc_infobox"></div>
				</form>
			</div><div class="sc_form_address column-1_3">
				<?php
				echo (!empty($post_options['title'])
					? '<h5 class="sc_form_title sc_item_title">' .esc_html__('Find Us', 'pastore-church'). '</h5>'
					: '');
				?>
				<div class="sc_form_address_field address">
					<span class="sc_form_address_label"><?php esc_html_e('Address', 'pastore-church'); ?></span>
					<span class="sc_form_address_data"><?php echo trim($address_1) . (!empty($address_1) && !empty($address_2) ? ', ' : '') . $address_2; ?></span>
				</div>
				<div class="sc_form_address_field phone">
					<span class="sc_form_address_label"><?php esc_html_e('Phone', 'pastore-church'); ?></span>
					<span class="sc_form_address_data"><?php echo trim($phone) . (!empty($phone) && !empty($fax) ? ', ' : '') . $fax; ?></span>
				</div>
				<div class="sc_form_address_field hours">
					<span class="sc_form_address_label"><?php esc_html_e('We are open', 'pastore-church'); ?></span>
					<span class="sc_form_address_data"><?php echo trim($open_hours); ?></span>
				</div>
				<div class="sc_form_address_field email">
					<span class="sc_form_address_label"><?php esc_html_e('E-mail', 'pastore-church'); ?></span>
					<span class="sc_form_address_data"><?php echo trim($email); ?></span>
				</div>
				<?php //echo do_shortcode('[trx_socials size="tiny" shape="round"][/trx_socials]'); ?>
			</div>
		</div>
		<?php
	}
}
?>