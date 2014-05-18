<!-- Static Tweets -->
<div class="<?php echo $class.' '.$skin; ?> wrapper">
	<div class="<?php echo $class.' '.$skin; ?> inner-wrapper">
		<?php foreach ($tweets as $tweet): extract($tweet); ?>
			<?php include('TweetView.php'); ?>
		<? endforeach; ?>
	</div>
</div>
<!-- End Static Tweets -->