 $(document).ready(function() {
     // if text input field value is not empty show the "X" button
     $("#field").keyup(function() {
         $("#x").fadeIn();
         if ($.trim($("#field").val()) == "") {
             $("#x").fadeOut();
         }
     });
     // on click of "X", delete input field value and hide "X"
     $("#x").click(function() {
         $("#field").val("");
         $(this).hide();
     });
 });
$(document).ready(function(){
      / / 
$('.noEnterSubmit').bind('keypress', false);
 $('.noEnterSubmit').keypress(function(e) {
 if (e.which == 13) return false;
 //or...
 if (e.which == 13) e.preventDefault();
 });
 });