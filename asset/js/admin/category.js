$(document).ready(function(){
    $('#category-table').DataTable({
        // DataTables configuration options...
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i>',
                className: 'btn btn-secondary',
                exportOptions: {
                    columns: ':visible'
                }
            }
        ],
        initComplete: function () {
            // Move the print button inside the printButtonContainer
            var dataTableButtons = $('.dt-buttons');
            $('#printButtonContainer').append(dataTableButtons);
        }
    });

    $('#new-category').on('submit', function(event){
        event.preventDefault();

        $.ajax({
            url: '/fmware/new-category',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(feedback){
                if (feedback.redirect) {
                    window.location.href = feedback.redirect;
                } else {
                    $('#category_feedback').text(feedback.category_feedback);
                }
            }
        });
    });

    $('.status').on('click', function(){
        $.ajax({
            url: '/fmware/disable-category',
            method: 'POST',
            data: {
                id : $(this).data('category-id'),
                status : $(this).data('category-status')
            },
            dataType: 'json', 
            success: function(feedback){
                if (feedback.redirect) {
                    window.location.href = feedback.redirect;
                }
            }
        });
    });

    $('.edit').on('click', function(){
        $('#edit-label').html('Editing <strong>"' + $(this).data('category-name') + '</strong>"');
        $('#category_name').val($(this).data('category-name'));
        $('#category_id').val($(this).data('category-id'));
        $('#editCategory').modal('show');
    });

    $('#edit-category').on('submit', function(event){
        event.preventDefault();

        $.ajax({
            url: '/fmware/edit-category',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(feedback){
                if (feedback.redirect) {
                    window.location.href = feedback.redirect;
                }
            }
        });
    });
});