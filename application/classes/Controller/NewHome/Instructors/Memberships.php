<?php

defined('SYSPATH') or die('No direct script access.');
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\IsMeetingRunningParameters;



class Controller_NewHome_Instructors_Memberships extends Controller_Template_Theme
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
        $this->template->layout = new View('new_theme/instractors/membership');
        $this->template->layout->instructors_plans = ORM::factory('Membershipplans')->where('membership_type','=',1)->where('is_deleted','=',NULL)->find_all();
        $this->template->layout->centers_plans = ORM::factory('Membershipplans')->where('membership_type','=',2)->where('is_deleted','=',NULL)->find_all();
        $this->template->layout->Students_Reviews = ORM::factory('Study_Sections_Comments')->where('item_type','=',NULL)->where('item_id','=',NULL)->find_all();
        $this->template->layout->lang = $this->lang;
    }

    public function action_Apply() {

        $this->template->layout = new View('new_theme/instractors/apply');
        $this->template->layout->Students_Count = ORM::factory('User')->where('is_deleted','=',NULL)->where('user_groub','=',3)->count_all();
        $this->template->layout->Teachers_Count = ORM::factory('User')->where('user_groub','=',4)->where('is_deleted','=',NULL)->count_all();
        $this->template->layout->Completed_Sections_Count = ORM::factory('Study_Sections')->where('end_date','<=',date("Y-m-d H:i:s"))->where('status','=',1)->where('is_deleted','=',NULL)->count_all();
        $this->template->layout->Sections_Count = ORM::factory('Study_Sections')->where('status','=',1)->where('is_deleted','=',NULL)->count_all();



    }

    public function action_Register() {

        if(isset($_GET["plan_id"])){
            $plan_id = $_GET["plan_id"];
            $Plan = ORM::factory('Membershipplans', $plan_id);
            if($Plan->loaded()){
                $app_type = ($Plan->membership_type == 1)? 2 : 3;             //2 for instructor 3 for center
                $this->template->layout = new View('new_theme/instractors/register');
                $this->template->layout->Centers = ORM::factory('Study_Colleges')->where('is_deleted','=',NULL)->find_all();
                $this->template->layout->Countries = ORM::factory('General_Countries')->where('is_active', '=', 1)->where('is_deleted', '=', NULL)->find_all();
                $this->template->layout->Courses = ORM::factory('Study_Courses')->where('is_deleted','=',NULL)->find_all();
                $this->template->layout->Genders = ORM::factory('General_Genders')->where('is_active', '=', 1)->where('is_deleted', '=', NULL)->find_all();
                $this->template->layout->lang = $this->lang;
                $this->template->layout->app_type = $app_type;
                $this->template->layout->Plan = $Plan;
            } else {
                $this->template->layout = new View('new_theme/system_messages/show_msg');
                $this->template->layout->class_color = 'danger';
                $this->template->layout->msg = Lang::__('You_dont_have_permission_to_access_this_page');
            }
        } else {
            $this->template->layout = new View('new_theme/system_messages/show_msg');
            $this->template->layout->class_color = 'danger';
            $this->template->layout->msg = Lang::__('You tracked wrong or expired link');
        }

    }


    public function action_Checkout() {
        $plan_id = $_GET["plan_id"];
        $Plan = ORM::factory('Membershipplans', $plan_id);
        $app_type = ($Plan->membership_type == 1)? 2 : 3;             //2 for instructor 3 for center
        
        $this->template->layout = new View('new_theme/instractors/checkout');
        $this->template->layout->Centers = ORM::factory('Study_Colleges')->where('is_deleted','=',NULL)->find_all();
        $this->template->layout->Countries = ORM::factory('General_Countries')->where('is_active', '=', 1)->where('is_deleted', '=', NULL)->find_all();
        $this->template->layout->Courses = ORM::factory('Study_Courses')->where('is_deleted','=',NULL)->find_all();
        $this->template->layout->lang = $this->lang;
        $this->template->layout->app_type = $app_type;
        $this->template->layout->Plan = $Plan;


    }


    public function action_AddEmployee() {
        $results = array();

        $req = Request::current(); //fillter requset
        $Filtered_array = Search::action_Filtered_array($req->post());

        $par1 = empty($Filtered_array['par1']) ? NULL : $Filtered_array['par1'];
        $par2 = empty($Filtered_array['par2']) ? NULL : $Filtered_array['par2'];
        $membership_plan = empty($Filtered_array['membership_plan']) ? NULL : $Filtered_array['membership_plan'];

        //Path of upload images
        $directory = 'files/hr/' . date('Y') . '/' . date("m") . '/';
        $directory_thumbs = $directory . 'thumbs/';

        $objj = ORM::factory('Hr_Employment_Applications', $par1);

        $session = Session::instance();

        $Access = FALSE;
        if ($objj->loaded()) {
            // Find existing data
            $data = $session->get('Emp_Arr');
            if (is_array($data)) {
                foreach ($data as $elm) {
                    $id_ = !empty($elm['id']) ? $elm['id'] : NULL;
                    $password_ = !empty($elm['password']) ? $elm['password'] : NULL;

                    if ((mb_strtolower($id_) == mb_strtolower($objj->app_id)) && mb_strtolower($password_) == mb_strtolower($objj->app_password)) {
                        $Access = TRUE;
                    }
                }
            }
        }
        $NewAppState = (ORM::factory('Variables', 86)->value == "Open") ? TRUE : FALSE;
        if ((!$objj->loaded() && $NewAppState) || ($objj->loaded() && $Access == TRUE)) {
            $New_App = TRUE;
            if (!$objj->loaded()) {
                $objj->Created_by = !empty($this->user_online->id) ? $this->user_online->id : NULL;
                $objj->Created_date = date("Y-m-d H:i:s");
                //Generate App id
                $app_id = date('y') . Text::random('nozero', 8);
                while (TRUE) {
                    $check = ORM::factory('Hr_Employment_Applications')->where('app_id', '=', $app_id)->find();
                    if ($check->loaded()) {
                        $app_id = date('y') . Text::random('nozero', rand(4, 7));
                    } else {
                        break;
                    }
                }
                //End Generate App id
                $objj->app_id = $app_id;
                $objj->app_password = mb_strtolower(Text::random('nozero', rand(4, 7)));
            } else {
                $objj->last_update_by = !empty($this->user_online->id) ? $this->user_online->id : NULL;
                $objj->last_update_date = date("Y-m-d H:i:s");
                $New_App = FALSE;
            }

            $objj->college = $Filtered_array['college'];
            $Jquery_Rules = $objj->VisitorRules($objj, FALSE);

            $upload_images_error = array();
            if ($this->request->method() == Request::POST) {
                $FileS = isset($_FILES['img_path']) ? $_FILES['img_path'] : NULL;
                if (!empty($FileS) && Upload::valid($FileS) && Upload::not_empty($FileS)) {

                    $allowed_ext = isset($Jquery_Rules['img_path']['accept']) ? explode('|', $Jquery_Rules['img_path']['accept']) : array();
                    $max_size = isset($Jquery_Rules['img_path']['filesize']) ? $Jquery_Rules['img_path']['filesize'] : 5000000;
                    $img_path = $this->_save_image($_FILES['img_path'], NULL, NULL, $directory, NULL, $allowed_ext, $max_size . 'B');
                    if ($img_path) {

                        foreach (array('img_path') as $value) {
                            if (!empty($objj->$value)) {
                                $DelFilePath = DOCROOT . $objj->$value;
                                if (is_file($DelFilePath)) {
                                    unlink($DelFilePath);
                                }
                            }
                        }
                        $Filtered_array['img_path'] = $directory . $img_path;
                    } else {
                        array_push($upload_images_error, Lang::__('img_path'));
                    }
                }
                $cv_file = isset($_FILES['cv_file']) ? $_FILES['cv_file'] : NULL;
                if (!empty($cv_file) && Upload::valid($cv_file) && Upload::not_empty($cv_file)) {
                    $allowed_ext = isset($Jquery_Rules['cv_file']['accept']) ? explode('|', $Jquery_Rules['cv_file']['accept']) : array();
                    $max_size = isset($Jquery_Rules['cv_file']['filesize']) ? $Jquery_Rules['cv_file']['filesize'] : 5000000;
                    $cv_file = $this->_save_image($_FILES['cv_file'], NULL, NULL, $directory, NULL, $allowed_ext, $max_size . 'B');
                    if ($cv_file) {

                        foreach (array('cv_file') as $value) {
                            if (!empty($objj->$value)) {
                                $DelFilePath = DOCROOT . $objj->$value;
                                if (is_file($DelFilePath)) {
                                    unlink($DelFilePath);
                                }
                            }
                        }
                        $Filtered_array['cv_file'] = $directory . $cv_file;
                    } else {
                        array_push($upload_images_error, Lang::__('cv_file'));
                    }
                }
            }

            if($Filtered_array['app_type'] != 3){     // اذا لم يكن نوع الطلب مدير مركز
                //المواد التى يدرسها
                if (empty($Filtered_array['courses'])) {
                    $objj->course = NULL;
                } else {
                    $courses = "";
                    foreach ($Filtered_array['courses'] as $value) {
                        $courses = $courses . $value . ",";
                    }
                    $objj->course = $courses;
                }

                //الفروع التى يعمل بها
                if (empty($Filtered_array['majors'])) {
                    $objj->major = NULL;
                } else {
                    $majors = "";
                    foreach ($Filtered_array['majors'] as $value) {
                        $majors = $majors . $value . ",";
                    }
                    $objj->major = $majors;
                }

                //المراحل التى يعمل بها
                if (empty($Filtered_array['programs'])) {
                    $objj->program = NULL;
                } else {
                    $programs = "";
                    foreach ($Filtered_array['programs'] as $value) {
                        $programs = $programs . $value . ",";
                    }
                    $objj->program = $programs;
                }

                //الصفوف التى يعمل بها
                if (empty($Filtered_array['plans'])) {
                    $objj->plan = NULL;
                } else {
                    $plans = "";
                    foreach ($Filtered_array['plans'] as $value) {
                        $plans = $plans . $value . ",";
                    }
                    $objj->plan = $plans;
                }
                $objj->center_name = NULL;
                $objj->from_college = NULL;
            }else if($Filtered_array['app_type'] == 3){
                $objj->referred_by = $Filtered_array['par2'] ;  
                $objj->course = NULL;
                $objj->major = NULL;
                $objj->program = NULL;
                $objj->plan = NULL;
                $Filtered_array['college'] = NULL;    
            }

            //Start allowed fields check
            $ArrayOfPrevents = array("app_status", "notes", "app_id", "app_password", "hr_section", "employee_type", "department", "insurance_number", "hr_position", "starting_date", "employee_not_in_payroll", "job_title", "basic_salary", "bank", "iban", "is_deleted", "deleted_by", "deleted_date", "Created_by", "Created_date", "last_update_by", "last_update_date", "approved_date", "approved_by", "rejected_date", "rejected_by");
            foreach ($Filtered_array as $key => $value) {
                if (in_array($key, $ArrayOfPrevents)) {
                    unset($Filtered_array[$key]);
                }
            }
            //End allowed fields check

            //set default values for empty columns
            $rnd = mt_rand(1000, 9999);
            if(empty($Filtered_array["id_no"])){
                $Filtered_array["id_no"] = Text::random('nozero', rand(8, 8));
            }
            if(empty($Filtered_array["email"])){
                $Filtered_array["email"] = $rnd . "@email.com";
            }
            if(empty($Filtered_array["name_first_ar"])){
                $Filtered_array["name_first_ar"] = "موظف جديد";
            }
            if(empty($Filtered_array["name_first_en"])){
                $Filtered_array["name_first_en"] = "New Member";
            }

            $objj->values($Filtered_array);
            $extra_rules = General::Jquery_To_ORM_Rules($Filtered_array, $Jquery_Rules);

            if (empty($upload_images_error)) {
                try {

                    if ($objj->save($extra_rules)) {                     

                        if (empty($objj->last_update_date)) {
                            //بداية انشاء علاقات الموافقات المطلوبة...
                                $ApprovalStep = ORM::factory('Trainers_Approvals')->where('type', '=', "employment_apps")->where('is_deleted', '=', NULL)->order_by('order')->find_all();
                                if(count($ApprovalStep) > 0){
                                    $department_array = array();
                                    foreach ($ApprovalStep as $value) {
                                        $department = $value->department;                        
                                        if (!in_array($department, $department_array)) {
                                            array_push($department_array, array('order' => $value->order, 'department' => $department));
                                        }
                                    }
                                    foreach ($department_array as $value) {
                                        //لعدم تكرار نفس القسم للموافقة
                                        $Prev = ORM::factory('Hr_Employment_Applications_Approvals')
                                                ->where('department', '=', $value['department'])
                                                ->where('app_id', '=', $objj->id)
                                                ->find();
                                        if (!$Prev->loaded()) {
                                            $O = ORM::factory('Hr_Employment_Applications_Approvals');
                                            $O->order = $value['order'];
                                            $O->department = $value['department'];
                                            $O->app_id = $objj->id;
                                            $O->Created_by = !empty($this->user_online->id) ? $this->user_online->id : NULL;
                                            $O->Created_date = date("Y-m-d H:i:s");
                                            $O->save();
                                        }
                                    }
                                }else{
                                    if($objj->app_type == 3){
                                        $user_online_id = !empty($this->user_online) ? $this->user_online->id : NULL;
                                        $Groub = ORM::factory('Usersgroub', 4); // مدير
                                        $Center = Controller_Admission_Employment::CreateNewCenter($objj,$user_online_id);
                                        $user_college = $Center->id;
                                    }else if($objj->app_type == 2){
                                        $current_date = new DateTime();
                                        $current_date = $current_date->modify('+30 day')->format('Y-m-d H:i:s');
                                        $Groub = ORM::factory('Usersgroub', 4); // مدرس
                                        $user_college = $objj->college;
                                        $objj->renewal_date = $current_date;
                                    }else{
                                        $Groub = ORM::factory('Usersgroub', 5); // موظف
                                        $user_college = $objj->college;
                                    }

                                    $password = Text::random('nozero', rand(8, 8));
                                    $username = $objj->GenerateAcadimicNo();
                                    $User = ORM::factory('User');
                                    $objj->college = $user_college ;
                                    $User->college = $user_college ;
                                    $User->username = $username;
                                    $User->password = $password;
                                    $User->email = $objj->email;
                                    $User->user_groub = $Groub->id;
                                    $User->name_ar = $objj->name_first_ar . ' ' . $objj->name_father_ar . ' ' . $objj->name_grandfather_ar . ' ' . $objj->name_last_ar;
                                    $User->name_en = $objj->name_first_en . ' ' . $objj->name_father_en . ' ' . $objj->name_grandfather_en . ' ' . $objj->name_last_en;
                                    $User->Created_by = NULL;
                                    $User->Created_date = date("Y-m-d H:i:s");
                                    if ($User->save()) {
                                        // Grant user login role
                                        $User->add('roles', ORM::factory('Role', array('name' => 'login')));
                                        if($objj->app_type == 3){   //أضف صلاحية مدير مركز
                                            $CenterAdminGroup = ORM::factory('Permissions_Groups')->where('name_en','=',"center management")->where('is_deleted','=',NULL)->find();
                                            $CenterAdminPermission = ORM::factory('Permissions_Usersrelations');
                                            $CenterAdminPermission->user = $User->id;
                                            $CenterAdminPermission->groub = $CenterAdminGroup->id;
                                            $CenterAdminPermission->Created_by = NULL;
                                            $CenterAdminPermission->Created_date = date("Y-m-d H:i:s");
                                            $CenterAdminPermission->save();
                                        }
                                        $objj->user_id = $User->id;
                                        $objj->user_password = $password;
                                        $objj->update();

                                        $membership_name = $username;
                                        $membership_password = $password;

                                        //send email to the user
                                        $viewMessageBody = View::factory('emails/student_registeration');
                                        $viewMessageBody->name = $User->name_ar;
                                        $viewMessageBody->username = $User->username;
                                        $viewMessageBody->password = $password;
                                        $viewMessageBody->text = "تم إنشاء عضويتك بنجاح وهذه بيانات الحساب";
                                        $viewMessageBody->status = "approved";
                                        $viewMessageBody->qrCode = (!empty($qr_image_path))? $qr_image_path : NULL ;
                                        $viewMessageBody->buttonUrl = ORM::factory('Variables', 2)->value . 'NewHome';
                                        $viewMessageBody->logoUrl = ORM::factory('Variables', 2)->value . ORM::factory('Variables', 1)->value;
                                        $title = ORM::factory('Variables', 2)->{'value_'.$this->lang};
                                        $viewMessageBody->title = $title;
                                        $Message = Lang::safehtml($viewMessageBody);
                                        Mailer::SendEmail(null, null, $User->email, ORM::factory('Variables',2)->value_ar, $viewMessageBody);
                                    
                                    }
                                }
                            //نهاية انشاء علاقات الموافقات المطلوبة...    
                        } 
                        
                        if ($New_App == TRUE) {
                            if(!empty($membership_name)){
                                $membership_name = $membership_name;
                                $membership_password = $membership_password;
                                $type = "membership_created";
                            }else{
                                $membership_name = $objj->app_id;
                                $membership_password = $objj->app_password;
                                $type = "application_created";
                            }
                            //$HtmlMsg = View::factory("site/admission/employment/sucess_msg_first_time")->set('app_id', $objj->app_id)->set('app_password', $objj->app_password)->render();
                            $HtmlMsg = View::factory("new_theme/instractors/sucess_msg_first_time")->set('type', $type)->set('app_id', $membership_name)->set('app_password', $membership_password)->set('app_type',$objj->app_type)->render();
                            $results['Success'] = array(
                                'title' => Lang::__('Done'),
                                'HtmlMsg' => $HtmlMsg,
                            );
                        } else {
                            if($objj->membership_plan != NULL){
                                $redirect_link = $objj->id . "?plan_id=" . $objj->membership_plan ;
                            }else{
                                $redirect_link = $objj->id;
                            }
                            $results['SuccessR'] = array(
                                'title' => Lang::__('Done'),
                                'content' => Lang::__('Saved_successfully'),
                                'Redirect' => URL::base() . "NewHome_Instructors_Memberships/Register/" . $redirect_link  ,
                            );
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
                    'content' => Lang::__('The following images or files could not be uploaded') . '</br>' . General::ArrayToString($upload_images_error)
                );
            }
        } else {
            if (!$objj->loaded() && !$NewAppState) {
                $results['Errors'] = array(
                    'title' => Lang::__('Error'),
                    'content' => nl2br(ORM::factory('Variables', 87)->{'text_' . $this->lang})
                );
            }
        }


        echo json_encode($results);

    }

    protected function _save_image($image, $width = NULL, $height = NULL, $directory, $directory_thumbs, $extensions, $max_file_size)
    {
        if (!Upload::valid($image) OR !Upload::not_empty($image) OR !Upload::type($image, $extensions) OR !Upload::size($image, $max_file_size)) {
            return FALSE;
        }

        if (!empty($directory)) {
            $directory_D = DOCROOT . str_replace("\\", "/", $directory);
            if (!file_exists($directory_D)) {
                mkdir($directory_D, 0777, true);
            }
        }

        if (!empty($directory_thumbs)) {
            $directory_thumbs_D = DOCROOT . str_replace("\\", "/", $directory_thumbs);
            if (!file_exists($directory_thumbs_D)) {
                mkdir($directory_thumbs_D, 0777, true);
            }
        }

        $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
        $filename = mb_strtolower(Text::random('alnum', rand(10, 30))) . '.' . $ext;

        if (in_array(mb_strtolower($ext), array("gif", "jpeg", "jpg", "png", "bmp"))) {
            if ($file = Upload::save($image, NULL, $directory)) {
                $img = Image::factory($file);
                $img->save($directory . $filename, 100);
                if (!empty($directory_thumbs)) {
                    $img->resize($width, $height, Image::INVERSE);
                    $img->save($directory_thumbs . $filename, 100);
                }
                // Delete the temporary file
                unlink($file);

                return $filename;
            }
        } else {
            if ($file = Upload::save($image, $filename, $directory)) {

                return $filename;
            }
        }
    }





 
   

}

// End Home
