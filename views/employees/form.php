<?php
if(!empty($errors)){ ?><p class="errors"><b>Errors:</b>
    <ul> <?php
        foreach ($errors as $error) {

            ?>  <li><?= $error  ?></li>

        <?php  } ?>
    </ul>

    </p> <?php } ?>
<form action="<?=$action ?>" method="POST"><table>
    <tr>
        <th>Full name</th>
        <th>Email</th>
    </tr>
    <tbody>
    <tr>
        <td><input type="text" name="fullname" value="<?= (isset($employee))?$employee->fullname:""; ?>"></td>
        <td><input type="text" name="email" value="<?= (isset($employee))?$employee->email:""; ?>"></td>
    </tr>
    </tbody>
</table>
<input type="hidden" name="id" value="<?= (isset($employee))?$employee->id:""; ?>">
<button type="submit">SUBMIT</button>

</form>