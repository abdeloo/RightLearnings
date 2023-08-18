<?php

defined('SYSPATH') or die('No direct script access.');

class Common_General {


    public static function GetSectionName($Section,$lang) {
        //name
        $description = $Section->Descriptions->where('is_deleted','=',NULL)->find();
        if($description->loaded() && $description->{'name_'.$lang} != NULL){
            $name = $description->{'name_'.$lang};
        }else{
            $name = ($Section->Course->{'name_'.$lang} != NULL)? $Section->Course->{'name_'.$lang} : "new course";
        }
        return $name;    
    }

    public static function GetSectionDetails($Section,$lang) {
        $results = array();

        //logo
        $section_logo = ($Section->logo_path != NULL)? $Section->logo_path : $Section->Course->img;
        if($section_logo == NULL ){   
            $section_logo = ($Section->College->file_path != NULL)?  $Section->College->file_path : ORM::factory('Variables', 1)->value;
        }

        //name
        $description = $Section->Descriptions->where('is_deleted','=',NULL)->find();
        if($description->loaded() && $description->{'name_'.$lang} != NULL){
            $name = $description->{'name_'.$lang};
            $desc = $description->{'desc_'.$lang};
        }else{
            //$name = ($Section->Course->{'name_'.$lang} != NULL)? $Section->Course->{'name_'.$lang} : "new course";
            $name = ($Section->{'name_'.$lang} != NULL)? $Section->{'name_'.$lang} : $Section->Course->{'name_'.$lang}.' '.$Section->Plan->{'name_'.$lang}.' '.$Section->Program->{'name_'.$lang}.' '.$Section->Major->{'name_'.$lang};
            $desc = ($Section->{'desc_'.$lang} != NULL)? $Section->{'desc_'.$lang} : $Section->College->{'name_'.$lang};
        }

        //students
        $Students = $Section->Students_Sections->where('state','=',3)->where('is_deleted','=',NULL)->count_all();

        $results["id"] = $Section->id;
        $results["logo"] = $section_logo;
        $results["section_name"] = $name;
        $results["description"] = $description;
        $results["students_count"] = $Students;
        return $results;    
    }
   

}
