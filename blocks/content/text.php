<?php

	do_action ( 'fw_before_text_block' );
	
?>

<div class="block-text">
	<?php echo apply_filters ( 'the_content', $block['body'] ); ?>
</div>

<?php

	do_action ( 'fw_after_text_block' );

?>