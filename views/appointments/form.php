<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="/css/mystyle.css">
</head>
<body>

<div>
    <H1>
        <a class="homeUrl" href='/mainpage/home'>Boardroom Booker</a>
    </H1>
    <div class="curBrdrm"><h3> <?= $currentBoardroom->name ?> </h3></div>
</div>
<div style="position: absolute; top: 50px;">
    <?php
    $h12format = (isset($_COOKIE["time"]))?$_COOKIE["time"]:false;

    if(!empty($errors)){ ?><p class="errors"><b>Errors:</b>
        <ul> <?php
            foreach ($errors as $error) {

                ?>  <li><?= $error  ?></li>

            <?php  } ?>
        </ul>

        </p> <?php } ?>
    <form action="" method="POST" id="form">
        <input type="hidden" value="<?= $currentBoardroom->id ?>" name="boardroom_id">
        <p><b>Booked for:</b></p>

        <p>
            <select required size="1" name="employee_id">
                <option value='0'></option>


                <?php
                foreach ($employees as $employee) { ?>
                    <option value="<?= $employee->id ?>" <?= (($_POST) && $_POST['employee_id']==$employee->id )?"selected": "" ?>  ><?= $employee->fullname ?></option>

                <?php } ?>
            </select>
        </p>

        <p><b>I would like to book this meeting:</b></p>
        <p>
            <select id="meet_month" name="meet_month">
                <option value="0">Choose month...</option>
                <option value="1">January</option>
                <option value="2">February</option>
                <option value="3">March</option>
                <option value="4">April</option>
                <option value="5">May</option>
                <option value="6">June</option>
                <option value="7">July</option>
                <option value="8">August</option>
                <option value="9">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
            </select>

            <select id="meet_day" name="meet_day"></select>

            <?= CommonHelper::select_years("meet_year", "meet_year") ?>
        </p>

        <p><b>Specify what the time and the end of the meeting</b></p>

        <p>
            <?= $helper->timeSelect("meet_hour_begin", true, "meet_hour_begin",-1,$h12format); ?>
            <?= $helper->timeSelect("meet_minute_begin", false, "meet_minute_begin"); ?>
            <?php if(isset($_COOKIE["time"]) && $_COOKIE["time"]==1) { ?>

                <select id="am_pm_begin" name="am_pm_begin">
                    <option value=""> </option>
                    <option value="AM">AM</option>
                    <option value="PM">PM</option>
                </select>


            <?php } ?>

        </p>
        <p>
            <?= $helper->timeSelect("meet_hour_end", true, "meet_hour_end",-1,$h12format); ?>
            <?= $helper->timeSelect("meet_minute_end", false, "meet_minute_end"); ?>
            <?php if(isset($_COOKIE["time"]) && $_COOKIE["time"]==1) { ?>

            <select id="am_pm_end" name="am_pm_end">
                <option value=""> </option>
                <option value="AM">AM</option>
                <option value="PM">PM</option>
            </select>


        <?php } ?>


        </p>

        <p><b>Enter the specifics of the meeting</b></p>
        <p>
            <textarea name="note" id="note" cols="30" rows="5"></textarea>
        </p>

        <p><b>Is this going to be a recurring event</b></p>
        <p>
            <input type="radio" name="is_recurr" value="0"> No<br>
            <input type="radio" name="is_recurr" value="1"> Yes<br>
        </p>

        <p><b>If it is recurring, specify weekly, bi-weekly, or monthly.</b></p>
        <p>
            <input type="radio" name="period" value="1"> weekly<br>
            <input type="radio" name="period" value="2"> bi-weekly<br>
            <input type="radio" name="period" value="3"> monthly<br>
        </p>

        <p>If weekly or bi-weekly,specify the number of week for it to keep recurring. If monthly, specify the number of months.
            (if you choose "bi-weekly" and put im an odd number of weeks, the computer will round down.)
        </p>
        <p>
            <input type="number" name="period_number" > duration (max 4 weeks)
        </p>

        <button type="submit">SUBMIT</button>

    </form>


</div>
<script src="/js/addapptmt.js"></script>
</body>
</html>

