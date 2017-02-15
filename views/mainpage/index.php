<!DOCTYPE html>
<html>
<head>
        <link rel="stylesheet" type="text/css" href="/css/mystyle.css">
</head>
<body>
<div class="brdrmLst">
    <?php
    foreach ($boardrooms as $room) {
        ?><a class='boardrooms'
             href="/mainpage/home/<?= $month ?>/<?= $year ?>/<?= $room->name ?>"><?= $room->name ?></a>
        <?php
    }
    ?>
    <a class="logout" href="/mainpage/logout/">Logout</a>
</div>
<div class="headline"><H1>
        <a class="homeUrl" href='/mainpage/home'>Boardroom Booker</a>
    </H1>
    <div>
        <a class=''
           href="/mainpage/home/<?= $prevMonth ?>/<?= ($prevMonth == 12) ? $year - 1 : $year ?>/<?= $currentBoardroom->name ?>">
            &#9668;</a>
        <?= date("F", mktime(0, 0, 0, $month, 1, $year)) . "-" . date("Y", mktime(0, 0, 0, $month, 1, $year)); ?>
        <a class=''
           href="/mainpage/home/<?= $nextMonth ?>/<?= ($nextMonth == 1) ? $year + 1 : $year ?>/<?= $currentBoardroom->name ?>">
            &#9658;</a>
    </div>
</div>
<div class="curBrdrm"><h3> <?= $currentBoardroom->name ?> </h3></div>
<div class="config">
   <form method="post" action="/mainpage/config/">
    <table border="0" style="border: hidden">
        <tr>
            <td>Time</td>
            <td><input type="radio" name="time" value="1" <?= (isset($_COOKIE["time"]) && $_COOKIE["time"]==1)?"checked=true":""; ?>> 12<br></td>
            <td><input type="radio" name="time" value="0" <?= (isset($_COOKIE["time"]) && $_COOKIE["time"]==0)?"checked":""; ?>> 24<br></td>
            <td rowspan="2"><input type="submit" style="width: 40px" value="ok"></td>
        </tr>
        <tr>
            <td>First day</td>
            <td><input type="radio" name="firstday" value="1" <?= (isset($_COOKIE["fdw"]) && $_COOKIE["fdw"]==1)?"checked":""; ?>>Sun<br></td>
            <td><input type="radio" name="firstday" value="0" <?= (isset($_COOKIE["fdw"]) && $_COOKIE["fdw"]==0)?"checked":""; ?>>Mon<br></td>
        </tr>

    </table>
    </form>
</div>
<div class="tableCal">
    <table border="0">
        <tr>
            <td><?= $helper ?></td>
            <td width="25%">
                <ul class="sideMenu">
                    <li><a href="/mainpage/addmeeting/<?= $currentBoardroom->name ?>">Book It!</a></li>
                    <li><a href='/employees/index'>Employee List</a></li>
                    <li><a href='/boardrooms/index'>Boardroom List</a></li>
                </ul>
            </td>
        </tr>
    </table>
</div>
<div class="modal" id="viewEvent">
    <div class="modal-container">
        <h1>B.B. Details</h1>
        <input class="detailForm" type="hidden" name="id" id="appointment_id">
        <p>
            <lable style="width: 75%; padding: 5px">When:</lable>
            <input id="meet_time_begin" name="meet_time_begin" class="detailForm"
                   style="width: 20%; padding: 5px; margin-left: 20px;" type="text">
            -
            <input id="meet_time_end" name="meet_time_end" class="detailForm" style="width: 20%; padding: 5px"
                   type="text">
        </p>
        <p>
            <lable style="width: 75%; padding: 5px">Note:</lable>
            <input id="note" name="note" class="detailForm" style="width: 50%; padding: 5px; margin-left: 20px;"
                   type="text">
        </p>
        <p>
            <lable style="width: 75%; padding: 5px ">Who:</lable>
            <!--            <input id="employee_id" name="employee_id" class="detailForm"-->
            <!--                   style="width: 50%; padding: 5px; margin-left: 20px;" type="text">-->
            <select id="employee_id" class="detailForm" required size="1" name="employee_id"
                    style="width: 50%; padding: 5px; margin-left: 20px;">
                <option value='0'></option>
                <?php foreach ($employees as $employee) { ?>
                    <option value="<?= $employee->id ?>"><?= $employee->fullname ?></option>
                <?php } ?>
            </select>
        </p>
        <p>
            <lable style="width: 75%; padding: 5px">submitted:</lable>
            <lable id="submitted" style="width: 75%; padding: 5px"></lable>
        </p>
        <p id="is_occurr_p">
            <input class="occurr" id="is_occurr" name="is_occurr" type="checkbox" disabled>
            <lable id="submitted" style="width: 75%; padding: 5px">Apply to all occurrences?</lable>
        </p>

        <p>
            <button id="updateEvent">Update</button>
            <button id="deleteEvent">Delete</button>
        </p>
        <a id="closeModal" href="#modal-close">Close</a>
    </div>
</div>

