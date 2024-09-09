(function ($, Drupal) {
    Drupal.behaviors.addToCart = {
      attach: function (context, settings) {
        if ($('.cart-icon-message').length) {
          var messages = Drupal.messages || [];
          var cartMessage = messages.find(function(message) {
            return message.includes('Product has been added to cart');
          });
  
          if (cartMessage) {
            $('.cart-icon-message').html(cartMessage);
          }
        }
      }
    };
  })(jQuery, Drupal);
  