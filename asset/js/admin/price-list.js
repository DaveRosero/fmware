$(document).ready(function(){
    $('#price-list').DataTable({
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
        order: [
            [1, 'asc']
        ],
        initComplete: function () {
            var dataTableButtons = $('.dt-buttons');
            $('#printButtonContainer').append(dataTableButtons);
        }
    });

    $('#products').select2({
        dropdownParent: $('#newPrice'),
        width: '100%',
        placeholder: 'Select a product'
    });

    $('#new-price').on('submit', function(event){
        event.preventDefault();

        $.ajax({    
            url: '/fmware/new-price',
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