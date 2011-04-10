
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
  
  // tools on bookmarks
  $('.savorypost').hover(
    function() {
      $(this).find('.tools').fadeIn(500);     
    },
    function() {
      $(this).find('.tools').hide();     
    }
  );
  
  // tools on comments
  $('.comment-meta').hover(
    function() {
      $(this).find('.tools').fadeIn(500);     
    },
    function() {
      $(this).find('.tools').hide();     
    }
  );
  
  // save link - ajaxify
  $('.savelink').click(function(e) {
    e.preventDefault();   
    var pop = $(this).parent().parent().parent().find('.pop span');
    $.get(this.href + '?_ajax=1', function (data) {
      if (0 < data) {
        var count = data;
        pop.text(count); // update screen
      }
    });
    
  });
  
  
});
