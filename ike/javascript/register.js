// JavaScript Document
//Script for registration page
function ajaxregister() {
    $('#btnreg').hide();
    $('#imgreg').show();
    //alert('Onbekende gegevens');
    $.ajax({
        type:"POST",
        url:"json/register/do.json",
        dataType:"json",
        data:"naam=" + $('#naam').val() + "&email=" + $('#inlog').val() + "&ww1=" + $('#ww1').val() + "&ww2=" + $('#ww2').val(),
        success:function (data) {
            if (data['result'] == 'succes') {
                window.location = data['redirectURI'];
            } else {
                alert(data['message']);
                $('#btnreg').show();
                $('#imgreg').hide();
            }
        },
        error:function () {
            alert('Er is een onbekende fout opgetreden');
            $('#btnreg').show();
            $('#imgreg').hide();
        }
    });

}