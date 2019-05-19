// var mid;

function action_modal_show(m_id)
{
    let id = m_id;
    $('#add_action').modal('show');
    document.getElementById('act_msgmod').innerHTML = "";
    document.getElementById('action_field').innerHTML = "Loading...";
    $('#action_field').prop('disabled', true);
    load_saved_action(id);
    set_id(id);
}

function set_id(id)
{
    document.getElementById('m_id').value = id;
    document.getElementById('mid').value = id;
}

function set_agenda(id) {
    $.ajax({
            url: 'email_meet_dataGet.php',
            data: {
                m_id: id,
                agenda: true
            },
            type: 'POST'
        })
        .done(function (data, status) {
            let ag = JSON.parse(data);
            $('#action_field').val(ag['agenda']);
            $('#action_field').prop('disabled', false);
        })
        .fail(function (data, status) {
            $("#act_msgmod").animate({opacity: 1}, 'fast');
            document.getElementById("act_msgmod").innerHTML = "<div class='alert alert-danger'>Something went wrong! Please try again.</div>";
            setTimeout(() => {
                $("#act_msgmod").animate({opacity: 0}, 'slow');
                document.getElementById("act_msgmod").innerHTML = "";
            }, 4000);
        })
}

function load_saved_action(id)
{
    $.ajax({
        url: 'load_saved_action.php',
        data: {m_id : id},
        type: 'POST'
    })
    .done(function(data, status)
    {
        if(data != "error")
        {
            let details = JSON.parse(data);

            if(details != null && details['action_taken'] != "")
            {
                $('#action_field').prop('disabled', false);
                $('#act_date').val(details['date']);
                $('#action_field').val(details['action_taken']);
            }
            else
            {
                console.log("duckdjv");
                var now = new Date();
                var day = ("0" + now.getDate()).slice(-2);
                var month = ("0" + (now.getMonth() + 1)).slice(-2);

                var today = now.getFullYear() + "-" + (month) + "-" + (day);

                $('#act_date').val(today);

                set_agenda(id);
            }
        }
        else
        {
            document.getElementById("act_msgmod").innerHTML = "<div class='alert alert-danger'>Failed to load previously saved action! Saving now will <b><i>overwrite previous data.</i></b> Close and try again.</div>";
        }
    })
    .fail(function (data, status)
    {
        document.getElementById("act_msgmod").innerHTML = "<div class='alert alert-danger'>Failed to load previously saved action! Saving now will <b><i>overwrite previous data.</i></b> Close and try again.</div>";
    })
}

function add_action()
{
    var form_data = $("#action_form").serializeArray();
    // form_data.push({name: 'm_id', value: id});

    $.ajax({
        url: 'add_action.php',
        data: form_data,
        type: 'POST'
    })
    .done(function(data, status){
        if(data == "success")
        {
            $("#act_msgmod").animate({opacity:1}, 'fast');
            document.getElementById("act_msgmod").innerHTML = "<div class='alert alert-success'>Action was added successfully. Please click on close.</div>";
            setTimeout(() => {
                    $("#act_msgmod").animate({opacity:0}, 'slow');
                    document.getElementById("act_msgmod").innerHTML = "";
                },4000);
        }
        else if(data == "update_success")
        {
            $("#act_msgmod").animate({opacity:1}, 'fast');
            document.getElementById("act_msgmod").innerHTML = "<div class='alert alert-success'>Action was updated successfully. Please click on close.</div>";
            setTimeout(() => {
                    $("#act_msgmod").animate({opacity:0}, 'slow');
                    document.getElementById("act_msgmod").innerHTML = "";
                },4000);
        }
        else
        {
            $("#act_msgmod").animate({opacity:1}, 'fast');
            document.getElementById("act_msgmod").innerHTML = "<div class='alert alert-danger'>Something went wrong! Please try again.</div>";
            setTimeout(() => {
                    $("#act_msgmod").animate({opacity:0}, 'slow');
                    document.getElementById("act_msgmod").innerHTML = "";
                },4000);
        }
    })
    .fail(function (data, status) 
    {
        $("#act_msgmod").animate({opacity:1}, 'fast');
        document.getElementById("act_msgmod").innerHTML = "<div class='alert alert-danger'>Something went wrong! Please try again.</div>";
        setTimeout(() => {
                    $("#act_msgmod").animate({opacity:0}, 'slow');
                    document.getElementById("act_msgmod").innerHTML = "";
                },4000);
    })
}