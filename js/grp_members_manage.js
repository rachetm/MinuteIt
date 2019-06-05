function onSaveChanges()
{
    var form_data = $("#group_form").serializeArray();
    // console.log(form_data);
    
    $.ajax({
        url: "grp_edit.php",
        data: form_data,
        type: 'POST',
        success: function (data,status) {

            if(data == "success")
            {
                displayMsg('msgmod', 'Changed saved successfully.', 'success');
            }
            if (data == "error")
            {
                displayMsg('msgmod', 'Something went wrong! Please try again.', 'danger');
            }
        },
        error: function (x, s, err) {
            displayMsg('msgmod', 'Something went wrong! Please try again.', 'danger');
        }
    });
}


function disp_members(group) 
{
    document.getElementById("msgmod").innerHTML = "";
    document.getElementById("msgmod").style.opacity = 1;
    
    if (group == "nav") 
    {
        document.getElementById("pills").innerHTML = "";
        $.ajax({
            type: "GET",
            url: "get_groups.php",
            success: function (data, status) {
                if (data == "error") {
                    document.getElementById("pills").innerHTML = "<p style='color: red; font-size: 15px;'><b>Error fetching groups!</b></p>";
                } 
                else if(data == "empty")
                {
                    document.getElementById("pills").innerHTML = "<p style='color: red; font-size: 15px;'><b>No groups present</b></p>";
                }
                else 
                {
                    let groups = JSON.parse(data);
                    var res_string = '';
                    var i = 0;
                    while (i < groups.length) {
                        let name = groups[i].grp_name.toUpperCase();
                        name = name.replace("_", " ");
                        res_string += `<li class="nav-item" id="${groups[i].grp_name}" onclick="disp_members(this.id)">
						<a class="nav-link grp_pills" id="pills-${groups[i].grp_name}-tab" data-toggle="pill" href="#pills" role="tab">${name}</a></li>`;
                        i++;
                    }
                    document.getElementById("pills-tab-grp").innerHTML = res_string;
                }

            },
            error: function (x, s, err) {
                document.getElementById("pills").innerHTML = "<p style='color: red; font-size: 15px;'><b>Error fetching groups!</b></p>";
            }
        });
        document.getElementById("msgmod").innerHTML = "<p style='color: grey; font-size: 14px; font-family: 'Lato', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;'><b>Select a group</b></p>";
        
        $('.grp_pills').each(function () {
            $(this).removeClass("active");
        });
    } 
    else 
    {
        document.getElementById("pills").innerHTML = "<p style='color: grey; font-size: 15px;'><b>Loading...</b></p>";
        $.ajax({
            type: "GET",
            url: "grp_dt_fetch.php",
            data: { grp : group},
            success: function (data, status) {

                if (data == "error") {
                    document.getElementById("pills").innerHTML = "<p style='color: red; font-size: 15px;'><b>Error fetching data!</b></p>";
                } 
                else
                {
                    let members = JSON.parse(data)[0];
                    let grp_mems = JSON.parse(data)[1];

                    var res_string = `<form action="" method="post" id="group_form">
                                                    <div class="col-lg-12 pt-3">
                                                    <div id="name">`;
                    var i = 0;
                    while (i<members.length) 
                    {
                        res_string += `
                        <div class="form-row form-group ml-4">
                            <label class="names greyout" id="lbl_${members[i].fac_id}" for="${members[i].fac_id}">${members[i].fac_name}</label> 
                            <input class="_fac_names" type="hidden" id="h_${members[i].fac_id}" name=names[] value="${members[i].fac_name}" disabled> 
                            <input class="_fac_names" type="hidden" id="hh_${members[i].fac_id}" name=emails[] value="${members[i].fac_email}" disabled>
                    <input type="checkbox" class="form-check-input col-lg-6 ml-5" id="${members[i].fac_id}" name=members[] value="${members[i].fac_id}"
                    onclick = "add_rm(this)">
                        </div>
                    `;
                        i++;
                    }

                    res_string += `</div>
                                 </div>
                        <input type="hidden" name=grp_name[] value="${group}">
							<div class="modal-footer">
								<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-success" onclick="onSaveChanges()">Save Changes</button>
                            </div>
                            </form>`;

                    document.getElementById("pills").innerHTML = res_string;
                    
                    i = 0;
                    while (i<grp_mems.length)
                    {
                        document.getElementById(grp_mems[i].fac_id).checked = true;
                        document.getElementById("lbl_" + grp_mems[i].fac_id).classList.remove("greyout");
                        document.getElementById("h_" + grp_mems[i].fac_id).disabled = false;
                        document.getElementById("hh_" + grp_mems[i].fac_id).disabled = false;
                        i++;
                    }
                }
            },
            error: function (x, s, err) {
                document.getElementById("pills").innerHTML = "<p style='color: grey; font-size: 15px;'><b>Error fetching data!</b></p>";
            }
        });
    }
}