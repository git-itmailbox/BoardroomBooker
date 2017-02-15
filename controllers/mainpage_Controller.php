<?php
require_once('models/appointments.php');
require_once('models/employees.php');
require_once('models/boardrooms.php');
require_once('views/helpers/common.php');
require_once('models/users.php');
require_once('Auth.php');

/**
 * Created by PhpStorm.
 * User: yura
 * Date: 06.02.17
 * Time: 22:26
 */
class MainPageController
{
    private $Month_r = array(
        "1" => "JANUARY",
        "2" => "февраль",
        "3" => "март",
        "4" => "апрель",
        "5" => "май",
        "6" => "июнь",
        "7" => "июль",
        "8" => "август",
        "9" => "сентябрь",
        "10" => "октябрь",
        "11" => "ноябрь",
        "12" => "декабрь");


    private $args;

    private $currnetBoardroom;

    function __construct($args)
    {
        $this->args = $args;

    }

    public function getFilledMonthArray($month = 0, $year = 0)
    {

        $m = ($month > 0 && $month < 13) ? $month : $month = date('m');
        $y = ($year > 1970) ? $year : $year = date('y');
        $first_of_month = mktime(0, 0, 0, $m, 1, $y);
        $daysInMonth = date('t', $first_of_month);
        $filledMonthByWeekArray = [
            0 => [],
            1 => [],
            2 => [],
            3 => [],
            4 => [],
            5 => [],
            6 => [],
        ];

        for ($i = 1; $i <= $daysInMonth; $i++) {
            $dateInfo = getdate(mktime(0, 0, 0, $m, $i, $y));
            $dayOfWeek = $dateInfo['wday'];
            foreach ($filledMonthByWeekArray as $key => $val) {
                if ($dayOfWeek == $key) $filledMothByWeekArray[$key][] = $i;

            }
        }
        return $filledMonthByWeekArray;
    }

    public function getCurrentBoardroom($arg)
    {
        $boardrooms = Boardroom::all();
        $currentBoardroom = false;
        foreach ($boardrooms as $boardroom) {
            if ($boardroom->name == $arg) {
                $currentBoardroom = $boardroom;
                return $currentBoardroom;
            }
        }
        return $currentBoardroom;

    }

