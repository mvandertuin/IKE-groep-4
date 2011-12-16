function onl(){
	$(".pos").click(handle);
	$(".neg").click(handle);
	$(".votedon").off("click");
	$(".votedon").click(function(){ouralert("U heeft deze rating al uitgebracht op deze plaat, u kunt hem nog wel wijzigen.")});
	$(".delete").click(del);
	$("#alertbox").overlay({
	mask: {
		color: '#ebecff',
		loadSpeed: 200,
		opacity: 0.9
	},
	closeOnClick: true,
	oneInstance: false,
	load: false
	});
}

function ouralert(infotext){
	$("#alertbox").html(infotext);
	$('#alertbox').css('style', 'block');

	$("#alertbox").data("overlay").load();
}

function handle(event){
	if($(event.target).hasClass('neg')){
		rat = -1;
	}else{
		rat = 1;
	}
	$.post("addrating/", { mbid: event.target.id.replace("_"+rat, ""), rhash: "2pm", albumname: "geniasal", rat: rat}, function(data){ change(data, event) });
	
	
	//alert("U stem is opgeslagen");
	//$("."+event.target.id).off("click");
	//$("."+event.target.id).click(function(){alert("U heeft al gestemd")});
}

function change(data, event){
	if(data.indexOf("geupdate") != -1){
		if($(event.target).hasClass('neg')){
			$(".pos").filter("."+event.target.id.replace("_\-1", "")).removeClass('votedon').off("click").click(handle);
		}else{
			$(".neg").filter("."+event.target.id.replace("_\+1", "")).removeClass('votedon').off("click").click(handle);
		}
	}
	$(event.target).addClass("votedon").off("click").click(function(){ouralert("U heeft deze rating al uitgebracht op deze plaat, u kunt hem nog wel wijzigen.")});
	ouralert(data);
	
}
$(document).ready(onl)
