<?php

defined('SYSPATH') or die('No direct script access.');
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\IsMeetingRunningParameters;



class Controller_NewHome_Students_Profile extends Controller_Template_Theme
{
    public function __construct(Request $request, Response $response) {
        // You must call parent::__construct at some point in your function
        parent::__construct($request, $response);
        // Do whatever else you want
        if (empty($this->user_online)) {
            $this->redirect('/NewHome', 302);
        } else {
            if ($this->user_online->user_groub != 3) {
                $this->redirect('/NewHome', 302);
            }else if ($this->user_online->Student_Information->S_Type != 1){
                $this->redirect('/NewHome', 302);
            }
        }

        $this->Current_Term = ORM::factory('Study_Terms',ORm::factory('Variables',78)->value);
        if ($this->user_online && $this->user_online->user_groub == 3 && $this->user_online->Student_Information->S_Type == 1) {
            $this->studentId = $this->user_online->id;
        }else{
            $this->studentId = NULL;
        }
    }


    public function action_index() {

        $Student_Information = $this->user_online->Student_Information;
        $Student_Sections = ORM::factory('Students_Sections')->where('students_sections.term', '=', $this->Current_Term->id)->where('state', '=', 3)->where('students_sections.status', '=', 1)->where('student', '=', $this->user_online->id)->where('students_sections.is_deleted', '=', NULL)->with('Section')->where('Section.parent','=',NULL);
        $Student_Sections->reset(FALSE);

        $student_sections_ids = array(0);
        foreach($Student_Sections as $Student_Section){
            array_push($student_sections_ids, $Student_Section->section_id);
        }

        if ($Student_Information->display_same_grade_courses == TRUE || $Student_Information->display_same_stage_courses == TRUE){
            $Suggested_Sections = ORM::factory('Study_Sections')->where('id','NOT IN', $student_sections_ids)->where('start_date','>',date("Y-m-d H:i:s"))->where('status','=',1)->where('is_deleted','=',NULL);
            if ($Student_Information->display_same_grade_courses == TRUE){
                $Suggested_Sections = $Suggested_Sections->where('plan','=', $Student_Information->plan);
            }
            if ($Student_Information->display_same_stage_courses == TRUE){
                $Suggested_Sections = $Suggested_Sections->where('program','=', $Student_Information->program);
            }
            $Suggested_Sections = $Suggested_Sections->find_all();
        }else{
            $Suggested_Sections = array();
        }
        
        //student quizz
        $sections = array(0);
        //$exam_sections = array();
        $student_exams = array();
        $student_sections = ORM::factory('Students_Sections')->where('term','=',$this->Current_Term )->where('student','=',$this->user_online->id)->where('is_deleted','=',NULL)->where('state','=',3)->where('exam_status','=',1)->find_all();
        foreach($student_sections as $student_section){
            array_push($sections,$student_section->section);
        }
        $exams = ORM::factory('Online_Exams')->where('term','=',$this->Current_Term )->where('approved',"=", 1)->where('start_at',"!=", NULL)->where('is_deleted','=',NULL)->where('is_cancelled','=',NULL)->order_by('start_at','DESC')->find_all();
        foreach($exams as $exam){
            if($exam->is_general == 1){
                if(in_array($exam->section,$sections)){
                    array_push($student_exams, array('exam' => $exam, 'course' => $exam->Course->{'name_'.$this->lang}, 'teacher' => $exam->Section->Teacher->{'name_'.$this->lang}));
                }
                $shares = $exam->Shares->where('section','IN',$sections)->where('is_deleted','=',NULL)->find_all();
                foreach($shares as $share){
                    array_push($student_exams, array('exam' => $share->CourseExam, 'course' => $share->Section->Course->{'name_'.$this->lang}, 'teacher' => $share->Section->Teacher->{'name_'.$this->lang}));
                }
            }else{
                $student_exam = $exam->Students->where('student','=',$this->user_online->id)->where('is_deleted','=',NULL)->find();
                if($student_exam->loaded()){
                    if(in_array($exam->section,$sections)){
                        array_push($student_exams, array('exam' => $exam, 'course' => $exam->Course->{'name_'.$this->lang}, 'teacher' => $exam->Section->Teacher->{'name_'.$this->lang}));
                    }else{
                        $shares = $exam->Shares->where('section','IN',$sections)->where('is_deleted','=',NULL)->find_all();
                        foreach($shares as $share){
                            array_push($student_exams, array('exam' => $share->CourseExam, 'course' => $share->Section->Course->{'name_'.$this->lang}, 'teacher' => $share->Section->Teacher->{'name_'.$this->lang}));                   
                        }
                    }
                }
            }
        }

        $this->template->layout = new View('new_theme/students/profile');
        $this->template->layout->lang = $this->lang;
        $this->template->layout->user_online = $this->user_online;

        $this->template->layout->Suggested_Sections = $Suggested_Sections;
        $Student_Sections->reset(FALSE);
        
        $this->template->layout->active_sections = ORM::factory('Students_Sections')->where('students_sections.term', '=', $this->Current_Term->id)->where('state', '=', 3)->where('students_sections.status', '=', 1)->where('student', '=', $this->user_online->id)->where('students_sections.is_deleted', '=', NULL)->with('Section')->where('Section.parent','=',NULL)->where('Section.start_date','<', date("Y-m-d H:i:s"))->where('Section.end_date','>', date("Y-m-d H:i:s"))->find_all();
        $Student_Sections->reset(FALSE);       

        $this->template->layout->online_sections = $Student_Sections->where('is_online','=',1)->count_all();
        $Student_Sections->reset(FALSE);       

        $this->template->layout->completed_sections =  $Student_Sections->where('Section.end_date','<', date("Y-m-d H:i:s"))->find_all();
        
        $this->template->layout->wishlists = ORM::factory('Students_Sections_Wishlists')->where('student','=',$this->user_online->id)->find_all();   
        
        $this->template->layout->Student_App = $Student_Information;

        $this->template->layout->student_exams = $student_exams;
    }

