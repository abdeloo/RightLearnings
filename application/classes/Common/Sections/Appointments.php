<?php

defined('SYSPATH') or die('No direct script access.');

class Common_Sections_Appointments {


    public static function Next_Section_Appointment($Section) {
        $is_today = FALSE;
        $target_days = array();
        $meeting_date = date("Y-m-d H:i:s");
        $current_day = date('l', strtotime($meeting_date));
        $Appointments = $Section->Study_Sections_Dates->find_all();
        foreach($Appointments as $Appointment){
            $appointment_date = date('l', strtotime($meeting_date));
            if($Appointment->day == $current_day && time() <= strtotime($Appointment->start)){
                $date = date("Y-m-d");
                $time = $Appointment->start;
                $meeting_date = date('Y-m-d H:i:s', strtotime("$date $time"));
                $is_today = TRUE;
                break;
            }else{
                $target_days[$Appointment->day] = $Appointment->start;
            }
        }

        if($is_today == FALSE && !empty($target_days)){
            // Create a DateTime object for today
            $today = $meeting_date;  //new DateTime();
            // Initialize an empty array to hold the dates for target days
            $dates = array();
            // Loop through the target days and calculate the next occurrence of each
            foreach ($target_days as $day => $value) {
                $date = new DateTime('next ' . $day);
                $dates[] = $date;
            }
            // Sort the dates array in ascending order
            sort($dates);
            // Initialize a variable to hold the closest date
            $closest_date = null;
            // Loop through the sorted dates array and find the closest date
            foreach ($dates as $date) {
                if ($date > $today) {
                    $closest_date = $date;
                    break;
                }
            }
            //get day from closest date
            if ($closest_date !== null) {
                $date = $closest_date->format('Y-m-d');
                $time = $target_days[$closest_date->format('l')];
                $meeting_date = date('Y-m-d H:i:s', strtotime("$date $time"));
            }
        }  
        return $meeting_date;    
    }
   

}
