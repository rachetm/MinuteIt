function ifsure(checkbox)
{
    let $temp;
    checkbox.checked ? $temp = 1 : $temp = 0;
    if($temp)
    {
        document.getElementById(checkbox.id).checked = true;
        document.getElementById(checkbox.id).value = 1;
    }
    else
    {
        document.getElementById(checkbox.id).checked = false;
        document.getElementById(checkbox.id).value = 0;
    }
}

function remove_mem()
{
    var form_data = $("#mem_rmv_form").serialize();

    $.ajax({
            url: "rmv_mem.php",
            data: form_data,
            type: 'POST',
        })
        .done(function (data, status) {
            if (data == "success") 
            {
                displayMsg('mem_msgmod', 'Member has been deleted successfully.', 'success');
                manage_members();
            } 
            else if (data == "notsure") 
            {
                displayMsg('mem_msgmod', 'Please tick the checkbox', 'danger');
            } 
            else 
            {
                displayMsg('mem_msgmod', 'Something went wrong! Please try again.', 'danger');
            }
        })
        .fail(function (data, status) {
            displayMsg('mem_msgmod', 'Something went wrong! Please try again.', 'danger');
        });
}

$("#mem_add_btn").click(function (event) {
    event.preventDefault();
    if ($("#fac_name").val().length > 0 && $("#fac_email").val().length > 0)
    {
        var form_data = $("#mem_add_form").serialize();

        $.ajax({
            url: 'add_mem.php',
            data: form_data,
            type: 'POST',
        })
            .done(function (data, status) {
                if (data == "success") {
                    displayMsg('mem_msgmod', 'Member has been added successfully.', 'success');
                    document.getElementById('mem_add_form').reset();
                }
                else if(data == "error"){
                    displayMsg('mem_msgmod', 'Something went wrong! Please try again.', 'danger');
                }
                else {
                    displayMsg('mem_msgmod', 'Something went wrong! Please try again.', 'danger');
                }
            })
            .fail(function (data, status) {
                displayMsg('mem_msgmod', 'Something went wrong! Please try again.', 'danger');
            });
    }
    else 
    {
        if ($("#fac_name").val().length == 0)
        {
            displayMsg('mem_msgmod', 'Faculty name cannot be blank.', 'danger');
            document.getElementById("fac_name").focus();
        }
        else
        {
            displayMsg('mem_msgmod', 'Faculty email cannot be blank.', 'danger');
            document.getElementById("fac_email").focus();
        }
    }
});

function manage_members()
{
    document.getElementById("mem_rmv_form").innerHTML = "<p style='color: grey; font-size: 15px;'><b>Loading...</b></p>";
    $.ajax({
        type: "POST",
        url: "grp_dt_fetch.php",
        success: function (data, status) {

        if (data == "error")
        {
            document.getElementById("mem_rmv_form").innerHTML = "<p style='color: red; font-size: 15px;'><b>Error fetching data!</b></p>";
        } 
        else 
        {
            let members = JSON.parse(data);
            var result_str = `<div class="form-row form-group">
                                    <label for="mem_select">Select a member :</label>
                                    <select class="form-control custom-select" id="mem_select" name="member">`;
            var i = 0;
            while (i < members.length) {
                result_str += `<option value='${members[i].fac_id}'>${members[i].fac_name} ----------- ${members[i].fac_email}</option>`;
                i++;
            }

            result_str += ` </select></div>
                            <div class="form-row form-group">
                                <div class="col-lg-auto col-sm-auto">
                                    <label for="sure">Are you sure?</label>
                                    <input type='checkbox' class="form-check-input" id="sure" name="sure" onclick="ifsure(this)" value="0" style="margin-left: 10px">
                                </div>
                            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="remove_mem()">Remove</button>
            </div>
            
            </form>`;

            document.getElementById("mem_rmv_form").innerHTML = result_str;
        }
    },
    error: function (x, s, err) {
        document.getElementById("mem_rmv_form").innerHTML = "<p style='color: grey; font-size: 15px;'><b>Error fetching data!</b><br></p>";
    }
    });
}