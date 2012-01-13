function change(data, event){
	var sys = arbor.ParticleSystem(10, 500, 1 , true, 55, 0.02, 0.6);
	sys.renderer = Renderer("#viewport") ;
	sys.graft(jQuery.parseJSON(data));
}


function onl(){
	$.post("graphjson/", function(data){ change(data, event) });
}


$(document).ready(onl)