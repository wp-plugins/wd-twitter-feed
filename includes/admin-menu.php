<?php
/*--------------------------------------------------*/
/* Options page
/*
/* This function adds a options page. It will show as 
/* a sub category under the settings tab in the admin panel.
/*--------------------------------------------------*/

// Hook the options page to initiation
add_action( 'admin_menu', 'twitter_feed_plugin_menu' );

// Add the options page to the settings menu
function twitter_feed_plugin_menu() {
	add_options_page( 'Twitter Feed Tokens', 'Twitter Feed Tokens', 'manage_options', 'wd_twitter_feed', 'twitter_feed_plugin_options' );
}

// The options page
function twitter_feed_plugin_options() {

    //must check that the user has the required capability 
    if (!current_user_can('manage_options')) {
		wp_die( __('You do not have sufficient permissions to access this page.') );
    }

    // variables for the field and option names 
    $tokens = get_option( 'twitterFeedTokens' );
    $hidden_field_name = 'wdtf_submit_hidden';
    $_slug = 'slug';

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value
        $new_tokens = array(
        	'oauth_access_token' => $_POST[ 'oauth_access_token' ],
        	'oauth_access_token_secret' => $_POST[ 'oauth_access_token_secret' ],
        	'consumer_key' => $_POST[ 'consumer_key' ],
        	'consumer_secret' => $_POST[ 'consumer_secret' ]
        );

        // Save the posted value in the database
        update_option( 'twitterFeedTokens', $new_tokens );

        // Put a 'settings updated' message on the screen
        $updated = true;
        
        // Show the new tokens in the form
        $tokens = $new_tokens;
        
	} ?>

<div class="wrap">
	<div class="icon32" id="icon-ms-admin"><br/></div>
    <h2><?php _e( 'Twitter Access Tokens', 'menu-test' ); ?></h2>
    
    <?php // Show the success message
    if($updated) { ?>
    	<div id="setting-error-settings_updated" class="updated settings-error">
			<p><strong><?php _e('settings saved.', 'menu-test' ); ?></strong></p>
		</div>
	<?php } ?>
	
	<form name="form1" method="post" action="">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="oauth_access_token"><?php _e("oAuth Access Token:", $_slug ); ?></label></th>
					<td><input name="oauth_access_token" type="text" id="oauth_access_token" class="regular-text" value="<?php echo $tokens['oauth_access_token']; ?>"></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="oauth_access_token_secret"><?php _e("oAuth Access Token Secret:", $_slug ); ?></label></th>
					<td><input name="oauth_access_token_secret" type="text" id="oauth_access_token_secret" class="regular-text" value="<?php echo $tokens['oauth_access_token_secret']; ?>"></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="consumer_key"><?php _e("Consumer Key:", $_slug ); ?></label></th>
					<td><input name="consumer_key" type="text" id="consumer_key" class="regular-text" value="<?php echo $tokens['consumer_key']; ?>"></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="consumer_secret"><?php _e("Consumer Secret:", $_slug ); ?></label></th>
					<td><input name="consumer_secret" type="text" id="consumer_secret" class="regular-text" value="<?php echo $tokens['consumer_secret']; ?>"></td>
				</tr>
			</tbody>
		</table>
		
		<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
		
		<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>

	</form>
</div>

<?php
 
} ?>