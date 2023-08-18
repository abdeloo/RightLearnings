<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_NewHome_Login extends Controller_Template_Theme
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
        $this->template->layout = new View('new_theme/login/index');
        $this->template->layout->lang = $this->lang;
    }

    public function action_ProcessLogin() {
        $result = array(); //مصفوفة نتائج الجسون
        try {
            if (HTTP_Request::POST == $this->request->method()) {
                $Us = ORM::factory('User')->where('username', '=', $this->request->post('username'))->find();
                if ($Us->loaded() && $Us->IsTeacher() == TRUE && ($Us->Employee_Information->renewal_date <= date("Y-m-d"))) {
                    $result['Errors'] = Lang::__('please_renew_your_subscription');
                }else{
                    // Attempt to login user
                    $remember = array_key_exists('remember', $this->request->post()) ? (bool) $this->request->post('remember') : FALSE;
                    $user = Auth::instance()->force_login($this->request->post('username'));
                    
                    // If successful, redirect user
                    if ($user) {
                        $result['Success'] = 'redirect';
                        $manuals = "";
                        $user = Auth::instance()->get_user();
                        $user_data = ORM::factory('User',$user);
                        if($user_data->user_groub == 3){
                            $Current_Term = ORM::factory('Variables', 78)->value;
                            $Student_Sections = ORM::factory('Students_Sections')->where('term','=',$Current_Term)->where('student','=',$user_data->id)->where('is_deleted','=',NULL)->where('state','=',3)->find();
                            if(!$Student_Sections->loaded()){
                                $result['redirect_url'] = URL::base() . 'NewHome';   
                            }else{
                                $result['redirect_url'] = URL::base().'NewHome';   
                            }
                            if($user_data->display_user_manual == 1){
                                $student_manuals = ORM::factory('Variables',119)->value_ar;
                                if(!empty($student_manuals)){
                                    $attachments = explode(',', $student_manuals);
                                    foreach($attachments as $attachment){
                                        if(!empty($attachment)){
                                            $manuals .= URL::base().$attachment . "," ;
                                        }
                                    }
                                    $result['manuals'] = $manuals; 
                                }                             
                            }
                        }
                        elseif($user_data->user_groub == 8){
                            if ($user_data->show_dashboard == 1){
                                $result['redirect_url'] = URL::base().'Dashboard_Parent_Portal';
                            }else{
                                $result['redirect_url'] = URL::base().'Parent_Home';   
                            } 
                        }
                        elseif($user_data->user_groub == 1) {
                            $result['redirect_url'] = URL::base().'NewVersion';
                        }
                        elseif($user_data->IsTeacher() == TRUE) {
                            $redirect_setting = ORM::factory('Variables',118)->value;
                            if($redirect_setting == "admin_panel"){
                                $result['redirect_url'] = URL::base().'NewVersion_Teachers_Home';
                            }else if($redirect_setting == "last_section"){
                                $last_section = ORM::factory('Study_Sections')->where('teacher','=',$user_data->id)->where('status','=',1)->where('is_deleted','=',NULL)->order_by('id','DESC')->find();
                                if($last_section->loaded()){
                                    $secId = ($last_section->parent == NULL)?  $last_section->id : $last_section->parent ;
                                    $result['redirect_url'] = URL::base().'NewVersion_Sections/View/' . $secId;
                                }else{
                                    $result['redirect_url'] = URL::base().'NewVersion_Teachers_Home';
                                }
                            } 
                            if($user_data->display_user_manual == 1){
                                $teacher_manuals = ORM::factory('Variables',120)->value_ar;
                                if(!empty($teacher_manuals)){
                                    $attachments = explode(',', $teacher_manuals);
                                    foreach($attachments as $attachment){
                                        if(!empty($attachment)){
                                            $manuals .= URL::base().$attachment . "," ;
                                        }
                                    }
                                    $result['manuals'] = $manuals;  
                                }                            
                            }
                        }
                        
                        $result['Success_msg'] = Lang::__('Logged in successfully');
                    } else {
                        if ($Us->loaded()) {
                            $logrole = $Us->hasRole('roles', ORM::factory('Role', array('name' => 'login')));
                            if ($logrole) {
                                $result['Errors'] = Lang::__('Wrong_password');
                            } else {
                                $result['Errors'] = Lang::__('user_dont_have_login_permesion');
                            }
                        } else {
                            $result['Errors'] = Lang::__('Wrong_username');
                        }
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
            $result['Errors'] = $Lang_Error;
        }
        echo json_encode($result);
    }


    public function action_SendResetPasswordToken() {
        $req = Request::current(); //fillter requset
        $Filtered_array = Search::action_Filtered_array($req->post());
        $results = array();

        $User = ORM::factory('User')->where('email','=',$Filtered_array['email'])->where('is_deleted','=',NULL)->find();

        if ($User->loaded()) {
            //send email to the user
            $token = Text::random('nozero', rand(6, 6));
            $viewMessageBody = View::factory('emails/new_theme/reset_password');
            $viewMessageBody->name = $User->name_ar;
            $viewMessageBody->email = $User->email;
            $viewMessageBody->token = $token;
            $viewMessageBody->text = "انسخ هذا الكود والصقه في المتصفح لاستعادة كلمة المرور";
            $viewMessageBody->logoUrl = ORM::factory('Variables', 2)->value . ORM::factory('Variables', 1)->value;
            $title = ORM::factory('Variables', 2)->{'value_'.$this->lang};
            $viewMessageBody->title = $title;
            $Message = Lang::safehtml($viewMessageBody);
            $resp = Mailer::SendEmail(NULL, NULL, $User->email, ORM::factory('Variables',2)->value_ar, $viewMessageBody);
            //print_r($resp);
        }else{
            $results['Errors'] = array(
                'title' => Lang::__('Error'),
                'content' => Lang::__('this_email_is_not_registered')
            );
        }

        echo json_encode($results);
        
    }




    public static function LoginUser($username, $password, $remember) {
        $results = array();
        $Us = ORM::factory('User')->where('username', '=', $username)->find();                    
        $remember = $remember;
        $user = Auth::instance()->login($username, $password, $remember);
        
        // If successful, redirect user
        if ($user) {
            $user = Auth::instance()->get_user();
            $user_data = ORM::factory('User',$user);
            $results['Response'] = array(
                'status' => TRUE,
                'user_details' => $user_data,
            );
        } else {
            if ($Us->loaded()) {
                $logrole = $Us->hasRole('roles', ORM::factory('Role', array('name' => 'login')));
                if ($logrole) {
                    $error_msg = Lang::__('Wrong_password');
                } else {
                    $error_msg = Lang::__('user_dont_have_login_permesion');
                }
            } else {
                $error_msg = Lang::__('Wrong_username');
            }
            $results['Response'] = array(
                'status' => FALSE,
                'Errors' => $error_msg,
            );
        }
        
        return $results;

    }


    public function action_logout() {
        // Log user out
        Auth::instance()->logout();

        // Redirect to login page
        $this->redirect('/NewHome', 302);
    }

    public function action_SigninGoogle(){
        // Load the Google API client library
        require_once DOCROOT . 'classes/google-api-php-client--PHP5.6/vendor/autoload.php';

        //Get Google Credentials
        $google_credentials = Model_Auth_User::SetGoogleCredentials();

        // Create a new Google client object
        $client = new Google_Client();

        // Set your Google API credentials
        $client->setClientId($google_credentials["client_id"]);
        $client->setClientSecret($google_credentials["client_secret"]);
        $client->setRedirectUri(ORM::factory('Variables', 2)->value . 'NewHome_Login/google_oauth');
        // Set the Google API scopes you want to request
        $client->setScopes(array('email', 'profile'));
        // Generate a Google Sign-In URL
        $authUrl = $client->createAuthUrl();

        header("Location: " . $authUrl);
        exit;
    }


    //if not user loaded register as student
    public function action_google_oauth(){
        // Load the Google API client library
        require_once DOCROOT . 'classes/google-api-php-client--PHP5.6/vendor/autoload.php';

        try{
            //Get Google Credentials
            $google_credentials = Model_Auth_User::SetGoogleCredentials();
            
            // Create a new Google client object
            $client = new Google_Client();
            $result = array();
            // Set your Google API credentials
            $client->setClientId($google_credentials["client_id"]);
            $client->setClientSecret($google_credentials["client_secret"]);
            $client->setRedirectUri(ORM::factory('Variables', 2)->value . 'NewHome_Login/google_oauth');
            // Set the Google API scopes you want to request
            $client->setScopes(array('email', 'profile'));
            // Create a new Google Sign-In object
            $googleSignIn = new Google_Service_Oauth2($client); 
            if (isset($_GET['code'])) {
                // Exchange the authorization code for an access token
                $accessToken = $client->authenticate($_GET['code']);
                // Retrieve user information from the Google API
                $userInfo = $googleSignIn->userinfo->get();
                $email = $userInfo->getEmail();
                $g_id = $userInfo->getId();
                $name = $userInfo->getName();
                $picture = $userInfo->getPicture();


                $User = ORM::factory('User')->where('email', '=',$email)->where('is_deleted','=',NULL)->find();
                
                    if ($User->loaded() && $User->IsTeacher() == TRUE && ($User->Employee_Information->renewal_date <= date("Y-m-d"))) {
                        $result['Errors'] = Lang::__('please_renew_your_subscription');
                    }else{
                        //register student
                        $User->google_id = $g_id;
                        //print_r("ssssssssssss");
                        // If successful, redirect user
                        if ($User->loaded()) {
                            $logrole = $User->hasRole('roles', ORM::factory('Role', array('name' => 'login')));
                            if (!$logrole) {
                                //print_r("here");
                                $result['Errors'] = Lang::__('user_dont_have_login_permesion');
                            }
                            // Attempt to login user
                            $user = Auth::instance()->force_login($User->username);
                            $user = Auth::instance()->get_user();
                            $user_data = ORM::factory('User',$user);
                            //$User->google_token = $accessToken["access_token"];
                            $User->last_login = date("Y-m-d H:i:s");
                            if($User->img == NULL){
                                $User->img = $picture;
                            }
                            $User->save();
                            $this->redirect('NewHome');
                        }else {
                            $password = Text::random('nozero', rand(8, 8));
                            $Student_Application = ORM::factory('Students_Applications')->where('Email','=',$email)->where('is_deleted','=',NULL)->find();
                            if($Student_Application->loaded()){
                                $Student_Application->last_update_date = date("Y-m-d H:i:s");
                            }else{
                                //تسجيل عضوية للطالب
                                $Student_Application->Email = $email;
                                $Student_Application->Initial_Approval = 2;
                                $Student_Application->S_Type = 1;
                                $Student_Application->Full_Name_Arabic = $name;
                                $Student_Application->Full_Name_English = $name;
                                $Student_Application->password = $password;
                                $Student_Application->Created_date = date("Y-m-d H:i:s");
                            }
                            if($Student_Application->save()){
                                $username = $Student_Application->GenerateAcadimicNo();
                                $User->username = $username;
                            }
                            //print_r("ggggggggggggggg");
                            $User->email = $email;
                            $User->password = $password;
                            $User->name_ar = $name;
                            $User->name_en = $name;
                            $User->img = $picture;
                            $User->Created_date = date("Y-m-d H:i:s");
                            $User->user_groub = 3;
                            if($User->save()){
                                //print_r("yyyyyyyyyyyyyyy");
                                $User->add('roles', ORM::factory('Role', array('name' => 'login')));
                                // Attempt to login user
                                $user = Auth::instance()->force_login($User->username);
                                $user = Auth::instance()->get_user();
                                $user_data = ORM::factory('User',$user);
                                //$User->google_token = $accessToken["access_token"];
                                $User->last_login = date("Y-m-d H:i:s");
                                if($User->save()){
                                    $Student_Application->student_id = $User->id;
                                    $Student_Application->save();
                                }
                                $this->redirect('NewHome');                                
                            }
                        }
                    }



                    
                


                // // Store user data in your Kohana application's database or session
                // // ...

                // print_r($accessToken["access_token"] );
                // print_r($email);
                // print_r($userInfo->getPicture());
                // print_r($userInfo->getPicture());
                // print_r($userInfo->getName());
                // print_r($userInfo->getId());

                if(!empty($result)){
                    $this->template->layout = new View('new_theme/system_messages/show_msg');
                    $this->template->layout->class_color = 'danger';
                    $this->template->layout->msg = $result;
                }

            }
        } catch (ORM_Validation_Exception $e) {
            $errors = $e->errors('');
            $this->template->layout = new View('new_theme/system_messages/show_msg');
            $this->template->layout->class_color = 'danger';
            $this->template->layout->msg = General::ArrayToString($errors);
        }
    }


    public function action_TestGoogleApi(){
        // Load the Google API client library
        require_once DOCROOT . 'classes/google-api-php-client--PHP5.6/vendor/autoload.php';

        // Create a new Google client object
        $client = new Google_Client();

        // Set your Google API credentials
        $client->setClientId('479613276243-0g5h4lso79i7epd4hdgttr4fi4olt2h4.apps.googleusercontent.com');
        $client->setClientSecret('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');
        $client->setRedirectUri('http://localhost/right_learning/NewHome_Login/google_oauth');

        // Set the Google API scopes you want to request
        $client->setScopes(array('email', 'profile'));

        // Create a new Google Sign-In object
        $googleSignIn = new Google_Service_Oauth2($client);

        // Generate a Google Sign-In URL
        $authUrl = $client->createAuthUrl();

        // Output the Google Sign-In button
        echo '<a href="' . $authUrl . '">Sign in with Google</a>';

        // Handle the Google Sign-In response
        if (isset($_GET['code'])) {
            // Exchange the authorization code for an access token
            $accessToken = $client->authenticate($_GET['code']);

            // Retrieve user information from the Google API
            $userInfo = $googleSignIn->userinfo->get();

            // Store user data in your Kohana application's database or session
            // ...

            print_r($accessToken );
            print_r($userInfo );

        }
    }

  


   

}