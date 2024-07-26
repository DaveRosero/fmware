$(document).ready(function () {
    // Initialize Bootstrap tooltips
    $('[data-bs-toggle="tooltip"]').tooltip({
        placement: 'right'
    });

    var currentPath = window.location.pathname;
    $('.nav-link').each(function() {
        if ($(this).attr('href') === currentPath) {
            $(this).addClass('active');
            // Expand the section if the link is inside a collapsed section
            var section = $(this).closest('.collapse');
            if (section.length) {
                section.addClass('show');
                section.prev('.nav-item').find('.nav-link').attr('aria-expanded', 'true');
                saveExpandedState(section.attr('id'));
            }
        }
    });

    // Restore expanded state from localStorage
    restoreExpandedState();

    // Save expanded state on collapse show/hide
    $('.collapse').on('shown.bs.collapse', function () {
        saveExpandedState($(this).attr('id'));
    }).on('hidden.bs.collapse', function () {
        removeExpandedState($(this).attr('id'));
    });
    
    $('#expandButton').on('click', function() {
        if (document.fullscreenElement) {
            // Exit fullscreen
            document.exitFullscreen().catch(err => console.error("Error exiting fullscreen: ", err));
        } else {
            // Enter fullscreen
            document.documentElement.requestFullscreen().catch(err => console.error("Error entering fullscreen: ", err));
        }
    });

    updateBadge();
    
    function updateBadge() {
        var itemCount = $('#notificationMenu .dropdown-item').length;
        
        if (itemCount > 0) {
            $('#notificationBadge').text(itemCount).removeClass('d-none'); // Show badge and set count
        } else {
            $('#notificationBadge').addClass('d-none'); // Hide badge
        }
    }

    function saveExpandedState(id) {
        var expandedSections = JSON.parse(localStorage.getItem('expandedSections')) || [];
        if (!expandedSections.includes(id)) {
            expandedSections.push(id);
        }
        localStorage.setItem('expandedSections', JSON.stringify(expandedSections));
    }
    
    function removeExpandedState(id) {
        var expandedSections = JSON.parse(localStorage.getItem('expandedSections')) || [];
        var index = expandedSections.indexOf(id);
        if (index !== -1) {
            expandedSections.splice(index, 1);
        }
        localStorage.setItem('expandedSections', JSON.stringify(expandedSections));
    }
    
    function restoreExpandedState() {
        var expandedSections = JSON.parse(localStorage.getItem('expandedSections')) || [];
        expandedSections.forEach(function(id) {
            var section = $('#' + id);
            section.addClass('show');
            section.prev('.nav-item').find('.nav-link').attr('aria-expanded', 'true');
        });
    }
});

