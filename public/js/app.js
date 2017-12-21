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
        slug = hrefArr[hrefArr.length - 1];
        action = href.substring(0, href.length - (slug.length + 1));

        $.post(action, { 'slug': slug });
    });
}
