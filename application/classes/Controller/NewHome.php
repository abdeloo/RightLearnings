<?php

defined('SYSPATH') or die('No direct script access.');
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\IsMeetingRunningParameters;



class Controller_NewHome extends Controller_Template_Theme
{
    public function __construct(Request $request, Response $response) {
        // You must call parent::__construct at some point in your function
        parent::__construct($request, $response);
        // Do whatever else you want

        $this->Current_Term = ORM::factory('Study_Terms',ORm::factory('Variables',78)->value);
        $this->Genders = ORM::factory('General_Genders')->where('is_active', '=', 1)->where('is_deleted', '=', NULL)->find_all();
        $this->Countries = ORM::factory('General_Countries')->where('is_active', '=', 1)->where('is_deleted', '=', NULL)->find_all();
        $this->Centers = ORM::factory('Study_Colleges')->where('is_deleted','=',NULL)->find_all();
        if ($this->user_online && $this->user_online->user_groub == 3 && $this->user_online->Student_Information->S_Type == 1) {
            $this->studentId = $this->user_online->id;
        }else{
            $this->studentId = NULL;
        }
    }


    public function action_index() {

        $Ip_Setting = ORm::factory('Variables',116)->value ;

        if(!empty($this->user_online) && $this->user_online->college != NULL){
            $ip_country_code = $this->user_online->College->Country->countryCode;
            
        }else{
            $ip_country_code =  $this->ip_details(Detects::get_client_ip()); //"EG";
        }

        //$resp = Mailer::SendEmail("info@rightlearning.net", "info@rightlearning.net", "engamira333@gmail.com", "good morining", "hiiiiiiiiiiiii");


        if($Ip_Setting == "all"){
            if(!empty($this->user_online) && $this->user_online->user_groub == 3){
                $studentId = $this->user_online->id;
                $Term_Sections = ORM::factory('Study_Sections')
                                        ->with('College')
                                        ->with('College:Country')
                                        ->where('term','=',$this->Current_Term->id)
                                        ->where('status','=',1)
                                        ->where('parent','=',NULL)
                                        ->where('plan','=',$this->user_online->Student_Information->plan)
                                        ->where('display_main','=',1)
                                        ->where('study_sections.is_deleted','=',NULL)
                                        ->order_by('id', 'desc');

                $Packages = ORM::factory('Study_Sections')
                                        ->with('College')
                                        ->with('College:Country')
                                        ->where('term','=',$this->Current_Term->id)
                                        ->where('status','=',1)
                                        ->where('is_package','=',1)
                                        ->where('plan','=',$this->user_online->Student_Information->plan)
                                        ->where('display_main','=',1)
                                        ->where('study_sections.is_deleted','=',NULL)
                                        ->order_by('id', 'desc');
            }else{
                $studentId = NULL;
                $Term_Sections = ORM::factory('Study_Sections')
                                        ->with('College')
                                        ->with('College:Country')
                                        ->where('term','=',$this->Current_Term->id)
                                        ->where('status','=',1)
                                        ->where('parent','=',NULL)
                                        ->where('display_main','=',1)
                                        ->where('study_sections.is_deleted','=',NULL)
                                        ->order_by('id', 'desc');

                $Packages = ORM::factory('Study_Sections')
                                        ->with('College')
                                        ->with('College:Country')
                                        ->where('term','=',$this->Current_Term->id)
                                        ->where('status','=',1)
                                        ->where('is_package','=',1)
                                        ->where('display_main','=',1)
                                        ->where('study_sections.is_deleted','=',NULL)
                                        ->order_by('id', 'desc');
            }
        
            $allTeachers = ORM::factory('User')
                            ->and_where_open()
                                ->or_where('user_groub','=',4)
                                ->or_where('Employee_Information:Employee_Type.is_teacher','=',1)
                            ->and_where_close()
                            ->with('College')
                            ->with('College:Country')
                            ->where('user.is_deleted', '=', NULL)
                            //->where('Employee_Information.renewal_date','>', date("Y-m-d"))
                            ->where('Employee_Information.display_in_home','=', 1)
                            ->with('Employee_Information')
                            ->with('Employee_Information:Employee_Type')
                            ->find_all();
        }else{
            if(!empty($this->user_online) && $this->user_online->user_groub == 3){
                $studentId = $this->user_online->id;
                $Term_Sections = ORM::factory('Study_Sections')
                                        ->with('College')
                                        ->with('College:Country')
                                        ->where('term','=',$this->Current_Term->id)
                                        ->where('status','=',1)
                                        ->where('parent','=',NULL)
                                        ->where('plan','=',$this->user_online->Student_Information->plan)
                                        ->where('display_main','=',1)
                                        ->where('study_sections.is_deleted','=',NULL)
                                        ->where('College:Country.countryCode','=',$ip_country_code)
                                        ->order_by('id', 'desc');

                $Packages = ORM::factory('Study_Sections')
                                        ->with('College')
                                        ->with('College:Country')
                                        ->where('term','=',$this->Current_Term->id)
                                        ->where('status','=',1)
                                        ->where('is_package','=',1)
                                        ->where('plan','=',$this->user_online->Student_Information->plan)
                                        ->where('display_main','=',1)
                                        ->where('study_sections.is_deleted','=',NULL)
                                        ->where('College:Country.countryCode','=',$ip_country_code)
                                        ->order_by('id', 'desc');
            }else{
                $studentId = NULL;
                $Term_Sections = ORM::factory('Study_Sections')
                                        ->with('College')
                                        ->with('College:Country')
                                        ->where('term','=',$this->Current_Term->id)
                                        ->where('status','=',1)
                                        ->where('parent','=',NULL)
                                        ->where('display_main','=',1)
                                        ->where('study_sections.is_deleted','=',NULL)
                                        ->where('College:Country.countryCode','=',$ip_country_code)
                                        ->order_by('id', 'desc');

                $Packages = ORM::factory('Study_Sections')
                                        ->with('College')
                                        ->with('College:Country')
                                        ->where('term','=',$this->Current_Term->id)
                                        ->where('status','=',1)
                                        ->where('is_package','=',1)
                                        ->where('display_main','=',1)
                                        ->where('study_sections.is_deleted','=',NULL)
                                        ->where('College:Country.countryCode','=',$ip_country_code)
                                        ->order_by('id', 'desc');
            }
        
            $allTeachers = ORM::factory('User')
                            ->and_where_open()
                                ->or_where('user_groub','=',4)
                                ->or_where('Employee_Information:Employee_Type.is_teacher','=',1)
                            ->and_where_close()
                            ->with('College')
                            ->with('College:Country')
                            ->where('user.is_deleted', '=', NULL)
                            ->where('Employee_Information.renewal_date','>', date("Y-m-d"))
                            ->where('Employee_Information.display_in_home','=', 1)
                            ->with('Employee_Information')
                            ->with('Employee_Information:Employee_Type')
                            ->where('College:Country.countryCode','=',$ip_country_code)
                            ->find_all();

           
        }

        if($Ip_Setting == "all"){
            $Schools = ORM::factory('Study_Colleges')->where('display_in_home','=',1)->order_by(DB::expr('RAND()'))->limit(3)->find_all();
            $Random_School = ORM::factory('Study_Colleges')->where('display_in_home','=',1)->order_by(DB::expr('RAND()'))->find();
        }else{
            $Schools = ORM::factory('Study_Colleges')->where('display_in_home','=',1)->with('Country')->where('Country.countryCode','=',$ip_country_code)->order_by(DB::expr('RAND()'))->limit(3)->find_all();
            $Random_School = ORM::factory('Study_Colleges')->where('display_in_home','=',1)->with('Country')->order_by(DB::expr('RAND()'))->find();
        }

        $this->template->layout = new View('new_theme/index_3');

        $Recent_Sections = ORM::factory('Study_Sections')->where('is_package','=',NULL)->where('parent','=',NULL)->where('is_deleted','=',NULL)->order_by('id','DESC')->limit(6)->find_all();

        $Recent_Events = ORM::factory('Events')
                            //->where('event_date','>=',date("Y-m-d"))
                            ->where('is_deleted','=',NULL)
                            ->order_by('event_date','DESC')
                            //->order_by(DB::expr('RAND()'))
                            //->limit(3)
                            ->find_all();
        

        $SortedPackages = array();
        $Packages = $Packages->order_by('start_date','DESC')->find_all();
        foreach($Packages as $Package){
            $logo = (empty($Package->logo_path))? (empty($Package->College->file_path))? ORM::factory('Variables',1)->value : $Package->College->file_path : $Package->logo_path;

            $Students = $Package->Students_Sections->where('state','=',3)->where('is_deleted','=',NULL)->count_all();
            array_push($SortedPackages, array("details" => $Package, "sections" => $Package->Sections->where('is_deleted','=',NULL)->find_all(), "students_count" => $Students, "logo"=> $logo));
        }

        $counts = array_column($SortedPackages, 'students_count');
        array_multisort($counts, SORT_DESC, $SortedPackages);
        

        $this->template->layout->Teachers = $allTeachers;
        $this->template->layout->Schools = $Schools;
        $this->template->layout->Random_School = $Random_School;
        $this->template->layout->Packages = $SortedPackages;

        $this->template->layout->Total_Sections = ORM::factory('Study_Sections')->where('is_deleted','=',NULL)->count_all();
        $this->template->layout->Total_Teachers = ORM::factory('User')->where('user_groub','=',4)->where('is_deleted','=',NULL)->count_all();
        $this->template->layout->Total_Students = ORM::factory('User')->where('user_groub','=',3)->where('is_deleted','=',NULL)->count_all();
        $this->template->layout->Total_Centers = ORM::factory('Study_Colleges')->where('is_deleted','=',NULL)->count_all();


        $title = $this->Default_Title;
        $this->template->layout->title = $title;
        $this->template->title = $title;
        $this->template->layout->logo = $this->logo;
        $this->template->layout->dir =  ($this->lang == 'ar')? 'rtl' : 'ltr';;
        $this->template->layout->lang = $this->lang;
        $this->template->layout->Recent_Sections = $Recent_Sections;
        $this->template->layout->Recent_Events = $Recent_Events;

        $this->template->layout->first_part = ORM::factory('Variables', 125);
        $this->template->layout->first_part_button = ORM::factory('Variables', 129);
        $this->template->layout->part_1 = ORM::factory('Variables', 126);
        $this->template->layout->part_2 = ORM::factory('Variables', 127);
        $this->template->layout->part_3 = ORM::factory('Variables', 128);

        $this->template->layout->second_part = ORM::factory('Variables', 130);
        $this->template->layout->second_part_description = ORM::factory('Variables', 131);
        $this->template->layout->second_part_middle_description = ORM::factory('Variables', 132);
        $this->template->layout->second_part_middle_description2 = ORM::factory('Variables', 133);
        $this->template->layout->second_part_flag = ORM::factory('Variables', 134);
        $this->template->layout->second_part_flag2 = ORM::factory('Variables', 135);
        $this->template->layout->footer = $this->footer;

        $this->template->layout->Genders = $this->Genders;
        $this->template->layout->Countries = $this->Countries;
        $this->template->layout->Centers = $this->Centers;

        $this->template->layout->user_online = $this->user_online;

        $this->template->layout->Waiting_courses = (isset($this->Waiting_courses))? $this->Waiting_courses : array();


    }

