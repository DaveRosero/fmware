$(document).ready(function () {
    var currentPath = window.location.pathname;
    $('.nav-link').each(function() {
        if ($(this).attr('href') === currentPath) {
            $(this).addClass('active');
        }
    });

    function updateBadge() {
        var itemCount = $('.dropdown-menu a.dropdown-item').length;
        var badge = $('.navbar-badge');
        
        if (itemCount === 0) {
            badge.remove();
        } else {
            badge.text(itemCount);
        }
    }
    
    updateBadge();    
});

