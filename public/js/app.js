$(document).ready(function() {
    setupPostLinks();
});

/**
 * Sends post request before following links
 * with the class .post
 * Sends id assuming /action[/:id]
 *
 * @return void
 */
function setupPostLinks() {
    $('.post').click(function(e) {
        href = $(this).attr('href');
        hrefArr = href.split('/');
        id = hrefArr[hrefArr.length - 1];
        action = href.substring(0, href.length - (id.length + 1));

        $.post(action, { 'id': id });
    });
}