    public function action_Cart() {
        $studentId = $this->studentId;
        $Student_Sections = ORM::factory('Students_Sections')
                                ->and_where_open()
                                    ->or_where('state','!=',4)
                                    ->or_where('state','=',NULL)
                                ->and_where_close()
                                ->where('paid','!=',1)
                                ->where('student','=',$studentId)
                                ->where('students_sections.term','=', $this->Current_Term->id)
                                ->where('students_sections.is_deleted','=',NULL)
                                ->where('Section.parent','=',NULL)
                                ->with('Section')
                                ->find_all();
        $this->template->layout = new View('new_theme/cart');
        $this->template->layout->lang = $this->lang;
        $this->template->layout->Student_Sections = $Student_Sections;
        $this->template->layout->studentId = $this->studentId;
    }

    public function action_InstructorProfile() {
        $teacherId = $this->request->param('par1');
        $Teacher = ORM::factory('User',$teacherId);
        $Application = $Teacher->Employee_Information ;
        if(isset($Application) && ($Application->img_path != NULL)){
            $img_path = $Application->img_path;
        }else{
            $img_path = 'assets/site/images/default-user.png';
        }
        $Teacher_Sections = ORM::factory('Study_Sections')->where('term','=',$this->Current_Term->id)->where('status','=',1)->where('teacher','=',$teacherId)->where('is_deleted','=',NULL)->find_all();
        

        $this->template->layout = new View('new_theme/instructor_profile');
        $this->template->layout->lang = $this->lang;
        $this->template->layout->Teacher = $Teacher;
        $this->template->layout->Application = $Application;
        $this->template->layout->Teacher_Sections = $Teacher_Sections;
        $this->template->layout->img_path = $img_path;
        $this->template->layout->studentId = $this->studentId;
    }

