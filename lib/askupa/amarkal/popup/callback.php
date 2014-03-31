<?php
/**
 * @package   AskupaTwitterFeed
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/twitter-feed/
 * @copyright 2014 Askupa Software
 */

namespace TWITTERFEED;

/*----------------------------------------------------------------------------*\
 * This file renders the popup window using the data from the configuration
 * file
\*----------------------------------------------------------------------------*/

// Create a new class instance according to the GET request
if( isset($_GET['item']) ) {
	$item = trim($_GET['item']);
	$popup = new Popup( $item );
}
else {
	echo 'No item was defined in the GET request';
	exit;
}

?>

<!DOCTYPE html>
	<head></head>
	<body>
		
		<div id="twitter-shortcode-editor">
			
			<form action="#" id="shortcode-editor">
				<table class="form-table editor-table">
					<tbody>
						<?php $popup->show(); ?>
						<tr class="last"><th></th>
							<td><input type="submit" name="submit" id="submit" class="button button-primary" value="Insert Shortcode"></td>
						</tr>
					</tbody>
				</table>
			</form>
		</div>
	</body>
</html>