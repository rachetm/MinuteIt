function disable() {
    document.getElementById("savebtn").disabled = true;
}

function new_meeting(){
    setTimeout(() => {
        let select = document.getElementById("groups");
        select.addEventListener("change", function () {
            group = document.getElementById('groups').value;
            display_mem_select(group);
        });
    }, 2000);
}

function display_mem_select(group)
{
    if (group == "")
    {
        document.getElementById("savebtn").disabled = true;
        document.getElementById("errmsg").innerHTML = "Please select a group";
        document.getElementById("names").innerHTML = "";
        return;
    }
    else 
    {
        document.getElementById("names").innerHTML = "<p style='color: grey; font-size: 15px;'><b>Loading...</b></p>";
        document.getElementById("errmsg").innerHTML = "";
        grp = group.replace(" ", "_");
        $.ajax({
            type: "GET",
            url: "fetch_grp_members.php",
            data: { grp : group},
            success: function (data, status) {
                document.getElementById("names").innerHTML = "<p style='color: grey; font-size: 15px;'><b>Failed to load!</b></p>";
                if (data == "error")
                {
                    document.getElementById("savebtn").disabled = true;
                    document.getElementById("names").innerHTML = "<p style='color: grey; font-size: 15px;'><b>Error fetching data!</b></p>";
                }
                else if (data == "empty")
                {
                    document.getElementById("savebtn").disabled = true;
                    document.getElementById("names").innerHTML = `<p style='color: grey; font-size: 15px; padding-left:15px;'><b>No members present in this group<b><br>
                    You can add memebers by going to 'Manage Group Members' above.</p>`;
                }
                else 
                {
                    var resultvar = JSON.parse(data);
                    var res_string = '';
                    var i = 0;
                    while (resultvar[i]) {
                        res_string += "<div class=\"form-row form-group ml-1\"> <label class=\"names\" id=\"lbl_" + resultvar[i].fac_id + "\" for=\"" + resultvar[i].fac_id + "\">" + resultvar[i].fac_name + "</label> <input type=\"hidden\" id=\"h_" + resultvar[i].fac_id + "\" name=names[] value=\"" + resultvar[i].fac_name + "\"> <input type=\"hidden\" id=\"hh_" + resultvar[i].fac_id + "\" name=emails[] value=\"" + resultvar[i].fac_email + "\"> <input type=\"checkbox\" class=\"form-check-input col-lg-6 ml-3\" id=\"" + resultvar[i].fac_id + "\" name=\"attendees[]\" value=\"" + resultvar[i].fac_id + "\" checked onclick=\"add_rm(this)\"> </div>";
                        i++;
                    }
                    document.getElementById("names").innerHTML = res_string;
                    document.getElementById("savebtn").disabled = false;
                }
            },
            error: function (x, s, err) {
                document.getElementById("savebtn").disabled = true;
                document.getElementById("names").innerHTML = "<p style='color: grey; font-size: 15px;'><b>Error fetching data!</b></p>";
            }
        });
    }
}

function add_rm(checkbox)
{
    let temp;
    checkbox.checked ? temp = 1 : temp = 0;

    if(temp)
    {
        document.getElementById("h_" + checkbox.id).disabled = false;
        document.getElementById("hh_" + checkbox.id).disabled = false;
        document.getElementById("lbl_" + checkbox.id).classList.remove("greyout");
    }
    else
    {
        document.getElementById("h_" + checkbox.id).disabled = true;
        document.getElementById("hh_" + checkbox.id).disabled = true;
        document.getElementById("lbl_" + checkbox.id).classList.add("greyout");
    }
    
}

