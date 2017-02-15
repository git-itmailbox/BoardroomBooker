<div><H1>
        <a class="homeUrl" href='/mainpage/home'>Boardroom Booker</a>
    </H1>
</div>
<p>Employee list:</p>
<table width="50%">
    <tr>
        <th>Fullname</th>
        <th>Email</th>
        <th colspan="2">Actions</th>
    <tr>
        <?php foreach ($employees as $employee) { ?>
    <tr>
        <td><?php echo $employee->fullname; ?></td>
        <td><?php echo $employee->email; ?></td>
        <td><a href='/employees/edit/<?php echo $employee->id; ?>'>edit</a></td>
        <td><a href='#' class="dltEmployee" data-id="<?= $employee->id; ?>" id="dltEmployee_<?= $employee->id; ?>">delete</a>
        </td>
    </tr>
    <?php } ?>
</table>
<p>
    <a class="" href='/employees/add'>Add new Employee</a>
</p>

<script>


    //waiting for page loaded
    document.onreadystatechange = function () {
        if (document.readyState == "complete") {
            var dltBtns = document.getElementsByClassName("dltEmployee");

            for (var i = 0; i < dltBtns.length; i++) {
                dltBtns[i].addEventListener("click", function () {
                    deleteEmployee(this);
                });
            }
        }
    }

    function deleteEmployee(e) {
        var result = confirm("Are you sure you want to delete this contact?");
        if (!result) {
            return;
        }
        var getId = e.id.split('_');
        console.log(e.parentElement.parentElement);
        e.parentElement.parentElement.remove();
        var dltForm = new FormData();
        dltForm.append('id', getId[1]);

        var xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function () {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                var response = JSON.parse(xmlHttp.responseText);
                //if php method answer is 'ok'
                //update info in table and close modal
                if (response.status === 'ok') {
                    alert(response.message)
                }
                if (response.status === 'error') {
                }
            }
        }
        xmlHttp.open('post', '/employees/ajaxdelete/');
        xmlHttp.send(dltForm);

    }
</script>