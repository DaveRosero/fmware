$(document).ready(function(){
    $('#stocks-table').DataTable({
        dom: 'lBfrtip',
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
            var dataTableButtons = $('.dt-buttons');
            $('#printButtonContainer').append(dataTableButtons);
        }
    });

    $('#products').select2({
        dropdownParent: $('#addStock'),
    });

    $('#add-stock').on('submit', function(event){
        event.preventDefault();

        $.ajax({
            url: '/fmware/add-stock',
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

    $('.restock').on('click', function(){
        $('#edit-label').html('Restocking <strong>"' + $(this).data('product-name') + '</strong>"');
        $('#product-name').val($(this).data('product-name'));
        $('#product-id').val($(this).data('product-id'));
        $('#restock').modal('show');
    });

    $('#restock-product').on('submit', function(event){
        event.preventDefault();

        console.log($(this).serialize());

        $.ajax({
            url: '/fmware/restock',
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