<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_NewHome_Live_Meetings extends Controller_Template_Theme
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
        $this->template->layout = new View('new_theme/live_meetings/view_all');
        $this->template->layout->lang = $this->lang;
        $this->template->layout->live_meetings = $this->GetLiveMeetings($this->user_online);       
        
    }


    public static function GetLiveMeetings($user_online) {

        $live_classes = array();
        $class_types = ["","our_server","zoom","wiziq","google"];
        //get meeting for registered section if student
        if(isset($user_online) && $user_online->user_groub == 3){
            $Student_Sections = $user_online->Student_Sections->where('status','=',1)->where('state','=',3)->where('is_deleted','=',NULL)->find_all();
            foreach($Student_Sections as $Student_Section){
                $Section = $Student_Section->Section;
                $section_logo = ($Section->logo_path != NULL)? $Section->logo_path : $Section->Course->img;
                if($section_logo == NULL ){   
                    $section_logo = ($Section->College->file_path != NULL)?  $Section->College->file_path : ORM::factory('Variables', 1)->value;
                }
                $Check_Online_Courses = Model_VClassrooms_Classes::GetAllNextMeetings($Student_Section->section);
                if(!empty($Check_Online_Courses)){
                    foreach($Check_Online_Courses as $Check_Online_Course){
                        $is_live = FALSE;
                        if($Check_Online_Course->class_type == 1 && $Check_Online_Course->class_id != NULL){         //local server
                            try{
                                $start_date = new DateTime($Check_Online_Course->start_time);
                                if($start_date->format('Y-m-d') == date('Y-m-d')){
                                    $check_running_meeting = Controller_Student_Section::checkRunningMeeting($Check_Online_Course->class_id, $Check_Online_Course->moderator_pw);
                                    if($check_running_meeting == "true"){
                                        $is_live = TRUE;
                                    }
                                }
                            }catch(Exception $e){
                                $is_live = FALSE;
                            }
                        }
                        array_push($live_classes, array("id"=>$Check_Online_Course->id,"class_id" => $Check_Online_Course->class_id,"is_live"=> $is_live,"logo"=> $section_logo ,"app_name" => $class_types[$Check_Online_Course->class_type],"start_time" => $Check_Online_Course->start_time,"title"=>$Check_Online_Course->title,"duration"=>$Check_Online_Course->duration,"type"=>"section_class"));
                    }
                }
            }
        }

        return $live_classes;
    }



    public function action_View() {
        $type = (!empty($this->request->param('par1')))? $this->request->param('par1')  : NULL;
        $class_id = (!empty($this->request->param('par2')))? $this->request->param('par2')  : NULL;

        if($type == "section_class"){
            $virtual_classroom = ORM::factory('VClassrooms_Classes',$class_id);
        }
        $this->template->layout = new View('new_theme/live_meetings/show');
        $this->template->layout->lang = $this->lang;
        $this->template->layout->VirtualClassroom = $virtual_classroom;
    }




    //attend virtual classe
    public function action_AttendClass() {
        $req = Request::current(); //fillter requset
        $Filtered_array = Search::action_Filtered_array($req->post());
        $details = array();
        $class_id = $Filtered_array['par1']; 
        if(!empty($class_id)){
            $vclass_id=ORM::factory('VClassrooms_Classes')->where('class_id','=',$class_id)->find();  
            $virtual_class_site =  $vclass_id->class_type;         
            $details['attendee_id'] = $this->user_online->id;
            $details['screen_name'] = $this->user_online->name_ar;
            $details['class_id'] = $class_id;	    
            $previous_urls =  ORM::factory('VClassrooms_Urls')->where('student','=',$this->user_online->id)->where('class_id','=',$vclass_id)->find();
            if($previous_urls->loaded()){
                if (!empty($previous_urls->attendee_url)){                
                    $results['Success'] = array(
                        'title' => Lang::__('Done'),
                        'content' => Lang::__('you will redirected to the class'),
                        'redirect' => $previous_urls->attendee_url,
                    );                
                }
                else{
                    $results['Errors'] = Lang::__('not_found_url');
                }
            }else{
                if($virtual_class_site == 3){
                    $keys = Model_VClassrooms_Accounts::GetKeys();	//get api keys
                    $response= WizIQ_AddAttendee::AddAttendee($details,$keys);
                }elseif($virtual_class_site == 1){
                    $response = Common_Livemeeting_BBB::Join_Meeting($vclass_id->class_id, $details['screen_name'], $vclass_id->attendee_pw, NULL);   
                    $url = $response["url"]   ;  
                }
                if(($virtual_class_site == 3 && !empty($response['Success'])) || ($virtual_class_site == 1 && ($url != NULL))){ 
                    $attende_url =  ORM::factory('VClassrooms_Urls');
                    if($virtual_class_site == 3){
                        $attende_url->student=$response['Success']['attendee_id'] ;
                        $attende_url->attendee_url=$response['Success']['attendee_url'] ; 
                        $url = $response['Success']['attendee_url'];
                    }elseif($virtual_class_site == 1){
                        $attende_url->student = $this->user_online->id;
                        $attende_url->attendee_url=$url ; 
                    }
                    $attende_url->class_id=$vclass_id;
                    $attende_url->Created_by = $this->user_online->id;
                    $attende_url->Created_date = date("Y-m-d H:i:s");
                    $attende_url->save();
                    $results['Success'] = array(
                        'title' => Lang::__('Done'),
                        'content' => Lang::__('you will redirected to the class'),
                        'redirect' => $url,
                    );                
                }
                else{
                    if($virtual_class_site == 3){
                        $results['Errors'] = $response['Errors']['errorcode'] . " - " . Lang::__($response['Errors']['errormsg']);
                    }elseif($virtual_class_site == 1){
                        $results['Errors'] = Lang::__('not_found_url');
                    }
                }
            }
        } else{
            $results['Errors'] = array(
                'title' => Lang::__('error'),
                'content' => Lang::__('You_dont_have_permission_to_access_this_page'),
            );
        }
        echo json_encode($results);
    }



    public function action_Close_Class() {
        $results = array();
            $req = Request::current(); //fillter requset
            $Filtered_array = Search::action_Filtered_array($req->post());
            $Obj = ORM::factory('VClassrooms_Classes', $Filtered_array['id']);
                try {
                    $Obj->completed = 1;
                    $Obj->last_update_by = $this->user_online->id;
                    $Obj->last_update_date = date("Y-m-d H:i:s");
                    if ($Obj->save()) {
                        if($Obj->class_type == 1){
                            $response = Common_Livemeeting_BBB::End_Meeting($Obj->class_id, $Obj->moderator_pw);   
                            if($response == "SUCCESS"){
                                $results['Success'] = array(
                                    'title' => Lang::__('Done'),
                                    'content' => Lang::__('Deletion has been successfully'),
                                );
                            }else{
                                $results['Errors'] = array(
                                    'title' => Lang::__('Error'),
                                    'content' => Lang::__('Could_not_complete_request_Please_check_your_internet_connection'),
                                );
                            }
                        }
                    }
                } catch (ORM_Validation_Exception $e) {

                    $errors = $e->errors('');
                    $results['Errors'] = array(
                        'title' => Lang::__('Error'),
                        'content' => General::CatchErrorMSGSAjax($errors),
                    );
                }

        echo json_encode($results);
    }



   
   

}