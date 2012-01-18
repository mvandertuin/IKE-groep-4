function onl() {
    $(".contentbox").draggable({ containment:"#wrapper", revert:"invalid"});
    $("#del").droppable({
        drop:function (event, ui) {
            $.post("addrating/", { mbid:ui.draggable.context.id, rhash:"2pm", albumname:"geniasal", rat:-2}, function (data) {
                ouralert(data)
            });
            ui.draggable.remove();
        }});
    $("#info[rel]").overlay({
        mask:{
            color:'#ebecff',
            loadSpeed:200,
            opacity:0.9
        },
        closeOnClick:false
    });
}
$(document).ready(onl);
