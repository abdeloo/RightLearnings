<?php

defined('SYSPATH') or die('No direct script access.');

class Common_Notifications {


    public static function GetUserNotifications($User) {
        $results = array();
        $User_Notifications = ORM::factory('Users_Notifications')->where('user','=',$User->id)
                                                            ->and_where_open()
                                                            ->or_where('valid_to','=',NULL)
                                                            ->or_where('valid_to','>=',date("Y-m-d H:i:s"))
                                                            ->and_where_close()
                                                            ->where('is_deleted','=',NULL)
                                                            ->find_all();
        $results = array();
        foreach($User_Notifications as $Notification){
            array_push($results, array("id"=> $Notification->id,"date"=>$Notification->valid_to, "title"=> $Notification->title, "url"=>$Notification->url,"icon"=> $Notification->icon, "color"=>$Notification->color_class));
        }    
        return $results;    
    }
   

}
