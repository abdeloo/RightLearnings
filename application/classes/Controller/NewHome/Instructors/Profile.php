<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_NewHome_Instructors_Profile extends Controller_Template_Theme
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


    public function action_Page() {
        $teacherId = $_GET["id"] ;
        $TeacherProfile = ORM::factory('User',$teacherId);
        $Application = $TeacherProfile->Employee_Information ;
        if(isset($Application) && ($Application->img_path != NULL)){
            $img_path = $Application->img_path;
        }else{
            $img_path = 'assets/site/images/default-user.png';
        }
        $Sections = array(0);
        $Teacher_Sections = ORM::factory('Study_Sections')->where('term','=',$this->Current_Term->id)->where('status','=',1)->where('teacher','=',$teacherId)->where('is_deleted','=',NULL)->find_all();
        foreach($Teacher_Sections as $Teacher_Section){
            array_push($Sections,$Teacher_Section->id);
        }
        $Total_Students = ORM::factory('Students_Sections')->where('section','IN',$Sections)->where('is_deleted','=',NULL)->where('state','=',3)->count_all();


        //first calc average count
        $Limited_Sections = ORM::factory('Study_Sections')->where('term','=',$this->Current_Term->id)->where('status','=',1)->where('teacher','=',$teacherId)->where('is_deleted','=',NULL);
        $Limited_Sections->reset(FALSE); // useful to keep the existing query conditions for another query
        //you can change items_per_page
        $num = 6;
        //you can configure routes and custom routes params
        $pagination = Pagination::factory(array(
                    'total_items' => $Limited_Sections->count_all(),
                    'items_per_page' => $num,
                        //'current_page'   => Request::current()->param("page"),
                        )
                )->route_params(array(
            'directory' => Request::current()->directory(),
            'controller' => Request::current()->controller(),
            'action' => Request::current()->action(),
            "par1" => NULL,
            "par2" => NULL,
                )
        );
        //now select from your DB using calculated offset
        $Limited_Sections = $Limited_Sections->order_by("id", "DESC")
                ->limit($pagination->items_per_page)
                ->offset($pagination->offset)
                ->find_all();


        $this->template->layout = new View('new_theme/instructors/profile');
        $this->template->layout->lang = $this->lang;
        $this->template->layout->Teacher = $TeacherProfile ; 
        $this->template->layout->Application = $Application;
        $this->template->layout->img_path = $img_path ; 
        $this->template->layout->Teacher_Sections = $Teacher_Sections;
        $this->template->layout->Total_Students = $Total_Students;
        $this->template->layout->TeacherSections = $Limited_Sections;
        $this->template->layout->pagination = $pagination;



    }




}