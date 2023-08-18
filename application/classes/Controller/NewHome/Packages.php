<?php

defined('SYSPATH') or die('No direct script access.');
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\IsMeetingRunningParameters;



class Controller_NewHome_Packages extends Controller_Template_Theme
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

        $Categories = ORM::factory('Categories')->where('is_deleted','=',NULL)->find_all();
        $Tags = ORM::factory('Tags')->where('is_deleted','=',NULL)->find_all();

        //first calc average count
        $Packages = ORM::factory('Study_Sections')
                                        ->where('term','=',$this->Current_Term->id)
                                        ->where('status','=',1)
                                        ->where('is_package','=',1)
                                        ->where('study_sections.is_deleted','=',NULL)
                                        ->order_by('id', 'desc');
        $Packages->reset(FALSE); // useful to keep the existing query conditions for another query
        //you can change items_per_page
        $num = 3;
        //you can configure routes and custom routes params
        $pagination = Pagination::factory(array(
                    'total_items' => $Packages->count_all(),
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
        $Packages = $Packages->order_by("id", "DESC")
                ->limit($pagination->items_per_page)
                ->offset($pagination->offset)
                ->find_all();


        $this->template->layout = new View('new_theme/packages/index');
        $this->template->layout->lang = $this->lang;
        $this->template->layout->Categories = $Categories;
        $this->template->layout->Packages = $Packages;
        $this->template->layout->Tags = $Tags;
        $this->template->layout->pagination = $pagination;
        
    }

    public function action_details() {

        $Package_id = $this->request->param('par1');
        $Package = ORM::factory('Study_Sections', $Package_id);

        $this->template->layout = new View('new_theme/packages/show');
        $this->template->layout->lang = $this->lang;
        $this->template->layout->Package = $Package;
        $this->template->layout->LearningPoints = array_filter(explode(',',$Package->{'learning_points_'.$this->lang}));


        
    }

   

}