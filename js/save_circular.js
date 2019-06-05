function save_circular()
{
    
}

// $("#mail_check_input").click(() => 
function mailCheck(checkbox)
{
    if (!checkbox.checked)
    {
        document.getElementById('mail_check_input').value = 0;
        document.getElementById('mail_details').style.display = "none";
        document.getElementById('groups').disabled = true;
        document.getElementById('pwd').disabled = true;
        document.getElementById('savebtn').disabled = false;
    }
    else
    {
        document.getElementById('mail_check_input').value = 1;
        document.getElementById('mail_details').style.display = "";
        document.getElementById('groups').disabled = false;
        document.getElementById('pwd').disabled = false;
        document.getElementById('savebtn').disabled = true;
    }

}