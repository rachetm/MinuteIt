$('#chgpwd').on('shown.bs.modal', function () {
    $('#currpwd').focus()
});

$('#add_rmv_grp').on('shown.bs.modal', function () {
    $('#grp').focus()
});

$('#membermanage').on('shown.bs.modal', function () {
    $('#fac_name').focus()
});

$('#add_action').on('shown.bs.modal', function () {
    $('#action_field').focus()
});

function displayMsg(id, message, type)
{
    document.getElementById(id).innerHTML = "";
    $('#'+id).animate({opacity: 1}, 'fast');
    document.getElementById(id).innerHTML = "<div class = 'alert alert-"+ type +"'>"+ message + "</div>";
    setTimeout(() => {
        $('#'+id).animate({opacity: 0}, 'slow');
        document.getElementById(id).innerHTML = "";
    }, 2000);
}