<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_NewHome_Sections_Register extends Controller_Template_Theme
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


    public function action_LoginAddSection() {
        $req = Request::current(); //fillter requset
        $Filtered_array = Search::action_Filtered_array($req->post());
        $section = !empty($Filtered_array['section']) ? mb_strtolower($Filtered_array['section']) : NULL;
        $type = !empty($Filtered_array['type']) ? mb_strtolower($Filtered_array['type']) : NULL;
        $title = Lang::__('join_course');
        if($type == "login"){
            $view = View::factory('new_theme/sections/register/signin_modal');
        }else{
            $view = View::factory('new_theme/sections/register/register_modal');
        }        
        $view->set('title', $title);
        $view->set('lang', $this->lang);
        $view->set('section', $section);
        $view->set('logo', $this->logo);   
        $this->response->body($view);
    }


    public function action_AddSecTodb() {
        $results = array();
        $req = Request::current(); //fillter requset
        $Filtered_array = Search::action_Filtered_array($req->post());
        $sectionId = empty($Filtered_array['section_id']) ? NULL : $Filtered_array['section_id'];  //section Id
        $opType = empty($Filtered_array['op_type']) ? NULL : $Filtered_array['op_type'];  //login or register
        $Section = ORM::factory('Study_Sections',$sectionId);
        $SectionStudents = ORM::factory('Students_Sections')->where('section','=',$sectionId)->where('is_deleted','=',NULL)->and_where_open()->or_where('state','!=',4)->or_where('state','=',NULL)->and_where_close()->count_all();

        try {
            if($Section->for_student != NULL){
                $results['Errors'] = Lang::__('this_is_private_section') ;
            }else if (($Section->max_students > 0) && ($SectionStudents >= $Section->max_students)){
                    $results['Errors'] = Lang::__('reached_max_student_number') ;
            }else{
                if($opType == "user"){ //add to current user
                    if(!empty($this->user_online)){
                        $results = $this->AddStudentSection($this->user_online,$Section);
                    }
                    

                }else if($opType == "login"){
                    if (HTTP_Request::POST == $this->request->method()) {
                        $remember = array_key_exists('remember', $this->request->post()) ? (bool) $this->request->post('remember') : FALSE;
                        
                        $loggedin_user = Controller_NewHome_Login::LoginUser($this->request->post('username'), $this->request->post('password'), $remember);
                        // If successful, redirect user
                        if ($loggedin_user["Response"]["status"] == TRUE) {
                            $user_data = $loggedin_user["Response"]["user_details"];
                            $results = $this->AddStudentSection($user_data,$Section);
                            // if($user_data->user_groub == 3){  //if student
                            //     $can_register_section = $this->CheckIfValidToRegisterSection($user_data->id, $sectionId);
                            //     if($can_register_section["Response"]["status"] == FALSE){
                            //         $results['Errors'] = $can_register_section["Response"]["Errors"] ;
                            //         $results['redirect'] = $can_register_section["Response"]["redirect"];
                            //     }else{
                            //         $register_section = $this->AddStudentToSection($user_data,$Section);
                            //         if($register_section["Response"]["status"] == TRUE){
                            //             $results['Success'] = array(
                            //                 'text' => $register_section["Response"]["text"],
                            //                 'redirect' => $register_section["Response"]["redirect"],
                            //             );
                            //         }else{
                            //             $results['Errors'] = $register_section["Response"]["Errors"] ;
                            //         }                                    
                            //     }                          
                            // } else{
                            //     $results['Errors'] = Lang::__('You_dont_have_permission_to_access_this_page');
                            // }    
                        }else{
                            $results['Errors'] = $loggedin_user["Response"]["Errors"];
                        }                
                                      
                    }
                }else if($opType == "register"){
                    $objj = ORM::factory('Students_Applications')
                                    ->and_where_open()
                                    ->or_where('ID_No','=',$Filtered_array['ID_No'])
                                    ->or_where('Email','=',$Filtered_array['Email'])
                                    ->and_where_close()
                                    ->find();

                    if (!$objj->loaded()) {

                        $objj->Created_by = NULL;
                        $objj->Created_date = date("Y-m-d H:i:s");
                        //$objj->need_to_review = 1;
                        $objj->ip_of_application = Detects::get_client_ip();
                        $objj->device = Detects::getDevice();
                        $objj->os = Detects::getOS();
                        $objj->browser = Detects::getBrowser();
                        $objj->S_Type = 1;


                        //Generate App id
                        $app_id = date('y') . Text::random('nozero', 8);
                        while (TRUE) {
                            $check = ORM::factory('Students_Applications')->where('app_id', '=', $app_id)->find();
                            if ($check->loaded()) {
                                $app_id = date('y') . Text::random('nozero', rand(4, 7));
                            } else {
                                break;
                            }
                        }
                        $objj->app_id = $app_id;
                        $objj->app_password = mb_strtolower(Text::random('nozero', rand(4, 7)));
                        //End Generate App id
                        


                        //Keep only this step fields
                        $StepAFields = array('Office_Phone','country','city','college', 'major', 'program', 'plan', 'Full_Name_Arabic', 'Full_Name_English', 'Place_of_birth', 'Date_of_Birth', 'Date_of_Birth_higri', 'Gender', 'Nationality', 'ID_No', 'Mobile', 'Phone', 'Email', 'Address');
                        foreach ($Filtered_array as $key => $value) {
                            if (!in_array($key, $StepAFields)) {
                                unset($Filtered_array[$key]);
                            }
                        }
                        //End Keep only this step fields

                        //set default values for empty columns
                        $rnd = mt_rand(1000, 9999);
                        if(empty($Filtered_array["ID_No"])){
                            $Filtered_array["ID_No"] = Text::random('nozero', rand(8, 8));
                        }
                        if(empty($Filtered_array["Email"])){
                            $Filtered_array["Email"] = $rnd . "@email.com";
                        }
                        if(empty($Filtered_array["Full_Name_Arabic"])){
                            $Filtered_array["Full_Name_Arabic"] = "مشترك";
                        }
                        if(empty($Filtered_array["Full_Name_English"])){
                            $Filtered_array["Full_Name_English"] = "Participant";
                        }

                        $objj->values($Filtered_array);
                        try {
                            $extra_rules = General::Jquery_To_ORM_Rules($Filtered_array, $objj->StepAJqueryRules($objj, FALSE));
                            
                            if ($objj->save($extra_rules)) {

                                //لفحص اذا كان هناك رسوم لقبول التسجيل
                                $objj->amount_to_pay = ORM::factory('Variables', 73)->value; //رسوم التسجيل
                                if (empty(ORM::factory('Variables', 73)->value)) {
                                    $objj->amount_paid = 2;
                                }
                                if($objj->save()){
                                    $this->SetStepAsFinish($objj, "A");
                                    $StepA = ORM::factory('Students_Applications_Steps')
                                        ->where('student_application_id', '=', $objj->id)
                                        ->where('step', '=', 'A')
                                        ->find();

                                    if ($StepA->loaded() && empty($StepA->last_update_date)) {
                                        $objj->finished = 1;

                                        $Student = ORM::factory('User')->where('email','=',$objj->Email)->where('is_deleted','=',NULL)->find();
                                        if (!$Student->loaded()){
                                            $Student->Created_by = NULL;
                                            $Student->Created_date = date("Y-m-d H:i:s");
                                            $password = Text::random('nozero', rand(8, 8));
                                            $username = $objj->GenerateAcadimicNo();
                                            $Student->password = $password;
                                            $Student->username = $username;
                                            $objj->password = $password;
                                            $Student->user_groub = 3;
                                            $Student->email = $Filtered_array['Email'];
                                            $Student->mobile = $Filtered_array['Mobile'];
                                            $Student->name_ar = $Filtered_array['Full_Name_Arabic'];
                                            $Student->name_en = $Filtered_array['Full_Name_English'];
                                            $Student->college = $Filtered_array['college'];
                                            if ($Student->save()) {
                                                $Student->add('roles', ORM::factory('Role', array('name' => 'login')));
                                            }
                                        }
                                        $objj->student_id = $Student->id;
                                        $objj->Created_by = $Student->id;
                                        if ($objj->update()){

                                            $Student_Term = ORM::factory("Students_Terms")->where('is_deleted','=',NULL)->where('student','=',$Student->id)->where('term','=',$Section->term)->find();
                                            if(!$Student_Term->loaded()){
                                                $Student_Term->student = $Student->id;
                                                $Student_Term->term = $Section->term;
                                                $Student_Term->college = $objj->college;
                                                $Student_Term->program = $objj->program;
                                                $Student_Term->major = $objj->major;
                                                $Student_Term->plan = $objj->plan;
                                                $Student_Term->Created_by = $Student->id;
                                                $Student_Term->Created_date = date("Y-m-d H:i:s");
                                                $Student_Term->save();
                                            }

                                            $Student_Section = ORM::factory('Students_Sections');
                                            $Student_Section->student = $Student->id;
                                            $Student_Section->section = $sectionId;
                                            $Student_Section->term = $Section->term;
                                            $Student_Section->Created_by = $Student->id;
                                            $Student_Section->Created_date = date("Y-m-d H:i:s");
                                            if($Section->is_free){
                                                $Student_Section->state = 1;
                                                $text = Lang::__('your_request_saved_successfully');
                                            }else{
                                                $Student_Section->state = NULL;
                                                $checkoutLink = '<a href="' . URL::base() . 'Center/CartDetails' . '">' . Lang::__('payment_operation') . '</a>';
                                                $text = Lang::__('now_go_to')  . " ". $checkoutLink . " ". Lang::__('to_complete_section_registeration');
                                            }

                                            if ($Student_Section->save()){

                                                $viewMessageBody = View::factory('emails/register_section');
                                                $viewMessageBody->username = $Student->username;
                                                $viewMessageBody->password = $objj->password;
                                                $viewMessageBody->Section = $Section;
                                                $viewMessageBody->buttonUrl = ORM::factory('Variables', 2)->value . 'Center/CartDetail';
                                                $viewMessageBody->logoUrl = URL::base().ORM::factory('Variables', 1)->value;
                                                $title = ORM::factory('Variables', 2)->{'value_'.$this->lang};
                                                $Message = Lang::safehtml($viewMessageBody);
                                                Model_Auth_User::SendEmail($title, $Student->email, "test", $Message);
                                                $user = Auth::instance()->login($Student->username, $objj->password, TRUE);
                                                $results['Success'] = array(
                                                    'theme' => 'lime', //teal,amethyst,ruby,tangerine,lemon,lime,ebony,smoke
                                                    'horizontalEdge' => 'top', //top,bottom
                                                    'verticalEdge' => 'right', //right,left
                                                    'heading' => '',
                                                    'life' => '1000', //1000 is 1 second
                                                    'text' => $text,
                                                );
                                            }
                                        }                                    
                                    } 
                                }
                
                            }
                        } catch (ORM_Validation_Exception $e) {

                            $errors = $e->errors('');
                            $results['Errors1'] = $errors;
                            $results['Errors'] = General::CatchErrorMSGSAjax($errors);
                        }
                    } else{
                        $results['Errors'] = Lang::__('there_is_an_account_for_this_id_email') ;
                    }
                }
            }

        } catch (ORM_Validation_Exception $e) {
            $errors = $e->errors('');
            $Lang_Error = array();
            foreach ($errors as $key => $value) {
                $srting = '';
                $errr = explode('*', $value);
                if (count($errr) > 0) {
                    foreach ($errr as $aaa) {
                        $srting .= Lang::__($aaa) . ' ';
                    }
                    array_push($Lang_Error, $srting);
                }
            }
            $results['Errors'] = $Lang_Error;
        }
        echo json_encode($results);
    }

    public function AddStudentSection($user_data, $Section){
        $op_result = array();
        if($user_data->user_groub == 3){  //if student
            $can_register_section = $this->CheckIfValidToRegisterSection($user_data->id, $Section->id);
            if($can_register_section["Response"]["status"] == FALSE){
                $op_result['Errors'] = $can_register_section["Response"]["Errors"] ;
                $op_result['redirect'] = $can_register_section["Response"]["redirect"];
            }else{
                $register_section = $this->AddStudentToSection($user_data,$Section);
                if($register_section["Response"]["status"] == TRUE){
                    $op_result['Success'] = array(
                        'text' => $register_section["Response"]["text"],
                        'redirect' => $register_section["Response"]["redirect"],
                    );
                }else{
                    $op_result['Errors'] = $register_section["Response"]["Errors"] ;
                }                                    
            }                          
        } else{
            $op_result['Errors'] = Lang::__('You_dont_have_permission_to_access_this_page');
        } 
        return $op_result;
    }

    public function CheckIfValidToRegisterSection($userId,$sectionId){
        $section_status = array();
        $Student_Section = ORM::factory('Students_Sections')
                                ->and_where_open()
                                    ->or_where('state','!=',4)
                                    ->or_where('state','=',NULL)
                                ->and_where_close()
                                ->where('section','=',$sectionId)
                                ->where('student','=',$userId)
                                ->where('is_deleted','=',NULL)
                                ->find();
        if($Student_Section->loaded()){
            if($Student_Section->state == 3){
                $section_status['Response'] = array(
                    'status' => FALSE,
                    'Errors' => Lang::__('Section already added to your schedule'),
                    'redirect' => URL::base() . 'NewHome_Sections/CourseDetails/' . $sectionId,
                );
            }else if (in_array($Student_Section->state, [1,2])){
                $section_status['Response'] = array(
                    'status' => FALSE,
                    'Errors' => Lang::__('waiting_teacher_approve'),
                    'redirect' => URL::base() . 'NewHome_Sections/CourseDetails/' . $sectionId,
                );
            }else if ($Student_Section->state == NULL){
                $section_status['Response'] = array(
                    'status' => FALSE,
                    'Errors' => Lang::__('waiting_payment_for_this_section'),
                    'redirect' => URL::base() . 'NewHome/Cart' ,
                );
            }
        }else{
            $section_status['Response'] = array(
                'status' => TRUE,
            );
        }
        return $section_status;
    }


    public function AddStudentToSection($user_data,$Section){
        $Res = array();
        $Student_Info = $user_data->Student_Information;
        if((empty($Student_Info->plan) || ($Section->plan == $Student_Info->plan)) && (empty($Student_Info->program) || ($Section->program == $Student_Info->program)) && (empty($Student_Info->major) || ($Section->major == $Student_Info->major)) && ($Section->college == $Student_Info->college)){
            $Student_Term = ORM::factory("Students_Terms")->where('is_deleted','=',NULL)->where('student','=',$user_data->id)->where('term','=',$Section->term)->find();
            $StudentApp = ORM::factory("Students_Applications")->where('is_deleted','=',NULL)->where('student_id','=',$user_data->id)->find();
            $Student_Section = ORM::factory('Students_Sections');
            if(!$Student_Term->loaded()){
                $Student_Term->student = $user_data->id;
                $Student_Term->term = $Section->term;
                $Student_Term->college = $StudentApp->college;
                $Student_Term->program = $StudentApp->program;
                $Student_Term->major = $StudentApp->major;
                $Student_Term->plan = $StudentApp->plan;
                $Student_Term->Created_by = $user_data->id;
                $Student_Term->Created_date = date("Y-m-d H:i:s");
                $Student_Term->save();
            }
            if($Section->is_free){
                $Student_Section->state = 3;  //approved for free sections
                $Student_Section->status = 1;  //approved for free sections
                $text = Lang::__('your_request_saved_successfully');
                $redirect = URL::base() . 'NewHome_Sections/CourseDetails/' . $Section->id;
            }else{
                $Student_Section->state = NULL;
                $checkoutLink =  Lang::__('payment_operation'); //'<a href="' . URL::base() . 'NewHome/Cart' . '">' . Lang::__('payment_operation') . '</a>';
                $text = Lang::__('now_go_to')  . " ". $checkoutLink . " ". Lang::__('to_complete_section_registeration');
                $redirect = URL::base() . 'NewHome/Cart';
            }
            $Student_Section->student = $user_data->id;
            $Student_Section->term = $Section->term;
            $Student_Section->section = $Section->id;
            $Student_Section->Created_by = $user_data->id;
            $Student_Section->Created_date = date("Y-m-d H:i:s");
            try{
                if ($Student_Section->save()) {
                    $Res['Response'] = array(
                        'status' => TRUE,
                        'text' => Lang::safehtml($text),
                        'redirect' => $redirect
                    );
                }
            }catch (ORM_Validation_Exception $e) {
                $errors = $e->errors('');
                $Res['Response'] = array(
                    'status' => FALSE,
                    'Errors' => General::ArrayToString(General::CatchErrorMSGSAjax($errors))
                );
            }
        } else{
            $Res['Response'] = array(
                'status' => FALSE,
                'Errors' =>  Lang::__('Section_not_for_your_grade')
            );
        } 
        return $Res;
    }

    public function action_DeleteSection(){
        $results = array();
        $req = Request::current(); //fillter requset
        $Filtered_array = Search::action_Filtered_array($req->post());
        $student_section_id = empty($Filtered_array['student_section_id']) ? NULL : $Filtered_array['student_section_id'];  //student section Id
        $SectionStudents = ORM::factory('Students_Sections', $student_section_id);
        if($SectionStudents->loaded()){
            try {
                $SectionStudents->delete();
                $results['Success'] = array(
                    'title' => Lang::__('Done'),
                    'content' => Lang::__('Deletion has been successfully'),
                );
                
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
                'content' => Lang::__('Not_found_the_desired_item'),
            );
        }
        echo json_encode($results);
    }



   

}