<?php

defined('SYSPATH') or die('No direct script access.');

abstract class Controller_Template_Theme extends Controller_Template {

    // Define the template to use
    public $template = 'new_theme/layout/layout';
    public $user_online = NULL;
    public $Default_Language = NULL;
    public $user = NULL;
    public $lang = NULL;
    public $dir = NULL;
    public $par1 = NULL;
    public $UINotific8_msgs = NULL;
    public $mobile_version = NULL;

    public function __construct(Request $request, Response $response) {
        // You must call parent::__construct at some point in your function
        parent::__construct($request, $response);

        // Do whatever else you want
        // 
        // Load the user information
        $user = Auth::instance()->get_user();
        $this->Default_Language = ORM::factory('Variables', 13)->value;
        $this->par1 = mb_strtolower($this->request->param('par1'));
        try {
        // if a user is not logged in, redirect to login page
        if (!$user) {
           // $this->redirect('Login', 302);
        }else{
            $this->user_online = ORM::factory('User', $user);
            $this->user_online->last_active = date("Y-m-d H:i:s");
            $this->user_online->save();
        }
        } catch (ORM_Validation_Exception $e) {
            $errors = $e->errors('');
            $Lang_Error = array();
            foreach ($errors as $key => $value) {
                $srting = '';
                $errr = explode('*', $value);
                if (count($errr) > 0) {
                    foreach ($errr as $aaa) {
                        $srting.= Lang::__($aaa) . ' ';
                    }
                    array_push($Lang_Error, $srting);
                }
            }
            print_r($Lang_Error);
        }
        //Set Default Langauge
        $this->lang = Cookie::get('lang');
        if (empty($this->lang)) {
            Cookie::set('lang', $this->Default_Language); 
            $this->lang = $this->Default_Language;
            I18n::lang($this->Default_Language);
        }
        
        //Set UINotific8_msgs
        $this->UINotific8_msgs = Cookie::get('UINotific8_msgs');
        $this->Default_Title = ORM::factory('Variables', 2)->{'value_' . $this->lang};

        $this->logo = ORM::factory('Variables', 1)->value;
        $this->footer = ORM::factory('Variables', 136);
        $this->email = ORM::factory('Variables', 11)->value ;




        
    }

    public function before() {
        
        //clear templetate if its ajax
        if ($this->request->is_ajax())
        {
                $this->auto_render = FALSE;
        }else{
            
        
        parent::before();

        // Page Title
        $this->template->title = '';

        // Meta Tags
        $this->template->keywords = '';
        $this->template->description = '';
        $this->template->author = '';

        // Relational Links (other than stylesheets)
        $this->template->links = array();

        // Stylesheets
        $this->template->stylesheets = array();

        // Javascripts
        $this->template->javascripts = array();

        // Javascript Custom
        $this->template->js_custom = '';
        
        // Par1
        $this->template->par1 = $this->par1;
        
        $col_size = 'col-xs-';
        if(Detects::getDevice() != 'computer'){
        $this->template->mobile_version = 'yes';
        $this->mobile_version = 'yes';
        $col_size = 'col-md-';
        }
        
        
        // Default Language
        
        if(empty($this->UINotific8_msgs)){ 
            $this->template->UINotific8_msgs = NULL;
        }else{
            $this->template->UINotific8_msgs = $this->UINotific8_msgs;
        }
        
        if(empty($this->lang)){ 
            $this->template->lang = $this->Default_Language;
        }else{
            $this->template->lang = $this->lang;
        }
        
        if($this->lang == 'ar' || $this->lang == 'he'  || empty($this->lang)){
            $this->template->rtl = '-rtl';
            $this->template->dir = 'rtl';
        }else{
            $this->template->rtl = NULL;    
            $this->template->dir = 'ltr';
        }

        $this->template->logo = $this->logo;
        $this->template->footer = $this->footer;
        $this->template->email = $this->email;


        $this->template->Default_Title = $this->Default_Title;
        
        // controller name
        $this->template->controller = mb_strtolower(Request::initial()->controller());      
        
        // action name
        $this->template->action = mb_strtolower(Request::initial()->action());

        // Default layout
        $this->template->layout = new View('templates/empty');

        // No content by default
        $this->template->layout->content = '';
        

        // Layout Shortcut
        $this->layout = $this->template->layout;
        
        // user_online
        $this->template->layout->user_online = $this->user_online;       
        $this->template->user_online = $this->user_online;  
        if($this->user_online && $this->user_online->user_groub == 3){
            $Current_Term = ORM::factory('Variables', 78)->value;

            $this->Waiting_courses = ORM::factory('Students_Sections')
                                                    ->and_where_open()
                                                        ->or_where('state','!=',4)
                                                        ->or_where('state','=',NULL)
                                                    ->and_where_close()
                                                    ->where('paid','!=',1)
                                                    ->where('student','=',$this->user_online->id)
                                                    ->where('term','=', $Current_Term)
                                                    ->where('is_deleted','=',NULL)
                                                    ->find_all();
            $this->template->Waiting_courses =  $this->Waiting_courses ;
            $this->template->layout->Waiting_courses = $this->Waiting_courses;       
        }
        
        $this->template->Countries = ORM::factory('General_Countries')->where('is_active', '=', 1)->where('is_deleted', '=', NULL)->find_all();
        $this->template->Genders = ORM::factory('General_Genders')->where('is_active', '=', 1)->where('is_deleted', '=', NULL)->find_all();
        $this->template->Centers = ORM::factory('Study_Colleges')->where('is_deleted','=',NULL)->find_all();

        $this->template->ActiveCountries = ORM::factory('General_Countries')->where('id', 'IN', [1,65,169,113,236])->where('is_deleted', '=', NULL)->find_all();
        $this->template->Categories = ORM::factory('Categories')->where('is_deleted','=',NULL)->find_all();



    }
}
}

// End Controller_Template_Base