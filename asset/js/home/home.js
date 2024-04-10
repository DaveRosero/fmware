'use strict';
$(document).ready(function() {
    // Hide all accordion panels initially
    var all_panels = $('.templatemo-accordion > li > ul').hide();

    // Function to expand/collapse accordion panels
    $('.templatemo-accordion > li > a').click(function() {
        var target = $(this).next();

        if (!target.hasClass('active')) {
            // Collapse all other panels and expand the clicked panel
            all_panels.removeClass('active').slideUp();
            target.addClass('active').slideDown();

            // Store the index of the clicked accordion item in localStorage
            localStorage.setItem('activeAccordion', $(this).parent().index());
        }

        return false; // Prevent default anchor click behavior
    });

    // Check localStorage for active accordion item on page load
    var activeAccordionIndex = localStorage.getItem('activeAccordion');

    if (activeAccordionIndex !== null) {
        // Expand the previously active accordion panel
        var activePanel = $('.templatemo-accordion > li').eq(activeAccordionIndex).find('ul');
        activePanel.addClass('active').slideDown();
    }
    // End accordion

    // Product detail
    $('.product-links-wap a').click(function(){
      var this_src = $(this).children('img').attr('src');
      $('#product-detail').attr('src',this_src);
      return false;
    });
    $('#btn-minus').click(function(){
      var val = $("#var-value").html();
      val = (val=='1')?val:val-1;
      $("#var-value").html(val);
      $("#product-quanity").val(val);
      return false;
    });
    $('#btn-plus').click(function(){
      var val = $("#var-value").html();
      val++;
      $("#var-value").html(val);
      $("#product-quanity").val(val);
      return false;
    });
    $('.btn-size').click(function(){
      var this_val = $(this).html();
      $("#product-size").val(this_val);
      $(".btn-size").removeClass('btn-secondary');
      $(".btn-size").addClass('btn-success');
      $(this).removeClass('btn-success');
      $(this).addClass('btn-secondary');
      return false;
    });
    // End roduct detail

});
