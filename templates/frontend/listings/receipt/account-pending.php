<?php if (!empty($_GET['cl-verify-request'])) : ?>
	<p class="cl-account-pending cl_success">
		<?php _e('An email with an activation link has been sent.', 'clasify-classified-listing'); ?>
	</p>
<?php endif; ?>
<p class="cl-account-pending">
	<?php $url = esc_url(CCP()->common->user->cl_get_user_verification_request_url()); ?>
	<?php printf(__('Your account is pending verification. Please click the link in your email to activate your account. No email? <a href="%s">Click here</a> to send a new activation code.', 'clasify-classified-listing'), $url); ?>
</p>