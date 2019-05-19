var id;

function docGen(m_id)
{
    id = m_id;
    // console.log(id);
    $.ajax({
            url: 'docGenerate.php',
            data: {m_id: id},
            type: 'POST',
        })
        .done(function (data, status) 
        {
            if (data == "error") 
            {
                $('html, body').animate({
                    scrollTop: 0
                }, 'slow');
                document.getElementById("msg").innerHTML = "<div class='alert alert-danger'>Something went wrong! Please try again.</div>";

                setTimeout(() => {
                    $("#msg").animate({
                        opacity: 0
                    }, 'slow');
                }, 4000);

                setTimeout(() => {
                    document.getElementById("msg").innerHTML = "";
                    $("#msg").animate({
                        opacity: 1
                    });
                }, 5000);
            }
            else
            {
                setTimeout(() => {
                    $('html, body').animate({
                        scrollTop: 0
                    }, 'slow');
                    document.getElementById("msg").innerHTML = "<div class='alert alert-success'>File will download shortly</div>";
                }, 1000);

                setTimeout(() => {
                    $("#msg").animate({
                        opacity: 0
                    }, 'slow');
                }, 4000);


                setTimeout(() => {
                    document.getElementById("msg").innerHTML = "";
                    $("#msg").animate({
                        opacity: 1
                    });
                }, 5000);
            }
        })
        .fail(function (data, status) {
            document.getElementById("msg").innerHTML = "<div class='alert alert-danger'>Something went wrong! Please try again.</div>";
            $('html, body').animate({
                scrollTop: 0
            }, 'slow');

            setTimeout(() => {
                $("#msg").animate({
                    opacity: 0
                }, 'slow');
            }, 4000);

            setTimeout(() => {
                document.getElementById("msg").innerHTML = "";
                $("#msg").animate({
                    opacity: 1
                });
            }, 5000);
        });
}