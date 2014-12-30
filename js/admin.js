var server_url = '';
// var server_url = 'http://popcornstocks.com'; 
var item_id = $('#selected_item').data("item_id");


// Confirm navigation
function setConfirmUnload(on) {
    
   window.onbeforeunload = (on) ? unloadMessage : null;

}

function unloadMessage() {
    
   return '您确定要离开吗? 更改将丢失.\n' +
      'Unsaved changes will be lost.';

}

// Sort images
function sortImages(msg) {

   var object = JSON.parse(msg);

   // Sort client side images
   if (object.status === "Images sorted and renamed.") {
      // Temporary id to avoid conflict with .sortable
      for (var i = 0; i < object.order.length ; i++) {                                      
         var old_id = '#order_' + object.order[i];
         var temp_id = i;
         $(old_id).attr('id', temp_id);

         //label
         var label_id = '#ol_' + object.order[i];
         var label_temp_id = 'label_' + i;
         $(label_id).attr('id', label_temp_id);
      };

      for (var i = 0; i < object.order.length ; i++) { 
         // Change the id attr of pictures                     
         var temp_id = '#' + i;
         var new_id = 'order_' + i;
         $(temp_id).attr('id', new_id);

         // Change the label
         var label_temp_id = '#label_' + i;
         var new_id = 'ol_' + i;
         $(label_temp_id).attr('id', new_id);
         $('#' + new_id).html(i + 1);
      };
   };

}

function updatePosition(){
   $('#small_img_box').find('li').each(function(i){
      $(this).attr('id', 'order_' + i);
      $('div', this).attr('id', 'ol_' + i);
      $('div', this).html(i + 1);
   });
}

$(document).ready(function() { 

   // Prevent accidental navigation away     
   $(':input',document.forms.newItem).bind(
      "change", function() { 

         setConfirmUnload(true);

      }
   ); 

   $(':button[name=submit]').click(function() {

      setConfirmUnload(false);

   });

   //Sort image order
   $('#small_img_box').sortable({

      axis: 'x',
      update: function (event, ui) {
         var data = $(this).sortable('serialize') + "&item=" + item_id;
         // POST to server using $.post or $.ajax
         $.ajax({
            type: 'POST',
            url: 'admin_sort_img.php',
            data: data    
         })
            .done(function( msg ) {
               sortImages( msg );
            });

      }

   });

   $('.img_order').on('click', function(e){
      var img = parseInt($(this).text()) - 1;
      $(this).parent().remove();
      var data =  "item=" + item_id +
                  "&image=" + img;
      $.ajax({
         type: 'POST',
         url: 'admin_delete_img.php',
         data: data,
         error: function(xhr, status, error) {
            var err = eval("(" + xhr.responseText + ")");
            alert(err.Message);
         }
      })
         .done(function( msg ) {
            updatePosition();
            var parsed = JSON.parse( msg );
            var status = parsed.status;
            console.log(status);
         });
   });



});