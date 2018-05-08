window.jQuery = window.$ = require('jquery');
require('bootstrap'); // bootstrap is dependent on jquery
require('select2');

$(document).ready(function() {
    setupPostLinks();
    setupSelect2();
});

/**
 * Sends post request before following links
 * with the class .post
 * Sends slug to action assuming /action[/:slug]
 *
 * @return void
 */
function setupPostLinks() {
    $('.post').click(function(e) {
        href = $(this).attr('href');
        hrefArr = href.split('/');
        slug = hrefArr[hrefArr.length - 2];
        action = href.substring(0, href.length - (slug.length + 1));

        $.post(action, { 'slug': slug });
    });

    $('.post-noslug').click(function(e) {
        $.post($(this).attr('href'));
    });
}

/**
 * Enables the .select2 jQuery exentsion
 * for forms that use it.
 *
 * @return void
 */
function setupSelect2() {
    $('.select2.single.grouptype').select2({
        placeholder: 'Please select a grouptype',
        allowClear: true,
        width: '20em'
    });
}
