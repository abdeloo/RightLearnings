<?php

defined('SYSPATH') or die('No direct script access.');
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\IsMeetingRunningParameters;



class Controller_NewVersion extends Controller_Template_NewAdmin
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

        $this->template->layout = new View('new_admin/index');


    }




 
   

}

// End Home
