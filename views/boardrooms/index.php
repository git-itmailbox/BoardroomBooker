<!DOCTYPE html>
<html>
<head>
        <link rel="stylesheet" type="text/css" href="/css/mystyle.css">
</head>
<body>
<div><H1>
        <a class="homeUrl" href='/mainpage/home'>Boardroom Booker</a>
    </H1>
</div>
<p>Boardroom list:</p>
<table class="employee" border="1" width="30%" >
    <tr>
        <th >Default?</th>
        <th>Name</th>
        <th colspan="2">Actions</th>
    <tr>
        <?php foreach ($boardrooms as $boardroom) { ?>
    <tr>
        <td>
        <?php if($boardroom->is_default==1) echo " <b> 	&#10004;</b> "; ?>
        </td>
         <td>
        <?php echo $boardroom->name;  ?>
        </td>

        <td><a href='/boardrooms/edit/<?php echo $boardroom->id; ?>'>edit</a></td>
        <td><a href='#' class="dltBoardroom" data-id="<?= $boardroom->id; ?>" id="dltBoardroom_<?= $boardroom->id; ?>">delete</a>
        </td>
    </tr>
    <?php } ?>
</table>
<p>
    <a class="" href='/boardrooms/add'>Add new Boardroom</a>
</p>

<script>
    //waiting for page loaded
    document.onreadystatechange = function () {
        if (document.readyState == "complete") {
            var dltBtns = document.getElementsByClassName("dltBoardroom");

            for (var i = 0; i < dltBtns.length; i++) {
                dltBtns[i].addEventListener("click", function () {
                    deleteBoardroom(this);
                });
            }
        }
    }

    function deleteBoardroom(e) {
        var result = confirm("Are you sure you want to delete this boardroom?");
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
        xmlHttp.open('post', '/boardrooms/ajaxdelete/');
        xmlHttp.send(dltForm);

    }
</script>

</body>
</html>