    public function config()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // create model obj
            $user = Users::findByLogin($_COOKIE["login"]);
        }
            if (isset($_POST['time']) && $_POST['time'] == 1) {
            //пишем в базу и в куки флаг 12ч
                $user->updateTimeMode('1');
                //устанавливаем куки на месяц (например)
                setcookie("time", "1", time()+3600*24*30, "/");

            } else if (isset($_POST['time']) && $_POST['time'] != 1) {
            //пишем в базу и в куки флаг 24ч
                $user->updateTimeMode('0');
                setcookie("time", "0", time()+3600*24*30, "/");
            }
        if (isset($_POST['firstday']) && $_POST['firstday'] == 1) {
            //пишем в базу и в куки флаг 12ч
            $user->updateFirstDayWeek("1");
            setcookie("fdw", "1", time()+3600*24*30, "/");

        } else if (isset($_POST['firstday']) && $_POST['firstday'] != 1) {
            //пишем в базу и в куки флаг 24ч
            $user->updateFirstDayWeek("0");
            setcookie("fdw", "0", time()+3600*24*30, "/");
        }
        header("Location:/mainpage/home");
    }

    public function home()
    {

        $employees = Employee::all();

        $boardrooms = Boardroom::all();
        //Надо выбрать комнату поумолчанию, если даже такова не указана будет выбрана последняя
        foreach ($boardrooms as $boardroom) {
            $default_boardroom = $boardroom;
            //как только найдем флаг is_default - цель достигнута, дальше искать не нужно
            if ((int)$boardroom->is_default == 1) break;
        }
        $currentBoardroom = $default_boardroom;
        $year = date('Y');
        $month = date('m');

        if (isset($this->args[4]) && (int)$this->args[4] > 1970) {
            $year = (int)$this->args[4];
        }
        if (isset($this->args[3]) && (int)$this->args[3] <= 12) {
            $month = (int)$this->args[3];
        }

        if (isset($this->args[5]))
            $currentBoardroom = self::getCurrentBoardroom($this->args[5]);


        if ($month == '12') $next_year = $year + 1;
        else                $next_year = $year;

        switch ($month) {
            case 12:
                $nextMonth = 1;
                $prevMonth = $month - 1;
                break;
            case 1:
                $prevMonth = 12;
                $nextMonth = $month + 1;
                break;
            default:
                $prevMonth = $month - 1;
                $nextMonth = $month + 1;
                break;
        }

        $weekMode = (isset($_COOKIE["fdw"]))?(bool)$_COOKIE["fdw"]:false;
        $timeMode = (isset($_COOKIE["time"]))?(bool)$_COOKIE["time"]:false;
        $appointments = Appointments::all($currentBoardroom->id, $month, $year, $timeMode);
        $helper = CommonHelper::printCalendar($appointments, $month, $year,$weekMode);

        require_once('views/mainpage/index.php');
    }

    public
    function error()
    {
        require_once('views/mainpage/error.php');
    }

    public function addmeeting()
    {
        $employees = Employee::all();
        $boardrooms = Boardroom::all();
        $appointment = new Appointments();

        $currentBoardroom = self::getCurrentBoardroom($this->args['3']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $month = (isset($_POST['meet_month'])) ? $_POST['meet_month'] : "";
            $day = (isset($_POST['meet_day'])) ? $_POST['meet_day'] : "";
            $year = (isset($_POST['meet_year'])) ? $_POST['meet_year'] : "";
            $meet_date = DateTime::createFromFormat("Y-m-d", $year . "-" . $month . "-" . $day);
            $meet_dates = ($meet_date) ? [$meet_date->format("Y-m-d")] : "";
            $appointment->employeeId = $_POST['employee_id'];
            $strtime=  $_POST['meet_hour_begin'].':'. $_POST['meet_minute_begin'];
            $strtime .=($_POST['am_pm_begin'] && $_POST['am_pm_begin']!=='')?' '.$_POST['am_pm_begin']:'';
            $appointment->beginTime =date('H:i', strtotime($strtime) ); 
            $strtime=  $_POST['meet_hour_end'].':'. $_POST['meet_hour_end'];
            $strtime .=($_POST['am_pm_end'] && $_POST['am_pm_end']!=='')?$_POST['am_pm_end']:'';
            $appointment->endTime =date('H:i',strtotime($strtime)); 
            $appointment->note = $_POST['note'];
            $appointment->submited = date("Y-m-d H:i:s");
            $appointment->boardroom_id = $_POST['boardroom_id'];

            //validation
            $errors = $appointment->validate($_POST);
            //if there is no errors go on
            if (empty($errors)) {
                //if recurring event - get array of dates
                if (isset($_POST['is_recurr']) && $_POST['is_recurr'] === '1') {

                    $meet_dates = $appointment->getReccuringMeetingDates($meet_date, [
                        'is_recurr' => $_POST['is_recurr'],
                        'period' => $_POST['period'],
                        'period_number' => $_POST['period_number'],
                    ]);
                }
                //get errors if boardroom is unavailable at this(these) DateTime ?
                $appointment->checkOnConflictDates($meet_dates, $appointment->beginTime, $appointment->endTime, $appointment->boardroom_id);

                $appointment->meetDate = $meet_dates;
                if (empty($appointment->getErrors()) && $appointment->save())
                    header("Location:/mainpage/home");
            }

        }
        $helper = new CommonHelper();


        require_once('views/appointments/form.php');
    }

    public function ajaxupdate()
    {
        $appointment = new  Appointments();
        $data = json_decode($_POST['data']);
        $fetchedData = [];
        foreach ($data as $datum) {
            $fetchedData[$datum->name] = $datum->value;
        }
        $appointment = Appointments::find($fetchedData['id']);


        //check format of begin/end time
        $appointment->validateTime($fetchedData['meet_time_begin']);
        $appointment->validateTime($fetchedData['meet_time_end']);

        if (isset($fetchedData['is_occurr'])) {

            return $this->updateAllOccurrences($appointment, $fetchedData);
        }

        return $this->updateOne($appointment, $fetchedData);

    }

    public function updateOne(Appointments $appointment, $fetchedData)
    {
        $confilcts = $appointment->checkOnConflictDates([$appointment->meetDate], $fetchedData['meet_time_begin'], $fetchedData['meet_time_end'], $appointment->boardroom_id, [$fetchedData['id']]);
        if (empty($appointment->getErrors()) && $appointment->update($fetchedData)) {
            echo json_encode(['status' => 'ok', 'data' => Appointments::find($fetchedData['id'])]);
            return;
        }
        echo json_encode(['status' => 'error', 'error' => $appointment->getErrors()]);
    }

    public function updateAllOccurrences(Appointments $appointment, $fetchedData)
    {
        $allOtherOccurr = $appointment->findRecurring($appointment->created_by);
        $ids = [];
        $meet_dates = [];
        foreach ($allOtherOccurr as $eachOccurr) {
            $ids[] = $eachOccurr['id'];
            $meet_dates[] = $eachOccurr['meet_date'];
        }
        $confilcts = $appointment->checkOnConflictDates($meet_dates, $fetchedData['meet_time_begin'], $fetchedData['meet_time_end'], $appointment->boardroom_id, $ids);
//        var_dump($confilcts); exit;
        if (empty($appointment->getErrors()) && $appointment->updateAllOccurrences($fetchedData, $ids)) {
            echo json_encode(['status' => 'ok', 'data' => Appointments::find($fetchedData['id']), 'allOccur' => $ids]);
            return;
        }

        echo json_encode(['status' => 'error', 'error' => $appointment->getErrors(), 'allOccur' => $ids]);
    }

    public function getdetail()
    {
        $appointment = Appointments::find($_POST['event_id']);

        //check if its recurring event
        $recurringAppointments = $appointment->findRecurring($appointment->created_by);
        $isRecurr = (empty($recurringAppointments)) ? false : true;
        echo json_encode(['appointment' => $appointment, 'isrecurr' => $isRecurr]);

    }

    public function ajaxdelete()
    {
        $ids = [];

        $data = json_decode($_POST['data']);
        $fetchedData = [];
        foreach ($data as $datum) {
            $fetchedData[$datum->name] = $datum->value;
        }
        $appointment = Appointments::find($fetchedData['id']);

        //if need too delete all occurrences then find them and put their id in  $ids[]
        //else put  into $ids[] only one id
        //then send a query
        if (isset($fetchedData['is_occurr'])) {
            $recurringAppointments = $appointment->findRecurring($appointment->created_by);
            foreach ($recurringAppointments as $row) {
                $ids[] = $row['id'];
            }
        } else {
            $ids[] = $fetchedData['id'];
        }

        if ($appointment->delete($ids)) {
            echo json_encode(['status' => 'ok', 'allOccur' => $ids]);
            return;
        } else {
            echo json_encode(['status' => 'error', 'error' => "Cant delete this Event", 'allOccur' => $ids]);

        }
    }
    
 public function logout()
 {
    Auth::clearAuthCookie();
    header("Location:/mainpage/home");
 }
}