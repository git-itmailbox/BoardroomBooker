<?php

/**
 * Created by PhpStorm.
 * User: yura
 * Date: 07.02.17
 * Time: 1:58
 */
class Appointments
{
    public   $id,
             $employeeId,
             $endTime,
             $beginTime,
             $note,
             $meetDate,
             $submited,
             $boardroom_id,
             $created_by;

    private $db;
    private $errors = [];

    public function __construct($attributes = [])
    {
        $attributes = (object)$attributes;

        $this->id = (isset($attributes->id)) ? $attributes->id : "";
        $this->employeeId = (isset($attributes->employee_id)) ? $attributes->employee_id : "";
        $this->endTime = (isset($attributes->end_time)) ? $attributes->end_time : "";
        $this->beginTime = (isset($attributes->begin_time)) ? $attributes->begin_time : "";
        $this->note = (isset($attributes->note)) ? $attributes->note : "";
        $this->meetDate = (isset($attributes->meet_date)) ? $attributes->meet_date : "";
        $this->submited = (isset($attributes->submited)) ? $attributes->submited : "";
        $this->boardroom_id = (isset($attributes->boardroom_id)) ? $attributes->boardroom_id : "";
        $this->created_by = (isset($attributes->created_by)) ? $attributes->created_by : "";


        $this->db = Db::getInstance();

    }

    public function getErrors(){
      return $this->errors;
    }
    
    //get all appointments
    public static function all($boardroom_id = 1,  $month=0, $year = 0, $mode = false)
    {

        $month  =($month==0)? date('m'): $month;
        $year   =($year==0)?  date('Y'): $year;
        //var_dump($month, $year); exit;
        $format = ($mode) ? "\"%h:%i%p\"" : "\"%H:%i\"";
        $list = [];
        $db = Db::getInstance();
        $req = $db->query("SELECT id,employee_id,DATE_FORMAT(end_time,$format) as end_time,   
                                                      DATE_FORMAT(begin_time,$format) as begin_time,  
                                  note,submited,meet_date,boardroom_id FROM appointments
                                  WHERE boardroom_id = $boardroom_id and MONTH(meet_date)=$month AND YEAR(meet_date)=$year");

        foreach ($req->fetchAll() as $appointment) {
            $list[] = new Appointments([
                'id' => $appointment['id'],
                'employee_id' => $appointment['employee_id'],
                'end_time' => $appointment['end_time'],
                'begin_time' => $appointment['begin_time'],
                'note' => $appointment['note'],
                'submited' => $appointment['submited'],
                'meet_date' => $appointment['meet_date'],
                'boardroom_id' => $appointment['boardroom_id'],
            ]);
        }
        return $list;
    }


    public function update($data)
    {
        try{
            $curDate=date("Y-m-d H:i:s");
            $db = Db::getInstance();
            $sql = "UPDATE appointments SET note = :note, employee_id = :employee_id, end_time = :end_time, begin_time = :begin_time, submited = '$curDate' WHERE id = :id ";

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':note',$data['note']);
            $stmt->bindValue(':employee_id',$data['employee_id']);
            $stmt->bindValue(':begin_time',$data['meet_time_begin']);
            $stmt->bindValue(':end_time',$data['meet_time_end']);
            $stmt->bindValue(':id',$data['id']);

            return $stmt->execute();
        }
        catch (Exception $e){
            die('Cant update, error:' . $e);
        }


    }


