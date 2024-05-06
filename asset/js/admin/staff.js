$(document).ready(function(){
    $('#staff-table').DataTable({
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

    $('#show_password').change(function(){
        if($(this).is(':checked')) {
            $('#password').prop('type', 'text');
            $('#confirm').prop('type', 'text');  
        } else {
            $('#password').prop('type', 'password');
            $('#confirm').prop('type', 'password');  
        }
    });

    $('#add-staff').submit(function(event){
        event.preventDefault();
        console.log('form submitted');

        $.ajax({
            url: '/add-staff',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'text',
            success: function(feedback) {
                console.log(feedback);
                if (feedback === '/staff') {
                    window.location.href = feedback;
                } else {
                    $.notify(feedback, 'error');
                }
                
            }
        });
    });
});