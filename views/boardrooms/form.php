<?php
if(!empty($errors)){ ?><p class="errors" style="color:'red';"><b>Errors:</b>
    <ul> <?php
        foreach ($errors as $error) {

            ?>  <li><?= $error  ?></li>

        <?php  } ?>
    </ul>

    </p> <?php } ?>
<form action="<?=$action ?>" method="POST"><table>
    <tr>
        <th>Name</th>
        <th>Make default?</th>
    </tr>
    <tbody>
    <tr>
        <td><input type="text" name="name" value="<?= (isset($boardroom))?$boardroom->name:""; ?>"></td>
        <td> <input type="checkbox" name="is_default" value="1" <?= (isset($boardroom->is_default) && $boardroom->is_default==1)? "checked":""; ?>> <br></td>
    </tr>
    </tbody>
</table>
<input type="hidden" name="id" value="<?= (isset($boardroom))?$boardroom->id:""; ?>">
<button type="submit">SUBMIT</button>

</form>