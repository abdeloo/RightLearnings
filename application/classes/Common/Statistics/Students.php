<?php

defined('SYSPATH') or die('No direct script access.');

class Common_Statistics_Students {


    public static function CountTeacherStudents($teacher,$term,$section){
        $results = array();
        $student_ids = array(0);
        $count_students = 0;
        $Teacher_Sections = ORM::factory('Study_Sections')->where('status','=',1)->where('is_deleted', '=',NULL);
        if($teacher != NULL){
            $Teacher_Sections = $Teacher_Sections->where('teacher','=',$teacher);
        }
        if($section != NULL){
            $Teacher_Sections = $Teacher_Sections->where('section','=',$section);
        }
        if($term != NULL){
            $Teacher_Sections = $Teacher_Sections->where('term','=',$term);
        }
        $Teacher_Sections = $Teacher_Sections->find_all();

        foreach($Teacher_Sections as $Teacher_Section){
            $students = $Teacher_Section->Students_Sections->where('status','=',1)->where('state','=',3)->where('is_deleted', '=',NULL)->find_all();
            foreach($students as $student){
                if(!in_array($student->student, $student_ids)){
                    $count_students = $count_students + 1;
                    array_push($student_ids, $student->student);
                }
            }

            $results[$Teacher_Section->id] = array("students"=> $student_ids,"count"=> $count_students);
        }
        return $results;
    }

    public static function HomeWorkStatistics($teacher,$from_date, $to_date) {
        $results = array();
        $home_works_count = 0;
        $should_uploaded_count = 0;
        $uploaded_count = 0;
        $HomeWorks = ORM::factory('Study_Lessons_Documents')->where('teacher','=',$teacher)->where('document_type','=',"t")->where('is_deleted','=',NULL);
        if($from_date != NULL){
            $HomeWorks = $HomeWorks->where('download_limit','>=',$from_date);
        }
        if($to_date != NULL){
            $HomeWorks = $HomeWorks->where('download_limit','<=',$to_date);
        }
        $HomeWorks = $HomeWorks->find_all();
        
        foreach($HomeWorks as $HomeWork){
            $home_works_count += 1;
            //get students who should upload this
            //get students who uploaded this
            $Students_Sections = ORM::factory('Students_Sections')->where('section','=',$HomeWork->section)->where('state','=',3)->where('is_deleted','=',NULL)->find_all();
            $should_uploaded_count += count($Students_Sections);
            foreach($Students_Sections as $Students_Section){
                $has_uploaded_home_work = ORM::factory('Students_Lessons_Documents')->where('document','=',$HomeWork->id)->where('student','=',$Students_Section->student)->where('file_path','!=',NULL)->where('is_deleted','=',NULL)->find();
                if($has_uploaded_home_work->loaded()){
                    $uploaded_count += 1;
                }
            }
        }
        //percentage
        $results["total_home_works"] = $home_works_count;
        $results["total_should_uploaded"] = $should_uploaded_count;
        $results["total_uploaded"] = $uploaded_count;
        $results["percentage"] = ($should_uploaded_count > 0)? (($uploaded_count / $should_uploaded_count)*100) : 0;
        return $results;    
    }

    public static function VirtualClassRoomStatistics($teacher,$term,$from_date, $to_date) {
        $results = array();
        $total_students = 0;
        $total_attendance = 0;

        //get students
        $teacher_students = Common_Statistics_Students::CountTeacherStudents($teacher,$term,NULL);

        //get teacher's main online classes
        $vclasses = ORM::factory('VClassrooms_Classes')->where('class_id','!=',NULL)->where('Section.is_deleted','=',NULL)->where('vclassrooms_classes.is_deleted','=',NULL)->with('Section');
        if($teacher != NULL){
            $vclasses = $vclasses->where('Section.teacher','=',$teacher);
        }
        if($term != NULL){
            $vclasses = $vclasses->where('Section.term','=',$term);
        }
        if($from_date != NULL){
            $vclasses = $vclasses->where('start_time', '>=', $from_date);
        }
        if($to_date != NULL){
            $vclasses = $vclasses->where('start_time', '<=', $to_date);
        }
        $vclasses = $vclasses->find_all();


        //get teacher shared classess
        $Shared_Vclasses = ORM::factory('VClassrooms_Followers')->where('Class.class_id', '!=', NULL)->with('Follower')->with('Class');
        if($teacher != NULL){
            $Shared_Vclasses = $Shared_Vclasses->where('Follower.teacher', '=', $teacher);
        }
        if($term != NULL){
            $vclasses = $Shared_Vclasses->where('Follower.term', '=', $term);
        }
        if($from_date != NULL){
            $Shared_Vclasses = $Shared_Vclasses->where('Class.start_time', '>=', $from_date);
        }
        if($to_date != NULL){
            $Shared_Vclasses = $Shared_Vclasses->where('Class.start_time', '<=', $to_date);
        }
        $Shared_Vclasses = $Shared_Vclasses->find_all();

        //main classes statistics
        foreach($vclasses as $vclass){
            $total_students += (isset($teacher_students[$vclass->section]))? $teacher_students[$vclass->section]["count"] : 0;
            if(isset($teacher_students[$vclass->section])){
                $student_ids = $teacher_students[$vclass->section]["students"];
                $total_attendance += $vclass->StudentsUrls->where('student', 'IN',$student_ids)->count_all();
            }else{
                $total_attendance += 0;
            }
        }

        //shared classes statistics
        foreach($Shared_Vclasses as $vclass){
            $total_students += (isset($teacher_students[$vclass->follower_id]))? $teacher_students[$vclass->follower_id]["count"] : 0;
            if(isset($teacher_students[$vclass->follower_id])){
                $student_ids = $teacher_students[$vclass->follower_id]["students"];
                $total_attendance += $vclass->Class->StudentsUrls->where('student', 'IN',$student_ids)->count_all();
            }else{
                $total_attendance += 0;
            }
        }

        $results["total_students"] = $total_students;
        $results["total_attendance"] = $total_attendance;
        $results["percentage"] = ($total_students > 0)? (($total_attendance / $total_students)*100) : 0;

        return $results;
    }
   
    public static function RegisterationStatistics($teacher,$from_date, $to_date,$lang){
        $results = array();
        $results["total_students"] = array();
        $results["sections_names"] = array();
        $total_students = array();
        $sections = array();
        $Students_Sections = ORM::factory('Students_Sections')->where('Section.teacher','=',$teacher)->where('state','=',3)->where('Section.status','=',1)->where('Section.is_deleted','=',NULL)->where('students_sections.is_deleted','=',NULL)->with('Section');
        if($from_date != NULL){
            $Students_Sections = $Students_Sections->where('students_sections.Created_date','>=',$from_date);
        }
        if($to_date != NULL){
            $Students_Sections = $Students_Sections->where('students_sections.Created_date','<',$to_date);
        }
        $Students_Sections = $Students_Sections->find_all();
        foreach($Students_Sections as $Students_Section){
            if(!isset($students_sections[$Students_Section->section])){
                $section_name = Common_General::GetSectionName($Students_Section->Section,$lang);
                $total_students[$Students_Section->section] = 1;
                $sections[$Students_Section->section] = $section_name;
            }else{
                $total_students[$Students_Section->section] += 1;
            }
        }

        foreach($total_students as $key=>$value){
            array_push($results["total_students"], $value);
            array_push($results["sections_names"],$sections[$key]);
        }
        return $results;
    }






}



