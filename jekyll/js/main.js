/**
 * List JS config
 */
var options = {
    listClass: 'podcasts',
    searchClass: 'search-query',
    valueNames: [ 'title', 'description' ]
};
var podcastList = new List('podcasts', options);

/**
 * Toggle description on click in touch devices
 */
$(function() {
  $('.touch .description').click(function() {
    $(this).toggleClass('show-description');
  });
});
