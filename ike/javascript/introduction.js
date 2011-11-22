// JavaScript Document
//Script for introduction page
$(document).ready(function() { 
  $("#sortlist").sortable({ 
    handle : '.handle', 
    update : function () { 
      $("#sorted").val($('#sortlist').sortable('toArray')); 
    } 
  }); 
}); 