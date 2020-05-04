<?php

/**
 * @var string $nid
 * @var string $external_profile
 * @var string $last_name
 * @var string $last_name_ph
 * @var string $position_title
 * @var string $position_title_ph
 * @var string $affiliation
 * @var string $affiliation_ph
 * @var string $office
 * @var string $office_ph
 * @var string $phone
 * @var string $phone_ph
 * @var string $website
 * @var string $website_ph
 * @var string $email
 * @var string $email_ph
 * @var string $physical_address
 * @var string $physical_address_ph
 * @var string $physical_address_1
 * @var string $physical_address_1_ph
 * @var string $physical_address_2
 * @var string $physical_address_2_ph
 * @var string $physical_address_city
 * @var string $physical_address_city_ph
 * @var string $physical_address_state
 * @var string $physical_address_state_ph
 * @var string $physical_address_zip
 * @var string $physical_address_zip_ph
 * @var string $mailing_address
 * @var string $mailing_address_ph
 * @var string $mailing_address_2
 * @var string $mailing_address_2_ph
 * @var string $mailing_address_city
 * @var string $mailing_address_city_ph
 * @var string $mailing_address_state
 * @var string $mailing_address_state_ph
 * @var string $mailing_address_zip
 * @var string $mailing_address_zip_ph
 * @var string $cv_link
 * @var string $focus_area
 */
