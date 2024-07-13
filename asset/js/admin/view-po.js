$(document).ready(function() {
    $('#printButton').click(function() {
        var content = $('#printContent').html();
        var originalContent = $('body').html();

        $('body').html(content);

        window.print();
        $('body').html(originalContent);
    });
});