<?php

defined('SYSPATH') or die('No direct script access.');
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\IsMeetingRunningParameters;



class Controller_NewHome_Events extends Controller_Template_Theme
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
        $this->template->layout = new View('new_theme/events/view_all');
        $this->template->layout->lang = $this->lang;
        $this->template->layout->Events = ORM::factory('Study_Sections')
                                        ->where('term','=',$this->Current_Term->id)
                                        ->where('status','=',1)
                                        ->where('is_package','=',1)
                                        ->where('study_sections.is_deleted','=',NULL)
                                        ->order_by('id', 'desc')
                                        ->find_all();
        
    }



public function action_Details() {
    $eventId = $this->request->param('par1');
    $Event = ORM::factory('Events',$eventId);
    $Log = ORM::factory('Users_Logs')->where('title','=',"create_new_event")->where('related_id','=',$eventId)->find();
    if(isset($this->user_online)){
        $StudentEventRate = ORM::factory('Students_Events_Ratings')->where('Created_by','=',$this->user_online)->find();
    }
    if($Event->loaded()){
        $this->template->center_logo =  ($Event->img_path != NULL)? $Event->img_path : NULL ;
        if($this->user_online){
            $user = $this->user_online->id;
            $trackVisit = ORM::factory('Users_Logs_Trackers')->where('log_id','=',$Log->id)->where('user','=',$this->user_online->id)->find();
        }else{
            $user = NULL;
            $trackVisit = ORM::factory('Users_Logs_Trackers')->where('log_id','=',$Log->id)->where('user','=',NULL)->find();
        }
        if(!$trackVisit->loaded()){
            $trackVisit->log_id = $Log->id;
            $trackVisit->user = $user;
            $trackVisit->tracked_times = 1;
            $trackVisit->Created_by = $user;
            $trackVisit->Created_date = date("Y-m-d H:i:s");
        }else{
            $trackVisit->tracked_times = $trackVisit->tracked_times + 1;
            $trackVisit->last_update_by = $user;
            $trackVisit->last_update_date = date("Y-m-d H:i:s");
        }
        $trackVisit->save();

        $this->template->layout = new View('new_theme/events/event_details');
        $this->template->layout->EventDetails = $Event;
        $this->template->layout->Log = $Log;
        $this->template->layout->studentId = (!empty($this->user_online))? $this->user_online->id : NULL;
        $this->template->layout->lang = $this->lang;
        $this->template->layout->StudentEventRate = (isset($StudentEventRate) && $StudentEventRate->loaded())? $StudentEventRate->rate : 0;
        $this->template->title = $Event->Createdby->College->{'name_'.$this->lang} . " - " . $Event->title . " - " . $Event->Createdby->{'name_'.$this->lang};
    }else{
        $this->template->layout = new View('system/show_msg');
        $this->template->layout->class_color = 'danger';
        $this->template->layout->msg = Lang::__('You_dont_have_permission_to_access_this_page');
    }
}



    public function action_DetailsModal() {        
        $req = Request::current(); //fillter requset
        $Filtered_array = Search::action_Filtered_array($req->post());
        $url = !empty($Filtered_array['par1']) ? mb_strtolower($Filtered_array['par1']) : NULL;
        $title = Lang::__('Details');
        $view = View::factory('new_theme/sections/DetailsModal');
        $view->set('title', $title);
        $view->set('lang', $this->lang);
        $view->set('url', $url);
        $this->response->body($view);
    }

   

}