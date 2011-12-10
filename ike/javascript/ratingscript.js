function onl(){
	$(".ratingding").click(handle);
	$(".votedon").off("click");
	$(".votedon").click(function(){alert("U heeft deze rating al uitgebracht op deze plaat, u kunt hem nog wel wijzigen.")});
	$(".top").click(viewchange);
}

function viewchange(event){
	if($("#"+event.target.id+"row").is(':visible')){
		$("#"+event.target.id+"row").hide();
	}else{
		$("#"+event.target.id+"row").show();
	}
}

function handle(event){
	if($(event.target).hasClass('neg')){
		rat = -1;
	}else{
		rat = 1;
	}
	$.post("http://localhost/ike/addrating/", { mbid: event.target.id, rhash: "2pm", albumname: "geniasal", rat: rat}, function(data){ change(data, event) });
	
	
	//alert("U stem is opgeslagen");
	//$("."+event.target.id).off("click");
	//$("."+event.target.id).click(function(){alert("U heeft al gestemd")});
}

function change(data, event){
	if(data.indexOf("geupdate") != -1){
		if($(event.target).hasClass('neg')){
			$('.pos').filter("."+event.target.id).removeClass('votedon').off("click").click(handle);
			$("#"+event.target.id+"row").hide();
		}else{
			$('.neg').filter("."+event.target.id).removeClass('votedon').off("click").click(handle);
			
		}
	}
	$(event.target).addClass("votedon").off("click").click(function(){alert("U heeft deze rating al uitgebracht op deze plaat, u kunt hem nog wel wijzigen.")});
	alert(data);
	
}
$(document).ready(onl)