    public function action_Todb() {
        $results = array();

        $req = Request::current(); //fillter requset
        $Filtered_array = Search::action_Filtered_array($req->post());

        $objj = ORM::factory('User', $this->user_online->id);

        if ($objj->loaded() && $objj->user_groub == 3) {
            
            $Student_info = $objj->Student_Information;

            if(isset($Filtered_array['suggested_courses'])){
                $Student_info->display_same_grade_courses = isset($Filtered_array['display_same_grade_courses'])? TRUE : FALSE;
                $Student_info->display_same_stage_courses = isset($Filtered_array['display_same_stage_courses'])? TRUE : FALSE;
            }
            //Keep only this step fields
            $StepAFields = array('name_ar','name_en', 'mobile', 'email','current_password','new_password');
            foreach ($Filtered_array as $key => $value) {
                if (!in_array($key, $StepAFields)) {
                    unset($Filtered_array[$key]);
                }
            }
            //End Keep only this step fields
            try {                   
                if (!empty($Filtered_array['new_password'])){
                    if(!empty($Filtered_array['current_password'])){
                        if (is_string($Filtered_array['current_password']) && is_string($Filtered_array['new_password'])) {
                            // Check Current Password
                            $check_password = Auth::instance()->check_password($Filtered_array['current_password']);
                            if ($check_password){
                                $objj->password = $Filtered_array['new_password'];
                                $Student_info->password = $Filtered_array['new_password'];
                                if ($objj->update()) {
                                    if($Student_info->save()){
                                        $results['Success'] = array(
                                            'title' => Lang::__('Done'),
                                            'content' => Lang::__('Saved_successfully'),
                                        );
                                    }
                                }
                            }else{
                                $results['Errors'] = array(
                                    'title' => Lang::__('Error'),
                                    'content' => Lang::__('current_password_is_invalid')
                                );
                            }
                        }else{
                            $results['Errors'] = array(
                                'title' => Lang::__('Error'),
                                'content' => Lang::__('current_password_is_invalid')
                            );
                        }
                    }else{
                        $results['Errors'] = array(
                            'title' => Lang::__('Error'),
                            'content' => Lang::__('current_password_is_required')
                        );
                    }  
                }else{
                    $objj->values($Filtered_array);
                    $objj->last_update_by = $this->user_online->id;
                    $objj->last_update_date = date("Y-m-d H:i:s");
                    if ($objj->update()) {
                        if (!empty($Filtered_array['name_ar'])){
                            $Student_info->Full_Name_Arabic = $Filtered_array['name_ar'];
                        }
                        if (!empty($Filtered_array['name_en'])){
                            $Student_info->Full_Name_English = $Filtered_array['name_en'];
                        }
                        if (!empty($Filtered_array['email'])){
                            $Student_info->Email = $Filtered_array['email'];
                        }
                        if (!empty($Filtered_array['mobile'])){
                            $Student_info->Mobile = $Filtered_array['mobile'];
                        }
                        if($Student_info->save()){
                            $results['Success'] = array(
                                'title' => Lang::__('Done'),
                                'content' => Lang::__('Saved_successfully'),
                            );
                        }
                    } 
                }
            } catch (ORM_Validation_Exception $e) {

                $errors = $e->errors('');
                $results['Errors'] = array(
                    'title' => Lang::__('Error'),
                    'content' => General::ArrayToString(General::CatchErrorMSGSAjax($errors))
                );
            }
                           
        } else {
            $results['Errors'] = array(
                'title' => Lang::__('Error'),
                'content' => Lang::__('You_dont_have_permission_to_do_this_action')
            );
        }

        echo json_encode($results);
    }

    
   

}