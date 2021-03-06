<?php
/**
 * This file is used to markup the public facing aspect of the plugin.
 */

// If called from Frontpage Edit link we get a post_id
if (isset($_GET["post_id"])) { 
	$my_post = get_post(htmlspecialchars($_GET["post_id"]));
} else {
	$my_post = '';
}

// Set editor (content field) style
switch($djd_options['djd-editor-style']){
	case 'simple':
		$teeny = true;
		$show_quicktags = false;
		add_filter( 'teeny_mce_buttons', create_function ( '' , "return array('');" ) , 50 );
		break;
	case 'rich':
		$teeny = false;
		$show_quicktags = true;
		break;
	case 'visual':
		$teeny = false;
		$show_quicktags = false;
		break;
	case 'html':
		$teeny = true;
		$show_quicktags = true;
		add_filter ( 'user_can_richedit' , create_function ( '' , 'return false;' ) , 50 );
		break;
}

if ($called_from_widget == '1') {
	$teeny = true;
	$show_quicktags = false;
	add_filter( 'teeny_mce_buttons', create_function ( '' , "return array('');" ) , 50 );
//	add_filter ( 'user_can_richedit' , create_function ( '' , 'return false;' ) , 50 );
}

function myplugin_tinymce_buttons_2($buttons)
 {
	//Remove the format dropdown select and text color selector
	$remove = array('formatselect','forecolor', 'indent', 'outdent', 'charmap');

	return array_diff($buttons,$remove);
 }
//add_filter('mce_buttons_2','myplugin_tinymce_buttons_2');

function myplugin_tinymce_buttons($buttons)
 {
	//Remove the format dropdown select and text color selector
	$remove = array('link','unlink', 'blockquote', 'strikethrough', 'fullscreen', 'wp_more', 'wp_adv');

	return array_diff($buttons,$remove);
 }
//add_filter('mce_buttons','myplugin_tinymce_buttons');

?>

