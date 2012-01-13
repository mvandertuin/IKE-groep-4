// JavaScript Document
//Script for introduction page
$(document).ready(function() { 
 $(".question").each(function() {
    var radios = $(this).find(":radio").hide();
    $("<div></div>").slider({
      value: ($(":radio[name="+radios.attr("name")+"]:checked").val()) ,
      min: parseInt(radios.first().val(), 10),
      max: parseInt(radios.last().val(), 10),
      slide: function(event, ui) {
        radios.filter("[value=" + ui.value + "]").click();
      }
    }).appendTo(this);
});
  
  
}); 


