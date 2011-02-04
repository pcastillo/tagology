
/*
 * only show the tools menu for each bookmark when hovering over it's entry
 */
jQuery(document).ready(function($)
{
  $('.savorypost').hover(
    function() {
      $(this).find('.tools').fadeIn(500);     
    },
    function() {
      $(this).find('.tools').hide();     
    }
  );
});
