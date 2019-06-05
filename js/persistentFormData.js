function pseudo(error, page)
{
    if (typeof (Storage) !== "undefined") 
    {
        var store;

        if(!error)
        {
            if(page == 'meeting')
            {
                setCurTime();
                store = setInterval(saveToLocalStorage, 2000);
            }
            else
            {
                setCurTime();
                store = setInterval(circ_saveToLocalStorage, 2000);
            }
        }
        else
        {
            if (page == 'meeting') 
            {
                clearInterval(store);
                retrieveFromStorage();
                store = setInterval(saveToLocalStorage, 2000);
            }
            else
            {
                clearInterval(store);
                retrieveFromStorage();
                store = setInterval(circ_saveToLocalStorage, 2000);
            }
        }
    }
}

function setCurTime()
{
    var date = new Date();
    var hours = date.getHours() < 10 ? "0" + date.getHours() : date.getHours();
    var minutes = date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();
    time = hours + ":" + minutes;
    document.getElementById("time").value = time;
}


function saveToLocalStorage()
{
    sessionStorage.setItem("date", document.getElementById('date').value);
    sessionStorage.setItem("time", document.getElementById('time').value);
    sessionStorage.setItem("venue", document.getElementById('venue').value);
    sessionStorage.setItem("agenda", document.getElementById('agenda').value);
    sessionStorage.setItem("minutes", document.getElementById('minutes').value);
}

function retrieveFromStorage()
{
    document.getElementById('date').value = sessionStorage.getItem("date");
    document.getElementById('time').value = sessionStorage.getItem("time");
    document.getElementById('venue').value = sessionStorage.getItem("venue");
    document.getElementById('agenda').value = sessionStorage.getItem("agenda");
    document.getElementById('minutes').value = sessionStorage.getItem("minutes");
}

function circ_saveToLocalStorage()
{
    sessionStorage.setItem("cdate", document.getElementById('cdate').value);
    sessionStorage.setItem("agenda", document.getElementById('agenda').value);
    sessionStorage.setItem("venue", document.getElementById('venue').value);
    sessionStorage.setItem("mdate", document.getElementById('mdate').value);
    sessionStorage.setItem("time", document.getElementById('time').value);
}

function circ_retrieveFromStorage()
{
    document.getElementById('cdate').value = sessionStorage.getItem("cdate");
    document.getElementById('agenda').value = sessionStorage.getItem("agenda");
    document.getElementById('venue').value = sessionStorage.getItem("venue");
    document.getElementById('mdate').value = sessionStorage.getItem("mdate");
    document.getElementById('time').value = sessionStorage.getItem("time");
}