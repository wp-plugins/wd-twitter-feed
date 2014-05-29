<?php
/** --------------------------------------------------------------------------*\
 * Tweet Template
\** --------------------------------------------------------------------------*/
?>
<div class="<?php echo $class.' '.$skin; ?> tweet-wrapper">

	<?php if($show_time): ?>
		<time class="<?php echo $class.' '.$skin.' '.$dir; ?> tweet-time" datetime="<?php echo $time; ?>" title="Tweet Time">
			<?php echo $time_formatted; ?>
		</time>
	<?php endif; ?>

	<div class="<?php echo $class.' '.$dir; ?> user-card">
		<?php if($show_avatar): ?>
			<img src="<?php echo $image_url; ?>" width="32" height="32" />
		<?php endif; ?>
		<div class="<?php echo $class.' '.$dir; ?> screen-name">
			<?php if($show_user_name): ?>
				<span><?php echo $user_name; ?></span><br />
			<?php endif; ?>
			<?php if($show_screen_name): ?>
				<a href="https://twitter.com/<?php echo $screen_name; ?>" target="_blank" dir="ltr">@<?php echo $screen_name; ?></a>
			<?php endif; ?>
		</div>
	</div>
	<p class="<?php echo $class.' '.$dir; ?> tweet-text"><?php echo $tweet_text; ?></p>

	<?php if($show_retweeter): ?>
		<p class="<?php echo $class.' '.$dir; ?> retweet-credits"><i class="fa fa-retweet"></i> <?php _e('Retweeted by', $slug); echo ' '.$retweeter; ?></p>
	<?php endif; ?>

	<?php if($show_media): /* Tweet media */ ?>
		<a class="twitter-feed show-media"><i class="fa fa-youtube-play"></i> <span>Show</span> Media</a>
		<div class="<?php echo $class.' '.$skin; ?> media-wrapper">
			<?php foreach ($media as $medium): extract($medium); ?>
				<?php if( 'photo' == $type ): ?>
					<img src="<?php echo $url; ?>" />
				<?php endif; ?>
				<?php if( 'vine' == $type ): ?>
				<div class="twitter-feed video-container vine">
					<iframe src="<?php echo $embed_url; ?>" frameborder="0" scrolling="no" allowtransparency="true" width="435" width="435">
					</iframe>
				</div>
				<?php endif; ?>
				<?php if( 'youtube' == $type ): ?>
				<div class="twitter-feed video-container youtube">
					<iframe width="100%" height="360" src="<?php echo $embed_url; ?>" frameborder="0" allowfullscreen>
					</iframe>
				</div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php if($show_actions): /* Tweet actions */ ?>
		<ul class="<?php echo $class.' '.$dir.' '.$skin; ?> tweet-actions">
			<li>
				<a <?php echo $tooltip_attributes ?> href="<?php echo $intent_url; ?>tweet?in_reply_to=<?php echo $tweet_id ?>" class="reply-action web-intent" title="<?php _e('reply', $slug); ?>"><i class="fa fa-reply"></i></a>
			</li>
			<li>
				<a <?php echo $tooltip_attributes ?> href="<?php echo $intent_url; ?>retweet?tweet_id=<?php echo $tweet_id ?>" class="retweet-action web-intent" title="<?php _e('retweet', $slug); ?>"><i class="fa fa-retweet"></i></a> <?php echo $retweet_count; ?>
			</li>
			<li>
				<a <?php echo $tooltip_attributes ?> href="<?php echo $intent_url; ?>favorite?tweet_id=<?php echo $tweet_id ?>" class="favorite-action web-intent" title="<?php _e('favorite', $slug); ?>"><i class="fa fa-star"></i></a> <?php echo $favorite_count; ?>
			</li>
		</ul>
	<?php endif; /* End actions */ ?>

	<?php if( 'talk-bubble-skin' == $skin ): ?>
		<i class="fa fa-twitter"></i>
	<?php endif; ?>
</div>