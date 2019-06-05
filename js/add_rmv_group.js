function ifsure(checkbox) {
    let $temp;
    checkbox.checked ? $temp = 1 : $temp = 0;
    if ($temp) {
        document.getElementById(checkbox.id).checked = true;
        document.getElementById(checkbox.id).value = 1;
    } else {
        document.getElementById(checkbox.id).checked = false;
        document.getElementById(checkbox.id).value = 0;
    }
}

function remove_grp() 
{
    var form_data = $("#grp_rmv_form").serialize();

    $.ajax({
            url: 'rmv_grp.php',
            data: form_data,
            type: 'POST',
        })
        .done(function (data, status) 
        {
            if (data == "success") 
            {
                displayMsg('grp_msgmod', 'Group has been deleted successfully.', 'success');
                manage_groups();
            } 
            else if (data == "notsure")
            {
                displayMsg('grp_msgmod', 'Please tick the checkbox', 'danger');
            } 
            else 
            {
                displayMsg('grp_msgmod', 'Something went wrong! Please try again.', 'danger');
            }
        })
        .fail(function (data, status) {
            displayMsg('grp_msgmod', 'Something went wrong! Please try again.', 'danger');
        });
}
$("#grp_add_btn").click(function (event) 
{
    event.preventDefault();
    
    if ($("#grp").val().length > 0 )
    {
        var form_data = $("#grp_add_form").serialize();
        
        $.ajax({
            url: 'add_grp.php',
            data: form_data,
            type: 'POST',
        })
        .done(function (data, status) 
        {
            if (data == "success") {
                displayMsg('grp_msgmod', 'Group has been added successfully.', 'success');
                document.getElementById('grp_add_form').reset();
            } 
            else 
            {
                displayMsg('grp_msgmod', 'Something went wrong! Please try again.', 'danger');
            }
        })
        .fail(function (data, status) {
            displayMsg('grp_msgmod', 'Something went wrong! Please try again.', 'danger');
        });
    }
    else
    {
        displayMsg('grp_msgmod', 'Group name cannot be blank.', 'danger');
        
        document.getElementById("grp").focus();
    }
});

function manage_groups()
{
    document.getElementById("grp_rmv_form").innerHTML = "<p style='color: grey; font-size: 15px;'><b>Loading...</b></p>";
    $.ajax({
        type: "GET",
        url: "get_groups.php",
        success: function (data, status) {

            if (data == "error")
            {
                document.getElementById("grp_rmv_form").innerHTML = "<p style='color: red; font-size: 15px;'><b>Error fetching data!</b></p>";
            } 
            else 
            {
                let groups = JSON.parse(data);
                var result_str = `<div class="form-row form-group">
                                    <label for="mem_select">Select a group :</label>
                                    <select class="form-control custom-select" id="grp_select" name="grp_name">`;
                var i = 0;
                while (i < groups.length) {
                    var name = groups[i].grp_name.toUpperCase();
                    name = name.replace("_", " ");
                    result_str += `<option value='${groups[i].grp_name}'>${name}</option>`;
                    i++;
                }

                result_str += `</select></div>
                                <div class="form-row form-group">
                                    <div class="col-lg-auto col-sm-auto">
                                        <label for="sure">Are you sure?</label>
                                        <input type='checkbox' class="form-check-input" id="sure" name="sure" onclick="ifsure(this)" value="0" style="margin-left:10px">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-danger" onclick="remove_grp()">Remove</button>
                                </div>`;

                document.getElementById("grp_rmv_form").innerHTML = result_str;
            }
        },
        error: function (x, s, err) {
            document.getElementById("grp_rmv_form").innerHTML = "<p style='color: grey; font-size: 15px;'><b>Error fetching data!</b><br></p>";
        }
    });
}