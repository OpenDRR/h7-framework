<?php

	do_action ( 'fw_before_text_block' );
	
?>

<div class="block-text">
	<?php the_sub_field ( 'body' ); ?>
</div>

<?php

	do_action ( 'fw_after_text_block' );

?>