    public function updateAllOccurrences($data, $ids)
    {
        $strIds = implode(",", $ids);
        try{
            $curDate=date("Y-m-d H:i:s");
            $db = Db::getInstance();
            $sql = "UPDATE appointments SET note = :note, employee_id = :employee_id, end_time = :end_time, begin_time = :begin_time, submited = '$curDate' WHERE id IN ($strIds) ";

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':note',$data['note']);
            $stmt->bindValue(':employee_id',$data['employee_id']);
            $stmt->bindValue(':begin_time',$data['meet_time_begin']);
            $stmt->bindValue(':end_time',$data['meet_time_end']);
//            $stmt->bindValue(':id',$data['id']);

            return $stmt->execute();
        }
        catch (Exception $e){
            die('Cant update, error:' . $e);
        }


    }


    public static function find($id, $mode=false) {

        $format = ($mode) ? "\"%h:%i%p\"" : "\"%H:%i\"";

        $db = Db::getInstance();
        // we make sure $id is an integer
        $id = intval($id);
        $req = $db->prepare("SELECT id, employee_id, DATE_FORMAT(end_time,$format) as end_time, 
                              DATE_FORMAT(begin_time,$format) as begin_time, note,meet_date,submited ,boardroom_id, created_by
                              FROM appointments WHERE id = :id");
        // the query was prepared, now we replace :id with our actual $id value
        $req->execute(array('id' => $id));
        $appointment = $req->fetch();
        return new Appointments([
            'id' => $appointment['id'],
            'employee_id' => $appointment['employee_id'],
            'end_time' => $appointment['end_time'],
            'begin_time' => $appointment['begin_time'],
            'meet_date' => $appointment['meet_date'],
            'boardroom_id' => $appointment['boardroom_id'],
            'note' => $appointment['note'],
            'created_by' => $appointment['created_by'],
            'created_by' => $appointment['created_by'],
        ]);
    }


    //calculate recurring dates of event, depends on params
    public function getReccuringMeetingDates($date, $reccurParams = [])
    {
        $meet_dates = [];

        $pNum = (int)($reccurParams['period_number']);
        $meet_dates[] = $date->format("Y-m-d");
        switch ($reccurParams['period']) {
            case '1':               //weekly, add 7 days
                for ($i = 1; $i < $pNum; $i++) {
                    $date->add(new DateInterval("P7D"));
                    $meet_dates[] = $date->format("Y-m-d");
                }

                break;
            case '2':               //bi-weekly, add 14 days
                $pNum = ($pNum % 2 == 0) ? $pNum / 2 : ($pNum - 1) / 2;
                for ($i = 1; $i < $pNum; $i++) {
                    $date->add(new DateInterval("P14D"));
                    $meet_dates[] = $date->format("Y-m-d");
                }
                break;
            case '3':               //monthly, add 1 months
                for ($i = 1; $i < $pNum; $i++) {
                    $m = $date->format('m');
                    $y = $date->format('Y');

                    $countMonthsDays = date('t', mktime(0, 0, 0, $m, 1, $y));
                    $date->add(new DateInterval("P" . $countMonthsDays . "D"));
                    $meet_dates[] = $date->format("Y-m-d");
                }
                break;
            default:
                break;
        }

        return $meet_dates;
    }



    public function checkOnConflictDates($dates, $meet_begin, $meet_end, $boardroom_id, $updateArrayId=[])
    {
        $db = Db::getInstance();
        $str = "'" . implode("','", $dates). "'";
        $sql = "SELECT * FROM appointments WHERE meet_date in($str) AND (appointments.begin_time <= :meet_end AND appointments.end_time >= :meet_begin)
        AND appointments.boardroom_id=:boardroom_id {IFUPDATING};";
        $ifUpdating = "";
        //при обновлении нужно исключить в выборке обновляемые ИД, иначе постоянно будет конфликт самих с собой
        if(!empty($updateArrayId)){
            $strIds = implode(",", $updateArrayId);
            $ifUpdating = "AND id NOT IN ($strIds)";
        }
        $sql = str_replace("{IFUPDATING}",$ifUpdating,$sql);
        $stmt = $db->prepare($sql);
        $res = $stmt->execute([':meet_end' => $meet_end, ':meet_begin' => $meet_begin, ':boardroom_id' => $boardroom_id]);
        $list = [];
        foreach ($stmt->fetchAll() as $appointment) {
            $list[] = new Appointments([
                'id' => $appointment['id'],
                'employee_id' => $appointment['employee_id'],
                'end_time' => $appointment['end_time'],
                'begin_time' => $appointment['begin_time'],
                'note' => $appointment['note'],
                'submited' => $appointment['submited'],
                'meet_date' => $appointment['meet_date'],
                'boardroom_id' => $appointment['boardroom_id'],
            ]);
        }
        if ($list)
        {
            $conflDates = [];
            foreach ($list as $dateConflict) {
                $conflDates[] = $dateConflict->meetDate;
            }
            $this->errors['conflict_date'] = "<p>There is a conflict at this interval of time with date(s): (" . implode(', ', $conflDates) . "). Try to choose another date or time</p>";
        }
        return  $this->errors;
    }

    public function getNextAutoincrement()
    {
        $db = Db::getInstance();
        $res=$db->query("SHOW TABLE STATUS LIKE 'appointments'");
//        $next = $res->fetch(PDO::FETCH_ASSOC);
        $next = $res->fetch(PDO::FETCH_OBJ);
        return $next->Auto_increment;
    }

    public function findRecurring($id)
    {
        $db = Db::getInstance();
        $res=$db->query("SELECT id, meet_date FROM appointments WHERE created_by = '$id'");
        return $res->fetchAll(PDO::FETCH_ASSOC);

    }

    public function save()
    {
        $db = Db::getInstance();
        $nextId = self::getNextAutoincrement();
        $sql = "INSERT INTO appointments(employee_id,begin_time, end_time, meet_date, note, submited, boardroom_id, created_by) 
                VALUES (:employee_id,:begin_time, :end_time, :meet_date, :note, :submited, :boardroom_id, $nextId)";
        $stmt = $db->prepare($sql);

        foreach ($this->meetDate as $meetDate){
            $stmt->bindParam(':employee_id', $this->employeeId);
            $stmt->bindParam(':begin_time', $this->beginTime);
            $stmt->bindParam(':end_time', $this->endTime);
            $stmt->bindParam(':meet_date', $meetDate );
            $stmt->bindParam(':note', $this->note);
            $stmt->bindParam(':submited', $this->submited);
            $stmt->bindParam(':boardroom_id', $this->boardroom_id);

            $stmt->execute();
        }
//        var_dump($stmt);
        return $db->lastInsertId();
//        return $db-
    }

    public function validateFormOnRequired($post)
    {

        //check if begintime and endtime are set
        if (!isset($post['meet_hour_end'])
            || !isset($post['meet_minute_end'])
            || !isset($post['meet_hour_begin'])
            || !isset($post['meet_minute_begin'])
        ) {
            $this->errors['time_event_require'] = "Please set begin time and end time of your event";
        }

        //check if the date of the event is set
        if (!isset($post['meet_month']) || !isset($post['meet_day']) || !isset($post['meet_year']))
            $this->errors['meet_date_require'] = "Please specify event date";

        //check if recurr params are set
        if (isset($post['is_recurr']) && $post['is_recurr']==='1' && (!isset($post['period']) || !isset($post['period_number']))) {
            $this->errors['recurr_params_require'] = "If the event is recurring, specify how often and for what period it will be repeat, please.";
        }
        if (!isset($_POST['note']))
        {
            $this->errors['note_require'] = "Please specify the note of event";

        }
        if (!isset($_POST['boardroom_id']))
        {
            $this->errors['boardroom_required'] = "Please specify the boardroom for event";

        }

        return $this->errors;
    }


    public function validateTime($str, $mode=false){
        $pattern24 = "/(^2[0-3]|^[01]?[0-9]):([0-5][0-9])$/";
        $pattern12 = "/(^1[012]|^0[0-9]):([0-5][0-9])$/";
        //по у молчанию пусть будет 24 часа
        $pattern = ($mode)? $pattern12:$pattern24;
        $arr=explode(":",$str);
        if (preg_match($pattern, $str))
            return true;
        $this->errors['invalid_time'] = "Invalid time";
    return false;
    }

    public function validate($post)
    {
        if(self::validateFormOnRequired($post)) return $this->errors;

        $month=      $_POST['meet_month'] ;
        $day=        $_POST['meet_day']   ;
        $year=       $_POST['meet_year']  ;
        $meet_date = DateTime::createFromFormat("Y-m-d", $year."-".$month."-".$day);

        //check if the date of the event is in past
        if (new DateTime('now') > $meet_date)
            $this->errors['past_date'] = "You can't choose date for event in past, sorry.";

        //check if event time is viable
        if ($this->beginTime >= $this->endTime)
                   $this->errors['viable_time'] = "Please choose viable time. Endtime must be greater than Begintime";

        return $this->errors;

    }


    public function delete($ids=[])
    {
        if(!$ids) $this->errors['no_id']= "No id given in array for deleting";
        $strIds = implode(",", $ids);
        $db = Db::getInstance();
        $sql = "DELETE FROM appointments WHERE id IN ($strIds)";
        $res = $db->query($sql);
        return $res;
    }
    
     public function deleteByBoardroomId($boardroom_id)
    {
        $db = Db::getInstance();
        $sql = "DELETE FROM appointments WHERE boardroom_id =:boardroom_id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':boardroom_id',$boardroom_id);
        return $stmt->execute();
    }
    
}