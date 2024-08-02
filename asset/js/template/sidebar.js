$(document).ready(function () {
    var currentPath = window.location.pathname;
    $('.nav-link').each(function() {
        if ($(this).attr('href') === currentPath) {
            $(this).addClass('active');
        }
    });

    function updateBadge() {
        var itemCount = $('.dropdown-menu a.dropdown-item').length;
        $('.navbar-badge').text(itemCount);
    }

    updateBadge();
});

