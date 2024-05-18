$(document).ready(function(){
    function updateStaff (active, id) {
        console.log('staff update ajax');

        $.ajax({
            url: '/update-staff',
            method: 'POST',
            data: {
                active : active,
                id : id
            },
            dataType: 'text',
            success: function(feedback) {
                console.log(feedback);
                if (feedback === '/staff') {
                    window.location.href = feedback;
                }
            }
        });
    }

    $('#staff-table').DataTable();

    $('#show_password').change(function(){
        if ($(this).is(':checked')) {
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

    $('.status').change(function(){
        if ($(this).is(':checked')) {
            var id = $(this).data('user-id');
            var active = 1;
            updateStaff(active, id);
            console.log('checked');
        } else {
            var id = $(this).data('user-id');
            var active = 0;
            updateStaff(active, id);
            console.log('unchecked');
        }
    })
});