<?php if (!isset($_POST["djd_site_post_title"])) {

//init variables
$cf = array();
$sr = false;

if (isset($_COOKIE["form_ok"])){ 
	if($_COOKIE["form_ok"] == 1 ) {
		$cf['form_ok'] = true;
		$sr = true;
	}
}


$postType = $GLOBALS['djd_post_type'];
$postTypeNameSingular= rtrim($GLOBALS['djd_post_type'], 's') . '';
$postTypeName = ucfirst($postTypeNameSingular) . ' ';  
//echo 'name: ' . $djd_options['djd-title'] . $djd_options['djd-form-name'];

?>
<form id="site_post_form" class="djd_site_post_form bordered <?php echo ($sr && $cf['form_ok']) ? ' hidden' : 'visilbe'; ?>" method="post" action="<?php echo admin_url('admin-ajax.php'); ?>" enctype="multipart/form-data">
	<p hidden="hidden" class="form_error_message"></p>
	<input type="hidden" name="djd-our-id" <?php echo ( $my_post ? "value='".$my_post->ID."'" : "value='".$djd_post_id."'" ); ?> />
	<input type="hidden" name="djd-our-post-type" <?php  echo "value='" . $GLOBALS['djd_post_type']. "'" ?> />
	<input type="hidden" name="djd-our-post-taxonomy" <?php  echo "value='" . $GLOBALS['djd_post_type_taxonomy']. "'" ?> />
	<input type="hidden" name="djd-our-post-term" <?php  echo "value='" . $GLOBALS['djd_post_type_term']. "'" ?> />
	<input type="hidden" name="djd-our-author" <?php if ( $my_post ) echo "value='".$my_post->post_author."'"; ?> />
	
	<?php if (isset($djd_options['djd-login-link'])) { ?>
		<a style="float: right;" href="<?php echo wp_login_url( get_permalink() ); ?>" title="Login">Login</a>
	<?php } ?>
	<div id="field-wrapper">
		<h4><?php echo $GLOBALS['dynamic_post_title'] ?></h4>
		<!-- <legend><?php //echo ( $djd_options['djd-form-name'] ? $djd_options['djd-form-name'] : __('Frontend Post', 'djd-site-post') ); ?></legend> -->
		<label for="djd_site_post_title"><?php echo $postTypeName . ( $djd_options['djd-title'] ? $djd_options['djd-title'] : __('Title', 'djd-site-post') ); ?></label>
		<input type="text" <?php if ( $djd_options['djd-title-required'] == "1" ) echo "required='required'"; ?> id="djd_site_post_title" name="djd_site_post_title" maxlength="120" <?php if ( $my_post ) echo "value='".$my_post->post_title."'"; ?>/>
		
		<div class="row">
			<div class="col-xs-12">
			<!--<label for="djdsitepostcontent"><?php //echo $postTypeName . ( $djd_options['djd-content'] ? $djd_options['djd-content'] : __('Text', 'djd-site-post') ); ?> (180 character limit)</label>-->
			
			<?php
				//$content = '<a href="#" class="meta_field_upload_image_button button">Upload image</a>';
				//$display = 'none';
				?>
				<!-- <div>
					<?php //echo $content; ?>
					<input type="hidden" class="widefat" name="imageSlide[]"  />
					<a href="#" class="meta_field_remove_image_button button" style="display:<?php //echo $display; ?>; margin-top:14px;">Remove Image</a>
				</div> -->
			<?php
			$settings = array(
				'media_buttons'	=> true, //(boolean) $djd_options['djd-allow-media-upload'],
				'teeny'			=> false, 
				'wpautop'		=> false,
				'tinymce'		=> false,
				'quicktags'		=> false
								
				// 'teeny'			=> $teeny,
	 			// 'wpautop'		=> true,
	 			// 'quicktags'		=> $show_quicktags
			);
			$editor_content = '';
			if ( $my_post ) $editor_content = $my_post->post_content;
			//wp_editor($editor_content, 'djdsitepostcontent', $settings );
			?>
			
			<?php if (isset($djd_options['djd-show-excerpt'])) { ?>
				<label for="djd_site_post_excerpt"><?php echo ( $djd_options['djd-excerpt'] ? $djd_options['djd-excerpt'] : __('Excerpt', 'djd-site-post') ); ?> (180 character limit)</label>
				<textarea  maxlength="180" style="width:100%;" id="djd_site_post_excerpt" name="djd_site_post_excerpt"><?php if ( $my_post ) echo $my_post->post_excerpt; ?></textarea>
			<?php } ?>
			</div>
		</div>
		<?php                               //Shared Fields
		if($postType  == 'events' || $postType  == 'jobs'){ ?>    
			<!-- ADDRESS -->
		
		<!-- City -->
		<div class="row">
			<div class="col-xs-12">
				<label for="djd_site_post_city">City<?php //echo ( $djd_options['djd-city'] ? $djd_options['djd-city'] : __('City', 'djd-site-post') ); ?></label>
				<input type="text" <?php echo "required='required'"; ?> id="djd_site_post_city" name="djd_site_post_city" maxlength="255" value="" <?php //if ( $my_post ) echo "value='".$my_post->post_city."'"; ?>autofocus="autofocus"/>
			</div>

		<!-- State -->
			<div class="col-xs-12 col-sm-6">
				<label for="djd_site_post_state">State<?php //echo ( $djd_options['djd-state'] ? $djd_options['djd-state'] : __('State', 'djd-site-post') ); ?></label>
				<select name="djd_site_post_state" id="djd_site_post_state" style="width:100%;">
					<option> - Select Province/State - </option>
					<option value="None">Not Applicable</option> 
					<option value="AL">Alabama</option> 
					<option value="AK">Alaska</option> 
					<option value="AZ">Arizona</option> 
					<option value="AR">Arkansas</option> 
					<option value="CA">California</option> 
					<option value="CO">Colorado</option> 
					<option value="CT">Connecticut</option> 
					<option value="DE">Delaware</option> 
					<option value="DC">District Of Columbia</option> 
					<option value="FL">Florida</option> 
					<option value="GA">Georgia</option> 
					<option value="HI">Hawaii</option> 
					<option value="ID">Idaho</option> 
					<option value="IL">Illinois</option> 
					<option value="IN">Indiana</option> 
					<option value="IA">Iowa</option> 
					<option value="KS">Kansas</option> 
					<option value="KY">Kentucky</option> 
					<option value="LA">Louisiana</option> 
					<option value="ME">Maine</option> 
					<option value="MD">Maryland</option> 
					<option value="MA">Massachusetts</option> 
					<option value="MI">Michigan</option> 
					<option value="MN">Minnesota</option> 
					<option value="MS">Mississippi</option> 
					<option value="MO">Missouri</option> 
					<option value="MT">Montana</option> 
					<option value="NE">Nebraska</option> 
					<option value="NV">Nevada</option> 
					<option value="NH">New Hampshire</option> 
					<option value="NJ">New Jersey</option> 
					<option value="NM">New Mexico</option> 
					<option value="NY">New York</option> 
					<option value="NC">North Carolina</option> 
					<option value="ND">North Dakota</option> 
					<option value="OH">Ohio</option> 
					<option value="OK">Oklahoma</option> 
					<option value="OR">Oregon</option> 
					<option value="PA">Pennsylvania</option> 
					<option value="RI">Rhode Island</option> 
					<option value="SC">South Carolina</option> 
					<option value="SD">South Dakota</option> 
					<option value="TN">Tennessee</option> 
					<option value="TX">Texas</option> 
					<option value="UT">Utah</option> 
					<option value="VT">Vermont</option> 
					<option value="VA">Virginia</option> 
					<option value="WA">Washington</option> 
					<option value="WV">West Virginia</option> 
					<option value="WI">Wisconsin</option> 
					<option value="WY">Wyoming</option>
					<option> ---------------- </option>
					<option value="AB">Alberta</option>
					<option value="BC">British Columbia</option>
					<option value="MB">Manitoba</option>
					<option value="NB">New Brunswick</option>
					<option value="NL">Newfoundland and Labrador</option>
					<option value="NS">Nova Scotia</option>
					<option value="NT">Northwest Territories</option>
					<option value="NU">Nunavut</option>
					<option value="ON">Ontario</option>
					<option value="PE">Prince Edward Island</option>
					<option value="QC">Quebec</option>
					<option value="SK">Saskatchewan</option>
					<option value="YT">Yukon</option>
				</select>
			</div>
			
			<!-- Country -->
			<div class="col-xs-12 col-sm-6">
				<label for="djd_site_post_country">Country<?php //echo ( $djd_options['djd-country'] ? $djd_options['djd-country'] : __('Country', 'djd-site-post') ); ?></label>
				<select name="djd_site_post_country" id="djd_site_post_country" style="width:100%;">
					<option value="US">United States</option>
					<option value="AF">Afghanistan</option>
					<option value="AX">Åland Islands</option>
					<option value="AL">Albania</option>
					<option value="DZ">Algeria</option>
					<option value="AS">American Samoa</option>
					<option value="AD">Andorra</option>
					<option value="AO">Angola</option>
					<option value="AI">Anguilla</option>
					<option value="AQ">Antarctica</option>
					<option value="AG">Antigua and Barbuda</option>
					<option value="AR">Argentina</option>
					<option value="AM">Armenia</option>
					<option value="AW">Aruba</option>
					<option value="AU">Australia</option>
					<option value="AT">Austria</option>
					<option value="AZ">Azerbaijan</option>
					<option value="BS">Bahamas</option>
					<option value="BH">Bahrain</option>
					<option value="BD">Bangladesh</option>
					<option value="BB">Barbados</option>
					<option value="BY">Belarus</option>
					<option value="BE">Belgium</option>
					<option value="BZ">Belize</option>
					<option value="BJ">Benin</option>
					<option value="BM">Bermuda</option>
					<option value="BT">Bhutan</option>
					<option value="BO">Bolivia, Plurinational State of</option>
					<option value="BQ">Bonaire, Sint Eustatius and Saba</option>
					<option value="BA">Bosnia and Herzegovina</option>
					<option value="BW">Botswana</option>
					<option value="BV">Bouvet Island</option>
					<option value="BR">Brazil</option>
					<option value="IO">British Indian Ocean Territory</option>
					<option value="BN">Brunei Darussalam</option>
					<option value="BG">Bulgaria</option>
					<option value="BF">Burkina Faso</option>
					<option value="BI">Burundi</option>
					<option value="KH">Cambodia</option>
					<option value="CM">Cameroon</option>
					<option value="CA">Canada</option>
					<option value="CV">Cape Verde</option>
					<option value="KY">Cayman Islands</option>
					<option value="CF">Central African Republic</option>
					<option value="TD">Chad</option>
					<option value="CL">Chile</option>
					<option value="CN">China</option>
					<option value="CX">Christmas Island</option>
					<option value="CC">Cocos (Keeling) Islands</option>
					<option value="CO">Colombia</option>
					<option value="KM">Comoros</option>
					<option value="CG">Congo</option>
					<option value="CD">Congo, the Democratic Republic of the</option>
					<option value="CK">Cook Islands</option>
					<option value="CR">Costa Rica</option>
					<option value="CI">Côte d'Ivoire</option>
					<option value="HR">Croatia</option>
					<option value="CU">Cuba</option>
					<option value="CW">Curaçao</option>
					<option value="CY">Cyprus</option>
					<option value="CZ">Czech Republic</option>
					<option value="DK">Denmark</option>
					<option value="DJ">Djibouti</option>
					<option value="DM">Dominica</option>
					<option value="DO">Dominican Republic</option>
					<option value="EC">Ecuador</option>
					<option value="EG">Egypt</option>
					<option value="SV">El Salvador</option>
					<option value="GQ">Equatorial Guinea</option>
					<option value="ER">Eritrea</option>
					<option value="EE">Estonia</option>
					<option value="ET">Ethiopia</option>
					<option value="FK">Falkland Islands (Malvinas)</option>
					<option value="FO">Faroe Islands</option>
					<option value="FJ">Fiji</option>
					<option value="FI">Finland</option>
					<option value="FR">France</option>
					<option value="GF">French Guiana</option>
					<option value="PF">French Polynesia</option>
					<option value="TF">French Southern Territories</option>
					<option value="GA">Gabon</option>
					<option value="GM">Gambia</option>
					<option value="GE">Georgia</option>
					<option value="DE">Germany</option>
					<option value="GH">Ghana</option>
					<option value="GI">Gibraltar</option>
					<option value="GR">Greece</option>
					<option value="GL">Greenland</option>
					<option value="GD">Grenada</option>
					<option value="GP">Guadeloupe</option>
					<option value="GU">Guam</option>
					<option value="GT">Guatemala</option>
					<option value="GG">Guernsey</option>
					<option value="GN">Guinea</option>
					<option value="GW">Guinea-Bissau</option>
					<option value="GY">Guyana</option>
					<option value="HT">Haiti</option>
					<option value="HM">Heard Island and McDonald Islands</option>
					<option value="VA">Holy See (Vatican City State)</option>
					<option value="HN">Honduras</option>
					<option value="HK">Hong Kong</option>
					<option value="HU">Hungary</option>
					<option value="IS">Iceland</option>
					<option value="IN">India</option>
					<option value="ID">Indonesia</option>
					<option value="IR">Iran, Islamic Republic of</option>
					<option value="IQ">Iraq</option>
					<option value="IE">Ireland</option>
					<option value="IM">Isle of Man</option>
					<option value="IL">Israel</option>
					<option value="IT">Italy</option>
					<option value="JM">Jamaica</option>
					<option value="JP">Japan</option>
					<option value="JE">Jersey</option>
					<option value="JO">Jordan</option>
					<option value="KZ">Kazakhstan</option>
					<option value="KE">Kenya</option>
					<option value="KI">Kiribati</option>
					<option value="KP">Korea, Democratic People's Republic of</option>
					<option value="KR">Korea, Republic of</option>
					<option value="KW">Kuwait</option>
					<option value="KG">Kyrgyzstan</option>
					<option value="LA">Lao People's Democratic Republic</option>
					<option value="LV">Latvia</option>
					<option value="LB">Lebanon</option>
					<option value="LS">Lesotho</option>
					<option value="LR">Liberia</option>
					<option value="LY">Libya</option>
					<option value="LI">Liechtenstein</option>
					<option value="LT">Lithuania</option>
					<option value="LU">Luxembourg</option>
					<option value="MO">Macao</option>
					<option value="MK">Macedonia, the former Yugoslav Republic of</option>
					<option value="MG">Madagascar</option>
					<option value="MW">Malawi</option>
					<option value="MY">Malaysia</option>
					<option value="MV">Maldives</option>
					<option value="ML">Mali</option>
					<option value="MT">Malta</option>
					<option value="MH">Marshall Islands</option>
					<option value="MQ">Martinique</option>
					<option value="MR">Mauritania</option>
					<option value="MU">Mauritius</option>
					<option value="YT">Mayotte</option>
					<option value="MX">Mexico</option>
					<option value="FM">Micronesia, Federated States of</option>
					<option value="MD">Moldova, Republic of</option>
					<option value="MC">Monaco</option>
					<option value="MN">Mongolia</option>
					<option value="ME">Montenegro</option>
					<option value="MS">Montserrat</option>
					<option value="MA">Morocco</option>
					<option value="MZ">Mozambique</option>
					<option value="MM">Myanmar</option>
					<option value="NA">Namibia</option>
					<option value="NR">Nauru</option>
					<option value="NP">Nepal</option>
					<option value="NL">Netherlands</option>
					<option value="NC">New Caledonia</option>
					<option value="NZ">New Zealand</option>
					<option value="NI">Nicaragua</option>
					<option value="NE">Niger</option>
					<option value="NG">Nigeria</option>
					<option value="NU">Niue</option>
					<option value="NF">Norfolk Island</option>
					<option value="MP">Northern Mariana Islands</option>
					<option value="NO">Norway</option>
					<option value="OM">Oman</option>
					<option value="PK">Pakistan</option>
					<option value="PW">Palau</option>
					<option value="PS">Palestinian Territory, Occupied</option>
					<option value="PA">Panama</option>
					<option value="PG">Papua New Guinea</option>
					<option value="PY">Paraguay</option>
					<option value="PE">Peru</option>
					<option value="PH">Philippines</option>
					<option value="PN">Pitcairn</option>
					<option value="PL">Poland</option>
					<option value="PT">Portugal</option>
					<option value="PR">Puerto Rico</option>
					<option value="QA">Qatar</option>
					<option value="RE">Réunion</option>
					<option value="RO">Romania</option>
					<option value="RU">Russian Federation</option>
					<option value="RW">Rwanda</option>
					<option value="BL">Saint Barthélemy</option>
					<option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
					<option value="KN">Saint Kitts and Nevis</option>
					<option value="LC">Saint Lucia</option>
					<option value="MF">Saint Martin (French part)</option>
					<option value="PM">Saint Pierre and Miquelon</option>
					<option value="VC">Saint Vincent and the Grenadines</option>
					<option value="WS">Samoa</option>
					<option value="SM">San Marino</option>
					<option value="ST">Sao Tome and Principe</option>
					<option value="SA">Saudi Arabia</option>
					<option value="SN">Senegal</option>
					<option value="RS">Serbia</option>
					<option value="SC">Seychelles</option>
					<option value="SL">Sierra Leone</option>
					<option value="SG">Singapore</option>
					<option value="SX">Sint Maarten (Dutch part)</option>
					<option value="SK">Slovakia</option>
					<option value="SI">Slovenia</option>
					<option value="SB">Solomon Islands</option>
					<option value="SO">Somalia</option>
					<option value="ZA">South Africa</option>
					<option value="GS">South Georgia and the South Sandwich Islands</option>
					<option value="SS">South Sudan</option>
					<option value="ES">Spain</option>
					<option value="LK">Sri Lanka</option>
					<option value="SD">Sudan</option>
					<option value="SR">Suriname</option>
					<option value="SJ">Svalbard and Jan Mayen</option>
					<option value="SZ">Swaziland</option>
					<option value="SE">Sweden</option>
					<option value="CH">Switzerland</option>
					<option value="SY">Syrian Arab Republic</option>
					<option value="TW">Taiwan, Province of China</option>
					<option value="TJ">Tajikistan</option>
					<option value="TZ">Tanzania, United Republic of</option>
					<option value="TH">Thailand</option>
					<option value="TL">Timor-Leste</option>
					<option value="TG">Togo</option>
					<option value="TK">Tokelau</option>
					<option value="TO">Tonga</option>
					<option value="TT">Trinidad and Tobago</option>
					<option value="TN">Tunisia</option>
					<option value="TR">Turkey</option>
					<option value="TM">Turkmenistan</option>
					<option value="TC">Turks and Caicos Islands</option>
					<option value="TV">Tuvalu</option>
					<option value="UG">Uganda</option>
					<option value="UA">Ukraine</option>
					<option value="AE">United Arab Emirates</option>
					<option value="GB">United Kingdom</option>
					<option value="US">United States</option>
					<option value="UM">United States Minor Outlying Islands</option>
					<option value="UY">Uruguay</option>
					<option value="UZ">Uzbekistan</option>
					<option value="VU">Vanuatu</option>
					<option value="VE">Venezuela, Bolivarian Republic of</option>
					<option value="VN">Viet Nam</option>
					<option value="VG">Virgin Islands, British</option>
					<option value="VI">Virgin Islands, U.S.</option>
					<option value="WF">Wallis and Futuna</option>
					<option value="EH">Western Sahara</option>
					<option value="YE">Yemen</option>
					<option value="ZM">Zambia</option>
					<option value="ZW">Zimbabwe</option>
				</select>
			</div>
		
		<?php } ?>
		
		<?php                               //Events Fields
		if($postType  == 'events'){ ?> 
		
		<!-- START DATE -->
		
			<div class="col-xs-12 col-sm-6">
				<label for="dsp_event_venue">Event Venue</label>
				<input style="width:100%;" type="text" <?php  echo "required='required'"; ?> id="dsp_event_venue" name="dsp_event_venue" maxlength="255" <?php if ( $my_post ) echo "value='".$my_post->post_start_date."'"; ?>autofocus="autofocus"/>
			</div>
			<div class="col-xs-12 col-sm-6">
				<label for="dsp_event_link">Event Link (*Must be valid URL. Example: http://www.job.com/)</label>
				<input style="width:100%;" type="url" <?php  echo "required='required'"; ?> id="dsp_event_link" name="dsp_event_link" maxlength="255" <?php if ( $my_post ) echo "value='".$my_post->post_event_link."'"; ?>autofocus="autofocus"/>
			</div>	
			<div class="col-xs-12 col-sm-6">
				<label for="dsp_event_start_date">Start Date</label>
				<input style="width:100%;" type="date" <?php  echo "required='required'"; ?> id="dsp_event_start_date" name="dsp_event_start_date" maxlength="255" <?php if ( $my_post ) echo "value='".$my_post->post_start_date."'"; ?>autofocus="autofocus"/>
			</div>
			
		
			<div class="col-xs-12 col-sm-6">
				<label for="dsp_event_end_date">End Date </label>
				<input style="width:100%;" type="date" <?php echo "required='required'"; ?> id="dsp_event_end_date" name="dsp_event_end_date" maxlength="255" <?php if ( $my_post ) echo "value='".$my_post->post_end_date."'"; ?>autofocus="autofocus"/>
			</div>
			<!-- END DATE -->
			<div class="col-xs-12 col-sm-6">
				<label for="dsp_event_name">Contact Name</label>
				<input style="width:100%;" type="text" <?php  echo "required='required'"; ?> id="dsp_event_name" name="dsp_event_name" maxlength="255" <?php if ( $my_post ) echo "value='".$my_post->post_event_contact_name."'"; ?>autofocus="autofocus"/>
			</div>
			<div class="col-xs-12 col-sm-6">
				<label for="dsp_event_email">Contact Email</label>
				<input style="width:100%;" type="text" <?php  echo "required='required'"; ?> id="dsp_event_email" name="dsp_event_email" maxlength="255" <?php if ( $my_post ) echo "value='".$my_post->post_event_contact_email."'"; ?>autofocus="autofocus"/>
			</div>
		</div>

		<?php } ?>
		
		
		<?php                               //Jobs Fields
		if($postType  == 'jobs'){ ?>   
		
		<!-- <div class="row"> -->
			<div class="col-xs-12 col-sm-6">
				<label for="djd_site_company_name">Company Name</label>
				<input style="width:100%;" type="text" <?php  echo "required='required'"; ?> id="djd_site_company_name" name="djd_site_company_name" maxlength="255" <?php if ( $my_post ) echo "value='".$my_post->post_company_name."'"; ?>autofocus="autofocus"/>
			</div>
			<div class="col-xs-12 col-sm-6">
				<label for="djd_site_website">Company Website</label>
				<input style="width:100%;" type="text" <?php  echo "required='required'"; ?> id="djd_site_website" name="djd_site_website" maxlength="255" <?php if ( $my_post ) echo "value='".$my_post->post_company_phone."'"; ?>autofocus="autofocus"/>
			</div>
		<!-- </div>
		<div class="row"> -->
			<div class="col-xs-12 col-sm-6">
				<label for="djd_site_company_contact">Company Contact</label>
				<input style="width:100%;" type="text" <?php  echo "required='required'"; ?> id="djd_site_company_contact" name="djd_site_company_contact" maxlength="255" <?php if ( $my_post ) echo "value='".$my_post->post_company_contact."'"; ?>autofocus="autofocus"/>
			</div>
			<div class="col-xs-12 col-sm-6">
				<label for="djd_site_phone">Contact Phone Number</label>
				<input style="width:100%;" type="text" <?php  echo "required='required'"; ?> id="djd_site_phone" name="djd_site_phone" maxlength="255" <?php if ( $my_post ) echo "value='".$my_post->post_company_phone."'"; ?>autofocus="autofocus"/>
			</div>
		<!-- </div>
		<div class="row"> -->
			<div class="col-xs-12 col-sm-6">
				<label for="djd_site_email">Contact Email</label>
				<input style="width:100%;" type="email" <?php echo "required='required'"; ?> id="djd_site_email" name="djd_site_email" maxlength="255" <?php if ( $my_post ) echo "value='".$my_post->post_email."'"; ?>autofocus="autofocus"/>
			</div>
			<div class="col-xs-12 col-sm-6">
				<label for="djd_site_post_link"><?php echo $postTypeName ?> Link (URL)</label>
				<input style="width:100%;" type="text" <?php echo "required='required'"; ?> id="djd_site_posting_link" name="djd_site_posting_link" maxlength="255" <?php if ( $my_post ) echo "value='".$my_post->post_link."'"; ?>autofocus="autofocus"/>
			</div>
		</div>
		<?php } ?>
		<?php
		if ( (isset($djd_options['djd-guest-info'])) && (!is_user_logged_in()) ){ ?>
			<label for="djd_site_post_guest_name"><?php _e('Your Name', 'djd-site-post'); ?></label>
			<input type="text" required="required" id="djd_site_post_guest_name" name="djd_site_post_guest_name" maxlength="40" />

			<label for="djd_site_post_guest_email"><?php _e('Your Email', 'djd-site-post'); ?></label>
			<input type="email" required="required" id="djd_site_post_guest_email" name="djd_site_post_guest_email" maxlength="40" /><br><br>
		<?php } ?>

	<!--<span id="loading"></span>-->
	<input type="hidden" name="action" value="process_site_post_form"/>
	<?php if ( (isset($djd_options['djd-quiz'])) && (!is_user_logged_in()) ) { ?>
		<?php $no1 = mt_rand(1, 12); $no2 = mt_rand(1, 12); ?>
		<label class="error" for="djd_quiz" id="quiz_error" style="margin: 0 0 5px 10px; display: none; color: red;"><?php _e('Wrong Quiz Answer!', 'djd-site-post'); ?></label>
		<label for="djd_quiz" id="djd_quiz_label"><?php echo $no1; ?> plus <?php echo $no2; ?> =</label>
		<input type="text" required="required" id="djd_quiz" name="djd_quiz" maxlength="2" size="2" />
		<input type="hidden" id="djd_quiz_hidden" name="djd_quiz_hidden" value="<?php echo $no1 + $no2; ?>" />
	<?php } ?>
	<label class="error" for="g-recaptcha" id="g-recaptcha_error" style="margin: 0 0 5px 10px; display: none; color: red;">Captcha is required</label>
	<div class="g-recaptcha" id="g-recaptcha" data-sitekey="6LePhU8UAAAAAGJQwZRkXsoO6cdLNS_9UVVpKaU8"></div>
	<br><br>
	<button type="submit" class="send-button" id="submit"><?php echo ( $djd_options['djd-send-button'] ? $djd_options['djd-send-button'] : __('Publish', 'djd-site-post') ); ?></button>
	<p id="error" class="<?php echo ($sr && !$cf['form_ok']) ? 'visible' : ''; ?>"><?php echo $djd_options['djd-post-fail']; ?></p>
	</div> <!-- field-wrapper -->
</form>
<div>
	<p id="success" class="<?php echo ($sr && $cf['form_ok']) ? 'visible' : ''; ?>"><?php echo $djd_options['djd-post-confirmation']; ?></p>
	<button id="refresher" type="reset" onclick="RefreshPage()" class="btn-blue btn send-button <?php echo ($sr && $cf['form_ok']) ? 'visible' : ''; ?>"><?php _e('Submit Another', 'djd-site-post'); ?></button>
</div>
<?php } ?>
<script>
	var myForm = document.getElementById("site_post_form");
	myForm.style.display = "block";
</script>
<noscript>
	<div class="noscriptmsg">
		<p><?php _e("Seems like you don't have Javascript enabled. To use this function you need to enable JavaScript.", "djd-site-post"); ?></p>
	</div>
</noscript>
<script type="text/javascript">
	jQuery('#site_post_form').on('submit', ProcessFormAjax);
</script>
<script>
	function RefreshPage(){
		var newlocation = location.href;
		location.replace( newlocation.replace(location.search, '') );
	}
</script>