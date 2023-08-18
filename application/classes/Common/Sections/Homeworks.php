<?php

defined('SYSPATH') or die('No direct script access.');

class Common_Sections_Homeworks {


    public static function GetAll($teacher,$from_date,$to_date,$lang) {
        $results = array();
        $results["all_homework"] = array();
        $results["previous_homework"] = array();
        $results["current_homework"] = array();
        $results["next_homework"] = array();
        $HomeWorks = ORM::factory('Study_Lessons_Documents')->where('document_type','=',"t")->where('is_deleted','=',NULL)->order_by('download_limit','DESC');
        if($teacher != NULL){
            $HomeWorks = $HomeWorks->where('teacher','=',$teacher);
        }
        if($from_date != NULL){
            $HomeWorks = $HomeWorks->where('download_limit','>=',$from_date);
        }
        if($to_date != NULL){
            $HomeWorks = $HomeWorks->where('upload_limit','<=',$to_date);
        }
        $HomeWorks = $HomeWorks->find_all();
        
        foreach($HomeWorks as $HomeWork){
            $Section = $HomeWork->Section;
            $section_name = Common_General::GetSectionName($Section,$lang);
            $section_path =  $Section->Plan->{'name_'.$lang} . "-" . $Section->Program->{'name_'.$lang} . "-" . $Section->Major->{'name_'.$lang} . "-" . $Section->College->{'name_'.$lang}; 
            $lesson = $HomeWork->Study_Lesson->name;
            if($HomeWork->upload_limit < date("Y-m-d")){
                array_push($results["previous_homework"], array("id"=>$HomeWork->id,"section_name"=>$section_name,"section_path"=>$section_path,"lesson"=>$lesson,"file_name"=>$HomeWork->file_name,"file_path"=>$HomeWork->file_path,"valid_to"=>$HomeWork->upload_limit));
            }else if($HomeWork->download_limit <= date("Y-m-d") && $HomeWork->upload_limit > date("Y-m-d")){
                array_push($results["current_homework"], array("id"=>$HomeWork->id,"section_name"=>$section_name,"section_path"=>$section_path,"lesson"=>$lesson ,"file_name"=>$HomeWork->file_name,"file_path"=>$HomeWork->file_path,"valid_to"=>$HomeWork->upload_limit));
            }else{
                array_push($results["next_homework"], array("id"=>$HomeWork->id,"section_name"=>$section_name,"section_path"=>$section_path,"lesson"=>$lesson,"file_name"=>$HomeWork->file_name,"file_path"=>$HomeWork->file_path,"valid_to"=>$HomeWork->upload_limit));
            }
        }

        return $results;
    }
}
?>