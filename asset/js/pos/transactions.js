$(document).ready(function () {
    $(".view-transaction-btn").click(function () {
      const posRef = $(this).data("bs-posref");
  
      $.ajax({
        url: "/pos-transactions",
        method: "GET",
        data: {
          pos_ref: posRef,
        },
        dataType: "json",
        success: function (data) {
          console.log(data);
  
          // Number formatter for currency
          var formatter = new Intl.NumberFormat("en-PH", {
            style: "currency",
            currency: "PHP",
          });
  
          // Update general transaction details
          $("#transactionViewLabel").text("Transaction #" + data.pos_ref);
          $("#transaction-date").text(data.date);
          $("#transaction-subtotal").text(
            formatter.format(Number(data.subtotal))
          );
          $("#rtransaction-total").text(formatter.format(Number(data.total)));
          $("#rviewdiscountRec-input").val(data.discount);
          $("#rviewcashRec-input").val(Number(data.cash).toFixed(2));
          $("#rtransaction-change").text(formatter.format(Number(data.changes)));
          $("#transaction-status").text(data.status);
          $("#rtrans-username").text(data.username);
  
          // Clear and set payment method
          $("#rpaymentMethod").empty();
          $("#rpaymentMethod").append(new Option("G-Cash", "G-Cash"));
          $("#rpaymentMethod").append(new Option("Cash", "Cash"));
          if (
            $("#rpaymentMethod option[value='" + data.payment_type + "']")
              .length === 0
          ) {
            $("#rpaymentMethod").append(
              $("<option>", {
                value: data.payment_type,
                text: data.payment_type,
              })
            );
          }
          $("#rpaymentMethod").val(data.payment_type);
  
          // Clear and set transaction type
          $("#rtransaction-type").empty();
          if (
            $(
              "#rtransaction-type option[value='" +
                data.transaction_type +
                "']"
            ).length === 0
          ) {
            $("#rtransaction-type").append(
              $("<option>", {
                value: data.transaction_type,
                text: data.transaction_type,
              })
            );
          }
          $("#rtransaction-type").val(data.transaction_type);
  
          // Show or hide customer details based on transaction type
          if (data.transaction_type === "Walk-in") {
            // Show only first name and last name, hide other details
            $("#customer-details").show();
            $("#rfName-input").val(data.firstname);
            $("#rlName-input").val(data.lastname);
            $("#viewstreet-input, #street-label").val("").hide();
            $("#viewbrgy-input, #brgy-label").val("").hide();
            $("#viewmunicipality-input, #municipality-label").val("").hide();
            $("#viewcontact-input, #contact-label").val("").hide();
            $("#viewdeliverer-input, #deliverer-label").val("").hide();
          } else if (data.transaction_type === "Delivery") {
            // Show all customer details
            $("#customer-details").show();
            $("#rfName-input").val(data.firstname);
            $("#rlName-input").val(data.lastname);
  
            // Split address into street, baranggay, municipality
            let addressParts = data.address.split(", ");
            let street = addressParts[0];
            let baranggay = addressParts[1];
            let municipality = addressParts[2];
  
            $("#viewstreet-input, #street-label").val(street).show();
            $("#viewbrgy-input, #brgy-label").val(baranggay).show();
            $("#viewmunicipality-input, #municipality-label")
              .val(municipality)
              .show();
            $("#viewcontact-input, #contact-label").val(data.contact_no).show();
            $("#viewdeliverer-input, #deliverer-label")
              .val(data.deliverer_name)
              .show();
          }
  
          // Make AJAX call to fetch product details
          $.ajax({
            url: "/pos-transactionItems",
            method: "GET",
            data: {
              pos_ref: posRef,
            },
            dataType: "html",
            success: function (data) {
              console.log(data);
              $("#transactionItems").html(data);
            },
          });
  
          // Show the modal after updating all details
          $("#transaction-viewModal").modal("show");
        },
      });
    });
    
    $('.cart-checkbox').click(function() {
      if ($(this).is(':checked')) {
          console.log('Checkbox clicked and checked', $(this).val(), $(this).data('user-id'));

          $.ajax({
              url: '/check-product',
              method: 'POST',
              data: {
                  user_id : $(this).data('user-id'),
                  product_id : $(this).val()
              },
              dataType: 'json',
              success: function(feedback) {
                  if (feedback.checked) {
                      getCartTotal();
                  }
              }
          })
      } else {
          console.log('Checkbox clicked and unchecked', $(this).val());

          $.ajax({
              url: '/uncheck-product',
              method: 'POST',
              data: {
                  user_id : $(this).data('user-id'),
                  product_id : $(this).val()
              },
              dataType: 'json',
              success: function(feedback) {
                  if (feedback.unchecked) {
                      getCartTotal();
                  }
              }
          })
      }
  });
    
  });
  