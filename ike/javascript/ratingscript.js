function onl(){
	$(".ratingding").click(handle);
}

function handle(event){
	if($(event.target).hasClass('neg')){
		rat = -1;
	}else{
		rat = 1;
	}
	$.post("http://localhost/ike/addrating/", { mbid: event.target.id, rhash: "2pm", albumname: "geniasal", rat: rat}, function(data) {
  alert(data);
	});
	//alert("U stem is opgeslagen");
	//$("."+event.target.id).off("click");
	//$("."+event.target.id).click(function(){alert("U heeft al gestemd")});
}
$(document).ready(onl)
