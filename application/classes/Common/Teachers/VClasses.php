<?php

defined('SYSPATH') or die('No direct script access.');

class Common_Teachers_VClasses {


    public static function Active_Apps($Teacher) {
        $Meeting_Apps = ORM::factory('Teachers_Meeting_Apps')->where('user_id','=',$Teacher->id)->where('status','=',TRUE)->where('is_deleted','=',NULL)->find_all();
        $results = array();
        $default_app = NULL;
        $active_apps = array();
        foreach($Meeting_Apps as $Meeting_App){
            if($Meeting_App->app_type == 2){
                if($Meeting_App->is_default == 1){
                    $default_app = 2;
                }
                array_push($active_apps, array("app_type"=> 2, "name"=> "Zoom Meet"));
            }else if($Meeting_App->app_type == 4){
                if($Meeting_App->is_default == 1){
                    $default_app = 4;
                }
                array_push($active_apps, array("app_type"=> 4, "name"=> "Google Meet"));
            }
        }    
        $results["active_apps"] = $active_apps;  
        $results["default_app"] = $default_app;  
        return $results;    
    }
   

}