?>
<div class="cpeople-profile-editor">
	<!-- Start Profile Type Fieldset ------------------------------>
	<fieldset>
		<div class="cpeople-field cpeople-text-field">
			<label>WSU Net ID</label>
			<input type="text" name="_wsuwp_profile_nid" value="<?php echo esc_attr( $nid ); ?>" />
		</div>
		<div class="cpeople-field cpeople-checkbox-field">
			<label>
			<input type="checkbox" name="_wsuwp_profile_external_profile" value="1" <?php checked( 1, $external_profile ); ?>/>
			Non-WSU Profile</label>
		</div>
	</fieldset>
	<!-- End Profile Type Fieldset ------------------------------>
	<!-- Start Contact Card Fieldset ------------------------------>
	<fieldset class="cpeople-contact-card">
	<div class="cpeople-field cpeople-text-field">
			<label>Last Name (for sorting)</label>
			<input type="text" name="_wsuwp_profile_last_name" value="<?php echo esc_attr( $last_name ); ?>" placeholder="<?php echo esc_attr( $last_name_ph ); ?>" />
		</div>
		<div class="cpeople-field cpeople-text-field">
			<label>Position Title</label>
			<input type="text" name="_wsuwp_profile_position_title" value="<?php echo esc_attr( $position_title ); ?>" placeholder="<?php echo esc_attr( $position_title_ph ); ?>" />
		</div>
		<div class="cpeople-field cpeople-text-field">
			<label>Affiliation</label>
			<input type="text" name="_wsuwp_profile_affiliation" value="<?php echo esc_attr( $affiliation ); ?>" placeholder="<?php echo esc_attr( $affiliation_ph ); ?>" />
		</div>
		<div class="cpeople-field cpeople-text-field">
			<label>Office</label>
			<input type="text" name="_wsuwp_profile_office" value="<?php echo esc_attr( $office ); ?>" placeholder="<?php echo esc_attr( $office_ph ); ?>" />
		</div>
		<div class="cpeople-field cpeople-text-field">
			<label>Phone</label>
			<input type="text" name="_wsuwp_profile_phone" value="<?php echo esc_attr( $phone ); ?>" placeholder="<?php echo esc_attr( $phone_ph ); ?>" />
		</div>
		<div class="cpeople-field cpeople-text-field">
			<label>Email</label>
			<input type="text" name="_wsuwp_profile_email" value="<?php echo esc_attr( $email ); ?>" placeholder="<?php echo esc_attr( $email_ph ); ?>" />
		</div>
		<div class="cpeople-field cpeople-text-field">
			<label>Website</label>
			<input type="text" name="_wsuwp_profile_website" value="<?php echo esc_attr( $website ); ?>" placeholder="<?php echo esc_attr( $website_ph ); ?>" />
		</div>
		<div class="cpeople-field cpeople-text-field">
			<label>Address</label>
			<input type="text" name="_wsuwp_profile_physical_address[line_1]" value="<?php echo esc_attr( $physical_address_1 ); ?>" placeholder="<?php echo esc_attr( $physical_address_1_ph ); ?>" />
		</div>
		<div class="cpeople-field cpeople-text-field">
			<label>Address Line 2</label>
			<input type="text" name="_wsuwp_profile_physical_address[line_2]" value="<?php echo esc_attr( $physical_address_2 ); ?>" placeholder="<?php echo esc_attr( $physical_address_2_ph ); ?>" />
		</div>
		<div class="cpeople-field cpeople-text-field">
			<label>City</label>
			<input type="text" name="_wsuwp_profile_physical_address[city]" value="<?php echo esc_attr( $physical_address_city ); ?>" placeholder="<?php echo esc_attr( $physical_address_city_ph ); ?>" />
		</div>
		<div class="cpeople-field cpeople-text-field">
			<label>State</label>
			<input type="text" name="_wsuwp_profile_physical_address[state]" value="<?php echo esc_attr( $physical_address_state ); ?>" placeholder="<?php echo esc_attr( $physical_address_state_ph ); ?>" />
		</div>
		<div class="cpeople-field cpeople-text-field">
			<label>Zip</label>
			<input type="text" name="_wsuwp_profile_physical_address[zip]" value="<?php echo esc_attr( $physical_address_zip ); ?>" placeholder="<?php echo esc_attr( $physical_address_zip_ph ); ?>" />
		</div>
		<div class="cpeople-field cpeople-text-field">
			<label>Address</label>
			<input type="text" name="_wsuwp_profile_mailing_address[line_1]" value="<?php echo esc_attr( $mailing_address_1 ); ?>" placeholder="<?php echo esc_attr( $mailing_address_1_ph ); ?>" />
		</div>
		<div class="cpeople-field cpeople-text-field">
			<label>Address Line 2</label>
			<input type="text" name="_wsuwp_profile_mailing_address[line_2]" value="<?php echo esc_attr( $mailing_address_2 ); ?>" placeholder="<?php echo esc_attr( $mailing_address_2_ph ); ?>" />
		</div>
		<div class="cpeople-field cpeople-text-field">
			<label>City</label>
			<input type="text" name="_wsuwp_profile_mailing_address[city]" value="<?php echo esc_attr( $mailing_address_city ); ?>" placeholder="<?php echo esc_attr( $mailing_address_city_ph ); ?>" />
		</div>
		<div class="cpeople-field cpeople-text-field">
			<label>State</label>
			<input type="text" name="_wsuwp_profile_mailing_address[state]" value="<?php echo esc_attr( $mailing_address_state ); ?>" placeholder="<?php echo esc_attr( $mailing_address_state_ph ); ?>" />
		</div>
		<div class="cpeople-field cpeople-text-field">
			<label>Zip</label>
			<input type="text" name="_wsuwp_profile_mailing_address[zip]" value="<?php echo esc_attr( $mailing_address_zip ); ?>" placeholder="<?php echo esc_attr( $mailing_address_zip_ph ); ?>" />
		</div>
	</fieldset>
	<!-- End Contact Card Fieldset ------------------------------>
	<!-- Start Education Fieldset ------------------------------>
	<fieldset class="cpeople-education">
		<div class="cpeople-field cpeople-wp-editor-field">
			<label>Degrees</label>
			<input type="text" name="_wsuwp_profile_degrees" value="" placeholder="" />
		</div>
		<div class="cpeople-field cpeople-text-field">
			<label>CV/Resume (link)</label>
			<input type="text" name="_wsuwp_profile_cv" value="<?php echo esc_attr( $cv_link ); ?>" placeholder="<?php echo esc_attr( $cv_link_ph ); ?>" />
		</div>
	</fieldset>
	<!-- End Education Fieldset ------------------------------>
	<!-- Start Summary Fieldset ------------------------------>
	<fieldset class="cpeople-summary">
		<div class="cpeople-field cpeople-wp-editor-field">
			<label class="cpeople-editor-title">Research Interests/Position Duties Summary</label>
			<?php wp_editor( $focus_area, '_wsuwp_profile_focus_area', array( 'editor_height' => '200px' ) ); ?>
		</div>
	</fieldset>
	<!-- End Summary Fieldset ------------------------------>
	<label class="cpeople-editor-title">Bio</label>
</div>
