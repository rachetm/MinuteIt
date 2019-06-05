var id;

function confirmation_modal_show(m_id)
{
    id = m_id;
    $('#confirmation').modal('show');
}

function del_meeting()
{
    $('#confirmation').modal('hide');
    
    $.ajax({
            url: 'del_meet.php',
            data: {m_id:id},
            type: 'POST',
        })
        .done(function (data, status) {
            if (data == "success")
            {
                $("#meet_"+ id).fadeOut("slow");
                $("#meet_"+ id +"_hr").fadeOut("slow");

                setTimeout(() => {
                    document.getElementById("meet_" + id).remove();
                    document.getElementById("meet_" + id + "_hr").remove();
                }, 2000);

                setTimeout(() => {
                    $('html, body').animate({ scrollTop: 0 }, 'slow');
                    document.getElementById("msg").innerHTML = "<div class='alert alert-success'>Meeting has been deleted successfully.</div>";
                }, 1000);

                setTimeout(() => {
                    $("#msg").animate({opacity:0}, 'slow');
                },4000);
                
                
                setTimeout(() => {
                    document.getElementById("msg").innerHTML = "";
                    $("#msg").animate({opacity:1});
                }, 5000);
            } 
            else
            {
                $('html, body').animate({scrollTop: 0 }, 'slow');
                document.getElementById("msg").innerHTML = "<div class='alert alert-danger'>Something went wrong! Please try again.</div>";
                
                setTimeout(() => {
                    $("#msg").animate({opacity:0}, 'slow');
                },4000);

                setTimeout(() => {
                    document.getElementById("msg").innerHTML = "";
                    $("#msg").animate({opacity:1});
                }, 5000);
            }
        })
        .fail(function (data, status) {
            document.getElementById("msg").innerHTML = "<div class='alert alert-danger'>Something went wrong! Please try again.</div>";
            $('html, body').animate({scrollTop: 0 }, 'slow');

            setTimeout(() => {
                    $("#msg").animate({opacity:0}, 'slow');
                },4000);

                setTimeout(() => {
                    document.getElementById("msg").innerHTML = "";
                    $("#msg").animate({opacity:1});
                }, 5000);
        });
}