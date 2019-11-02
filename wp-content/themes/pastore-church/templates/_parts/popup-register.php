<div id="popup_registration" class="popup_wrap popup_registration bg_tint_light">
	<a href="#" class="popup_close"></a>
	<div class="form_wrap">
		<form name="registration_form" method="post" class="popup_form registration_form">
			<input type="hidden" name="redirect_to" value="<?php echo esc_attr(esc_url(home_url('/'))); ?>"/>
			<div class="form_left">

				<div class="registration_socials_title"><?php esc_html_e('You can register using your social profile', 'pastore-church'); ?></div>
				<div class="registration_socials_list">
					<?php echo trim(pastore_church_sc_socials(array('size'=>"tiny", 'shape'=>"round", 'socials'=>"facebook=#|twitter=#|gplus=#"))); ?>
				</div>
				<div class="registration_socials_or"><span><?php esc_html_e('or', 'pastore-church'); ?></span></div>

				<div class="popup_form_field login_field iconed_field"><input type="text" id="registration_username" name="registration_username"  value="" placeholder="<?php esc_attr_e('Login', 'pastore-church'); ?>"></div>
				<div class="popup_form_field email_field iconed_field"><input type="text" id="registration_email" name="registration_email" value="" placeholder="<?php esc_attr_e('Email', 'pastore-church'); ?>"></div>
			</div>
			<div class="form_right">
				<div class="popup_form_field password_field iconed_field"><input type="password" id="registration_pwd"  name="registration_pwd"  value="" placeholder="<?php esc_attr_e('Password', 'pastore-church'); ?>"></div>
				<div class="popup_form_field password_field iconed_field"><input type="password" id="registration_pwd2" name="registration_pwd2" value="" placeholder="<?php esc_attr_e('Confirm Password', 'pastore-church'); ?>"></div>
				<div class="popup_form_field agree_field">
					<input type="checkbox" value="agree" id="registration_agree" name="registration_agree">
					<label for="registration_agree"><?php esc_html_e('I agree with', 'pastore-church'); ?></label> <a href="#"><?php esc_html_e('Terms &amp; Conditions', 'pastore-church'); ?></a>
				</div>
				<div class="popup_form_field submit_field"><input type="submit" class="submit_button" value="<?php esc_attr_e('Sign Up', 'pastore-church'); ?>"></div>
			</div>
		</form>
		<div class="result message_block"></div>
	</div>	<!-- /.registration_wrap -->
</div>		<!-- /.user-popUp -->
