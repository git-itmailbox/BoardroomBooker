<?php

class CommonHelper
{


    public static function fetchAppointments($appointments)
    {

        $resultArray = [];
        foreach ($appointments as $appointment) {
            //$appointment->meetDate
            $date = new DateTime($appointment->meetDate);
            $dayOfMonth = intval($date->format('d'));

            $resultArray[$dayOfMonth][] = $appointment;
        }
        return $resultArray;
    }

    public static function printCalendar($appointments = [], $month = 0, $year = 0, $mode = false)
    {

        $fetchedAppointmetsByDays = self::fetchAppointments($appointments);

        $calendar = '<table class="calendar">';

        $calendarHeader1 = ['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN'];
        $calendarHeader2 = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];
        $calendarHeader=($mode)?$calendarHeader2:$calendarHeader1;
        // Печатаем шапку календаря.
        $calendar .= '<tr class="">';
        for ($weekDayNum = 0; $weekDayNum <= 6; $weekDayNum++) {
            $calendar .= '<th>';
            $calendar .= '<div>' . $calendarHeader[$weekDayNum] . '</div>';
            $calendar .= '</th>';
        }
        $calendar .= '</tr>';


        $weekDayNum = date('w', mktime(0, 0, 0, $month, 1, $year));
        if (!$mode) {
            $weekDayNum -= 1;
            $weekDayNum = ($weekDayNum == -1) ? 6 : $weekDayNum; //теперь 0 - mon, 6-sunday
        }

        $daysInMonth = date('t', mktime(0, 0, 0, $month, 1, $year));

        $dayCounter = 0;
        $days = 1;
        $calendar .= '<tr>';
        //print empty cells
        for ($i = 0; $i < $weekDayNum; $i++) {
            $calendar .= '<td></td>';
            $days++;
        }

        for ($list_day = 1; $list_day <= $daysInMonth; $list_day++) {

            //get all meetings at current day
            $appList = "";
            if (array_key_exists($list_day, $fetchedAppointmetsByDays)) {
                $appList = '<div class="appList">';
                foreach ($fetchedAppointmetsByDays[$list_day] as $key => $appointmets) {
                    $appList .= '<a class="meeting" href="#viewEvent" data-id="' . $appointmets->id . '" id="app_' . $appointmets->id . '">' . $appointmets->beginTime . '-' . $appointmets->endTime;
                }
                $appList .= '</div>';
            }
            $calendar .= '<td>';
            $calendar .= '<div class="tableContent">' . $list_day . '</div>' . $appList;
            $calendar .= '</td>';
            if ($weekDayNum == 6) {

                $calendar .= '</tr>';

                if ($daysInMonth != $dayCounter + 1) {
                    $calendar .= '<tr>';
                }
                $weekDayNum = -1;
                $days = 0;
            }
            $days++;
            $weekDayNum++;
            $dayCounter++;

        }
        //check if there empty cells in current week
        if ($days < 8) {
            for ($x = 1; $x <= (8 - $days); $x++) {
                $calendar .= '<td class=""> </td>';
            }
        }
        $calendar .= '</tr>';
        $calendar .= '</table>';

        return $calendar;

    }

    public static function select_years($name, $selectorId = "")
    {
        return '
        <select id="' . $selectorId . '" name="' . $name . '">
        <option value="' . date("Y") . '" SELECTED>' . date("Y") . '</option>
        <option value="' . (date("Y") + 1) . '">' . (date("Y") + 1) . '</option>
        <option value="' . (date("Y") + 2) . '">' . (date("Y") + 2) . '</option>
        <option value="' . (date("Y") + 3) . '">' . (date("Y") + 3) . '</option>
        </select>';
    }

   public function timeSelect($name, $mode, $id = "", $selected = -1, $h12format=false)
    {

        if ($mode) {
            $mode =($h12format)?13: 24;
        } else {
            $mode = 60;
        }

        $data = '<select id="' . $id . '" name="' . $name . '" >';
        $data .= '<option value="-1">--</option>';

        for ($i = 0; $i < $mode; $i++) {
            // when 12h format we do not need 0h am|pm, only 1-12
            if($h12format && $i==0) continue;
            if ($i <= 9) {
                $i = "0" . $i;
            }

            if ($i == $selected) {
                $data .= '<option selected="selected" value="' . $i . '" >' . $i . '</option>';
            } else {
                $data .= '<option value="' . $i . '" >' . $i . '</option>';
            }
        }
        $data .= '</select>';
        return $data;

    }
}

