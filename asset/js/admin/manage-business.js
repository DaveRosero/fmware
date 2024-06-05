$(document).ready(function(){
    $('#expenses-table').DataTable();
    $('#wage-table').DataTable();

    $('#add-expenses').click(function(){
        $('#expenses-modal').modal('show');
    });
})