$(document).ready(function () {
    let scale = 1;
    let zoomed = false;

    $('.zoom-container').on('click', function () {
        if (!zoomed) {
            scale = 2; // Change scale for zoom effect
            zoomed = true;
            $(this).css('cursor', 'zoom-out'); // Change cursor to zoom out
        } else {
            scale = 1; // Reset scale
            zoomed = false;
            $(this).css('cursor', 'zoom-in'); // Change cursor to zoom in
        }
        $('.zoom-image').css('transform', `scale(${scale})`);
    });

    $('.zoom-container').on('mousemove', function (e) {
        if (zoomed) {
            const rect = $(this)[0].getBoundingClientRect();
            const x = e.clientX - rect.left; // Get mouse X position
            const y = e.clientY - rect.top; // Get mouse Y position

            // Set background position to simulate zooming
            $('.zoom-image').css('transform-origin', `${(x / rect.width) * 100}% ${(y / rect.height) * 100}%`);
        }
    });
});
