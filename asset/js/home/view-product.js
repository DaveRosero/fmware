$(document).ready(function(){
    $('.add-to-cart').on('click', function(){
        console.log('click');
        $('#product-modal').modal('show');
    });

    $('#variant').select2({
        dropdownParent: $('#product-modal'),
        width: '100%',
        placeholder: 'Select variant'
    });
})