<script>
    const modalCloserEvent = new Event('click');

    //waiting for page loaded
    document.onreadystatechange = function () {
        if (document.readyState == "complete") {
            var updBtn = document.getElementById('updateEvent');
            var dltBtn = document.getElementById('deleteEvent');
            var meetBtn = document.getElementsByClassName('meeting');

            updBtn.addEventListener("click", function () {
                sendAjaxForm();
            });

            dltBtn.addEventListener("click", function () {
                deleteAppointment();
            });

            for (var i = 0; i < meetBtn.length; i++) {
                meetBtn[i].addEventListener("click", function () {
                    getDetail(this);
                });
            }

        }
    }
    //send ajax request for updating of selected(clicked) appointment
    function sendAjaxForm() {
        var allInputs = document.getElementsByClassName('detailForm');
        var isOccurr = document.getElementById('is_occurr');
        var detailForm = new FormData();
        var fields = [];
        for (var i = 0; i < allInputs.length; i++) {
            fields [i] = {
                name: allInputs[i].name,
                value: allInputs[i].value
            };
        }
        if(isOccurr.checked)
            fields [fields.length] = {name: isOccurr.name, value: "true"};

        detailForm.append("data", JSON.stringify(fields));
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function () {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                var response = JSON.parse(xmlHttp.responseText);
                //if php method answer is 'ok'
                //update info in table and close modal
                if (response.status === 'ok')
                {
                    var tb = response.data.beginTime,
                        te = response.data.endTime;

                    if(Array.isArray(response.allOccur))
                    {

                        for(var i=0; i<response.allOccur.length; i++){
                            var appEvent = document.getElementById('app_'+response.allOccur[i]);
                            if(appEvent)
                                appEvent.innerText =  tb + "-"+te;
                        }
                    }
                    document.getElementById('app_'+response.data.id).innerText =  tb + "-"+te;
                    window.location =document.getElementById('closeModal').getAttribute("href");
                }
                if(response.status === 'error')
                {
                    var allErrors="";
                    for(var key in response.error){
                        allErrors +=response.error[key] + "\n\r";
                        console.log(response);
                    }
                    alert(allErrors);
                }
            }
        }
        xmlHttp.open('post', '/mainpage/ajaxupdate/');
        xmlHttp.send(detailForm);

    }

    function deleteAppointment() {
        var result = confirm("Want to delete?");
        if (!result) {
            return;
        }

        var isOccurr = document.getElementById('is_occurr');
        var idAppoint = document.getElementById('appointment_id');
        var detailForm = new FormData();
        var fields =[];
        fields[0]={name:'id',value:idAppoint.value};
        if(isOccurr.checked)
            fields [1] = {name:'is_occurr',value:true};
        detailForm.append("data", JSON.stringify(fields));
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function () {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                var response = JSON.parse(xmlHttp.responseText);
                //if php method answer is 'ok'
                //update info in table and close modal
                if (response.status === 'ok')
                {
                    if(Array.isArray(response.allOccur))
                    {
                        for(var i=0; i<response.allOccur.length; i++){
                            var appEvent = document.getElementById('app_'+response.allOccur[i]);
                            if(appEvent)
                                appEvent.remove();
                        }
                    }
                    window.location =document.getElementById('closeModal').getAttribute("href");

                }
                if(response.status === 'error')
                {                }
            }
        }
        xmlHttp.open('post', '/mainpage/ajaxdelete/');
        xmlHttp.send(detailForm);

    }


    function getDetail(e) {
        var detailForm = new FormData();
        var xmlHttp = new XMLHttpRequest();
        var event_id = e.getAttribute('data-id');
        detailForm.append('event_id', event_id);
        xmlHttp.onreadystatechange = function () {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                var myJson = JSON.parse(xmlHttp.responseText);
                parseToForm(myJson);
            }
        }
        xmlHttp.open('post', '/mainpage/getdetail/');
        xmlHttp.send(detailForm);

    }

    function parseToForm(data) {
        var fieldNote = document.getElementById('note');
        var fieldBegin = document.getElementById('meet_time_begin');
        var fieldEnd = document.getElementById('meet_time_end');
        var fieldEmployee = document.getElementById('employee_id');
        var lblSubmitted = document.getElementById('submitted');
        var fieldAppointmentId = document.getElementById('appointment_id');
        var isOccurr = document.getElementById('is_occurr');
        var isOccurrP = document.getElementById('is_occurr_p');
        fieldAppointmentId.value    = data.appointment.id;
        fieldNote.value             = data.appointment.note;
        fieldBegin.value            = data.appointment.beginTime;
        fieldEnd.value              = data.appointment.endTime;
        fieldEmployee.value         = data.appointment.employeeId;
        lblSubmitted.innerHTML      = data.appointment.submited;
        isOccurr.disabled=true;
        isOccurrP.style.display="none";

        if(data.isrecurr==true){
            isOccurr.removeAttribute("disabled");
            isOccurrP.style.display="inline";
        }
        isOccurr.checked=false;
    }

</script>
</body>
</html>