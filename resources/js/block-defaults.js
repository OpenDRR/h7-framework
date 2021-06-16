(function($) {
  $(function() {

    $(document).data('block_defaults', <?php echo json_encode ( $defaults_array ); ?>)

    console.log($(document).data('block_defaults'))

  });
})(jQuery);
