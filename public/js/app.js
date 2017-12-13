$(document).ready(function() {
    setupPostLinks();
});

function setupPostLinks() {
    $('.post').click(function(e) {
        href = $(this).attr('href');
        hrefArr = href.split('/');
        id = hrefArr[hrefArr.length - 1];
        action = href.substring(0, href.length - (id.length + 1));

        $.post(action, { 'id': id });
    });
}
