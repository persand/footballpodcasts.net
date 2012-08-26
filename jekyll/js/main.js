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
   * Custom lazy load
   */
  $('.podcast').hover(function() {
    var src = $('img', this).attr('data-original');
    $('img', this).attr('src', src);
  });
});
