<?php

defined('SYSPATH') or die('No direct script access.');

class Common_Statistics_Sections {


    public static function GetProgress($teacher,$lang,$section=NULL,$term=NULL){
        $results = array();
        $sections_progress = array();
        $results["completed_sections"] = 0;
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
        $Teacher_Sections = $Teacher_Sections->order_by('id','DESC')->find_all();

        foreach($Teacher_Sections as $Teacher_Section){
            $section_name = Common_General::GetSectionName($Teacher_Section,$lang);
            $section_start_date = $Teacher_Section->start_date;
            $total_appointments_per_weak = $Teacher_Section->Study_Sections_Dates->count_all();
            $section_sessions = DB::select(array(DB::expr('SUM(`sessions_count`)'), 'sessions_count'))
                            ->from('study_sections_chapters')
                            ->where('status','=',1)
                            ->where('section_id','=',$Teacher_Section->id)
                            ->where('is_deleted','=',NULL)
                            ->execute();
            $total_sessions = ($section_sessions->get('sessions_count') != NULL)? $section_sessions->get('sessions_count') : 0;
            
            $completed_chapters = DB::select(array(DB::expr('SUM(`sessions_count`)'), 'sessions_count'))
                            ->from('study_sections_chapters')
                            ->where('status','=',1)
                            ->where('section_id','=',$Teacher_Section->id)
                            ->where('is_completed','=',1)
                            ->where('is_deleted','=',NULL)
                            ->execute();
            $total_completed_chapters = ($completed_chapters->get('sessions_count') != NULL)? $completed_chapters->get('sessions_count') : 0;

            $percentage = ($total_sessions > 0)? (($total_completed_chapters/$total_sessions)*100) : 0;
            if($percentage == 100){
                $results["completed_sections"] += 1;
            }
            array_push($sections_progress, array("id"=>$Teacher_Section->id,"section_name"=>$section_name,"total_sessions"=> $total_sessions,"completed_sessions"=> $total_completed_chapters, "percentage"=>$percentage));
        }

        $results["sections_progress"] = $sections_progress;
        return $results;
    }
} ?>