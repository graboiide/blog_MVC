(function ($){

   $("input[type=file]").change(function () {

      var fieldVal = $(this).val();
      if (fieldVal !== undefined || fieldVal !== "") {  console.log($(this).val());
         $(this).nextAll(".custom-file-label").text(fieldVal);
      }
   });
  

})(jQuery);
