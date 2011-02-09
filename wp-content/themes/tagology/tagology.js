
/*
 * only show the tools menu for each bookmark when hovering over it's entry
 */
jQuery(document).ready(function($)
{
  
  $('#loginlink').click(function(e) {    
    e.preventDefault();
    $('#loginlink').hide();
    $('#loginformwrapper').fadeIn(500);
  });
  
  $('.savorypost').hover(
    function() {
      $(this).find('.tools').fadeIn(500);     
    },
    function() {
      $(this).find('.tools').hide();     
    }
  );
});