    public function action_Contact() {
        $center_name = ORM::factory('Variables', 2)->{'value_'.$this->lang};
        $center_address = ORM::factory('Variables', 23)->{'value_'.$this->lang};
        $center_phone = ORM::factory('Variables', 4)->value ;
        $center_email = ORM::factory('Variables', 11)->value ;
        $center_lat = ORM::factory('Variables', 122)->value_ar ; #"30.01160468688256";
        $center_lng = ORM::factory('Variables', 122)->value_en ; #" 31.4929510357267";

        $this->template->layout = new View('new_theme/contact');
        $this->template->layout->lang = $this->lang;
        $this->template->layout->center_name = empty($center_name) ? ORM::factory('Variables', 2)->{'value_'.$this->lang}  : $center_name; 
        $this->template->layout->center_address = empty($center_address) ? ORM::factory('Variables', 23)->{'value_'.$this->lang}  : $center_address; 
        $this->template->layout->center_phone = empty($center_phone) ? ORM::factory('Variables', 4)->value  : $center_phone; 
        $this->template->layout->center_email = empty($center_email) ? ORM::factory('Variables', 11)->value  : $center_email; 
        $this->template->layout->center_lat = empty($center_lat) ? "30.01160468688256" : $center_lat; 
        $this->template->layout->center_lng = empty($center_lng) ? "31.4929510357267" : $center_lng; 
    }
    

    public function ip_details($IPaddress) 
    {
        $json = file_get_contents("http://www.geoplugin.net/json.gp?ip={$IPaddress}");
        if(!empty($json)){
            $details    = json_decode($json);
            $countryCode = "EG";
            //$countryCode = $details->geoplugin_countryCode;
        }else{
            $countryCode = "EG";
        }
        return $countryCode;
    }

   
   

}