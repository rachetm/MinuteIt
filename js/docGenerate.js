$('.doc_download').click(() => {

        $('html, body').animate({
            scrollTop: 0
        }, 'fast');
        
        document.getElementById("msg").innerHTML = "<div class='alert alert-success'>File will begin downloading shortly</div>";

    setTimeout(() => {
        $("#msg").animate({
            opacity: 0
        }, 'slow');
    }, 10000);

    setTimeout(() => {
        document.getElementById("msg").innerHTML = "";
        $("#msg").animate({
            opacity: 1
        });
    }, 10200);
});

$('#doc_download_action').click(() => {

    document.getElementById("act_msgmod").innerHTML = "<div class='alert alert-success'>File will begin downloading shortly</div>";

    setTimeout(() => {
        $("#act_msgmod").animate({
            opacity: 0
        }, 'slow');
    }, 10000);

    setTimeout(() => {
        document.getElementById("act_msgmod").innerHTML = "";
        $("#act_msgmod").animate({
            opacity: 1
        });
    }, 10200);
});