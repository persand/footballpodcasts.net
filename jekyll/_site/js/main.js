/**
 * List JS config
 */
var options = {
    listClass: 'podcasts',
    searchClass: 'search-query',
    valueNames: [ 'title', 'description' ]
};
var podcastList = new List('podcasts', options);


$(function() {
  /**
   * Toggle description on click in touch devices
   */
  $('.touch .description').click(function() {
    $(this).toggleClass('show-description');
  });

  /**
   * Lazyload podcast images
   */
  $(".podcast img").lazyload({
    effect : "fadeIn"
  });
});
