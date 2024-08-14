$(document).ready(function(){
    function viewPOS (pos_ref) {
        $.ajax({
            url: '/view-sales',
            method: 'POST',
            data: {
                pos_ref : pos_ref
            },
            dataType: 'json',
            success: function(json) {
                console.log(json);
                $('#viewModalLabel').text(json.pos_ref);
                $('#view-content').html(json.content);
                $('#staff').text(json.staff);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
                console.log("Response:", jqXHR.responseText);
            }
        });
    }

    $('#sales-table').DataTable();

    $(document).on('click', '.viewPOS', function(){
        viewPOS($(this).data('pos-ref'));
    });
})