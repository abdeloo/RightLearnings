<?php

defined('SYSPATH') or die('No direct script access.');
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\IsMeetingRunningParameters;



class Controller_NewHome_Students_Cart extends Controller_Template_Theme
{
    public function __construct(Request $request, Response $response) {
        // You must call parent::__construct at some point in your function
        parent::__construct($request, $response);
        // Do whatever else you want

        $this->Current_Term = ORM::factory('Study_Terms',ORm::factory('Variables',78)->value);
        if ($this->user_online && $this->user_online->user_groub == 3 && $this->user_online->Student_Information->S_Type == 1) {
            $this->studentId = $this->user_online->id;
        }else{
            $this->studentId = NULL;
        }
    }


    public function action_index() {

        
    }

    public function action_AddToCart() {
        $results = array();

        $req = Request::current(); //fillter requset
        $section_id = $this->request->param('par1');

        if (!empty($this->user_online->id)) {


            $Current_Term = ORM::factory('Study_Terms', ORM::factory('Variables', 78)->value);
            $StateOfTerm = $Current_Term->StateOfTerm();
            //فحص اذا الطالب مسجل للفصل
            $STerms = ORM::factory('Students_Terms')
                ->where('student', '=', $this->user_online->id)
                ->where('term', '=', $Current_Term->id)
                ->where('is_deleted', '=', NULL)
                ->find();


            $Obj = ORM::factory('Study_Sections', $section_id);
            if ($Obj->loaded()) {
                $SectionStudents = ORM::factory('Students_Sections')->where('section','=',$Obj->id)->where('is_deleted','=',NULL)->and_where_open()->or_where('state','!=',4)->or_where('state','=',NULL)->and_where_close()->count_all();

                $Student_Info = $this->user_online->Student_Information ;
                $CenterSettings = ORM::factory('Students_Applications_Forms')->where('for_college','=', $Student_Info->college)->find();
                if(!$CenterSettings->loaded()){
                    $test_condition = ((empty($Student_Info->plan) || ($Obj->plan == $Student_Info->plan)) && (empty($Student_Info->program) || ($Obj->program == $Student_Info->program)) && (empty($Student_Info->major) || ($Obj->major == $Student_Info->major)) && ($Obj->college == $Student_Info->college))? true : false;
                }else{
                    $test_condition = ((empty($Student_Info->plan) || ($Obj->plan == $Student_Info->plan) || ($CenterSettings->plan != 1)) && (empty($Student_Info->program) || ($Obj->program == $Student_Info->program) || ($CenterSettings->program != 1)) && (empty($Student_Info->major) || ($Obj->major == $Student_Info->major) || ($CenterSettings->major != 1)) && ($Obj->college == $Student_Info->college || ($CenterSettings->college != 1)))? true : false;
                }
                if ($test_condition == true){                        
                    if($Obj->for_student == NULL){
                        if(($Obj->max_students == 0) || ($Obj->max_students > 0 && $SectionStudents < $Obj->max_students)){
                            $ExRel = ORM::factory('Students_Sections')
                                ->where('student', '=', $this->user_online->id)
                                ->where('section', '=', $Obj->id)
                                ->where('is_deleted', '=', NULL)
                                ->find();
                            if (!$ExRel->loaded()) {
                                $study_course = ORM::factory('Study_Courses',$Obj->course);
                                $prerequisite_courses = ORM::factory('Study_Courses_Prerequisite')->where('course','=',$Obj->course)->find_all();
                                $allowed_register = True;
                                $prerequisite_status = $study_course->prerequisite_status;
                                if (count($prerequisite_courses) > 0){
                                    foreach ($prerequisite_courses as $pre_course) {
                                        $pre_study_sections = ORM::factory('Study_Sections')->where('course', '=', $pre_course->prerequisite_course)->find_all();
                                        $pre_study_sections_ids = array(0);
                                        foreach ($pre_study_sections as $pre_study_section) {
                                            array_push($pre_study_sections_ids, $pre_study_section->id);
                                        }
                                        if ($prerequisite_status == 1){
                                            $prevent_register_msg = " متطلبات سابقة يجب دراستها والنجاح فيه أولا  : ";
                                            $student_section = ORM::factory('Students_Sections')->where('student', '=', $this->user_online->id)->where('section', 'IN', $pre_study_sections_ids)->where('state', '=', 3)->where('score', '=', 1)->where('is_deleted', '=', NULL)->find();
                                            if (!$student_section->loaded()){
                                                $allowed_register = False;
                                                $prevent_register_msg.= $pre_course->Prerequisite_Course->name_ar ;
                                            }
                                        }
                                        else{//متطلبات سابقة يجب دراستها اولا قبل دراسة هذه المادة ولا يشترط النجاح فيها
                                            $student_section = ORM::factory('Students_Sections')->where('student', '=', $this->user_online->id)->where('section', 'IN', $pre_study_sections_ids)->where('state', '=', 3)->where('term', '!=', $Current_Term->id)->where('is_deleted', '=', NULL)->find();
                                            if (!$student_section->loaded()){
                                                $prevent_register_msg = " متطلبات سابقة يجب دراستها أولا  : ";
                                                $allowed_register = False;
                                                $prevent_register_msg.= $pre_course->Prerequisite_Course->name_ar;
                                            }
                                        }
                                    }
                                }
                                if($allowed_register == True){                  
                                    try {
                                        $New_Rel = ORM::factory('Students_Sections');
                                        $New_Rel->student = $this->user_online->id;
                                        $New_Rel->section = $Obj->id;
                                        $New_Rel->term = $Current_Term->id;
                                        if($Obj->is_free){
                                            $New_Rel->state = 3;
                                            $text = Lang::__('your_request_saved_successfully');
                                        }else{
                                            $New_Rel->state = NULL;
                                            $checkoutLink = '<a href="' . URL::base() . 'Center/CartDetails' . '">' . Lang::__('payment_operation') . '</a>';
                                            $text = Lang::__('now_go_to')  . " ". $checkoutLink . " ". Lang::__('to_complete_section_registeration');
                                        }
                                        //$New_Rel->state = 1;
                                        $New_Rel->Created_by = $this->user_online->id;
                                        $New_Rel->Created_date = date("Y-m-d H:i:s");
                                        
                                        if ($New_Rel->save()) {
                                            
                                            //in case of package
                                            if($Obj->is_package == 1){
                                                $packageSections = ORM::factory('Study_Sections')->where('parent','=',$Obj->id)->where('is_deleted','=',NULL)->find_all();
                                                foreach($packageSections as $Section){
                                                    $packageSection = ORM::factory('Students_Sections');
                                                    $packageSection->student = $this->user_online->id;
                                                    $packageSection->section = $Section->id;
                                                    $packageSection->term = $Obj->term;
                                                    if($Obj->is_free){
                                                        $packageSection->state = 1;
                                                    }else{
                                                        $packageSection->state = NULL;
                                                    }
                                                    $packageSection->Created_by = $this->user_online->id;
                                                    $packageSection->Created_date = date("Y-m-d H:i:s");
                                                    $packageSection->save();
                                                }
                                            }
                                                
                                            
                                            $results['Resp'] = array(
                                                'theme' => 'lime', //teal,amethyst,ruby,tangerine,lemon,lime,ebony,smoke
                                                'horizontalEdge' => 'top', //top,bottom
                                                'verticalEdge' => 'right', //right,left
                                                'heading' => Lang::__('successfully'),
                                                'life' => '2000', //1000 is 1 second
                                                'text' =>  $text,
                                            );
                                        }
                                    } catch (ORM_Validation_Exception $e) {
                                        $errors = $e->errors('');

                                        $results['Errors'] = array(
                                            'title' => Lang::__('Error'),
                                            'content' => General::CatchErrorMSGSAjax($errors),
                                        );
                                    }
                                }else{
                                    $results['Errors'] = array(
                                        'title' => Lang::__('Error'),
                                        'content' => $prevent_register_msg,
                                    );
                                }
                            } else {

                                $results['Errors'] = array(
                                    'title' => Lang::__('Error'),
                                    'content' => Lang::__('Section already added to your schedule'),
                                );
                            }
                        } else {

                            $results['Errors'] = array(
                                'title' => Lang::__('Error'),
                                'content' => Lang::__('reached_max_student_number'),
                            );
                        }
                    }else{
                        $results['Errors'] = array(
                            'title' => Lang::__('Error'),
                            'content' => Lang::__('this_is_private_section'),
                        ); 
                    }
                }else{
                    $results['Errors'] = array(
                        'title' => Lang::__('Error'),
                        'content' => Lang::__('Section_not_for_your_grade'),
                    );                        
                }
            } else {
                $results['Errors'] = array(
                    'title' => Lang::__('Error'),
                    'content' => Lang::__('Not_found_the_desired_item'),
                );
            }
        //}
    } else {
        $results['Errors'] = array(
            'title' => Lang::__('Error'),
            'content' => Lang::__('Please sign in to continue'),
        );
    }

    $this->redirect('NewHome/Cart');

    //echo json_encode($results);
        
    }

   

}