<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_NewHome_Centers extends Controller_Template_Theme
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

        $par1 = $this->request->param("par1");

        //first calc average count
        $Centers = ORM::factory('Study_Colleges');
        $Centers->where('is_deleted', '=', NULL);

        $Centers->reset(FALSE); // useful to keep the existing query conditions for another query
        //you can change items_per_page
        $num = 3;

        //you can configure routes and custom routes params
        $pagination = Pagination::factory(array(
                    'total_items' => $Centers->count_all(),
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
        $Objs = $Centers->order_by("id", "DESC")
                ->limit($pagination->items_per_page)
                ->offset($pagination->offset)
                ->find_all();

        $this->template->layout = new View('new_theme/centers/view_all');
        $this->template->layout->lang = $this->lang;
        $this->template->layout->Centers = $Objs;
        $this->template->layout->pagination = $pagination;

        
    }

    public function action_Details() {
        $centerId = $this->request->param('par1');
        $Center = ORM::factory('Study_Colleges',$centerId);

        $Packages = ORM::factory('Study_Sections')
                                ->where('term','=',$this->Current_Term->id)
                                ->where('status','=',1)
                                ->where('is_package','=',1)
                                ->where('college','=',$centerId)
                                ->where('is_deleted','=',NULL)
                                ->order_by('start_date','DESC')
                                ->find_all();

        $Sections = ORM::factory('Study_Sections')
                                ->where('term','=',$this->Current_Term->id)
                                ->where('status','=',1)
                                ->where('is_package','=',NULL)
                                ->where('parent','=',NULL)
                                ->where('college','=',$centerId)
                                ->where('is_deleted','=',NULL)
                                ->order_by('id', 'desc')
                                ->limit(6)
                                ->find_all();

        $SortedPackages = array();
        foreach($Packages as $Package){
            $logo = (empty($Package->logo_path))? (empty($Package->College->file_path))? ORM::factory('Variables',1)->value : $Package->College->file_path : $Package->logo_path;

            $Students = $Package->Students_Sections->where('state','=',3)->where('is_deleted','=',NULL)->count_all();
            array_push($SortedPackages, array("details" => $Package, "sections" => $Package->Sections->where('is_deleted','=',NULL)->find_all(), "students_count" => $Students, "logo"=> $logo));
        }
        $counts = array_column($SortedPackages, 'students_count');
        array_multisort($counts, SORT_DESC, $SortedPackages);
        


        $this->template->layout = new View('new_theme/centers/show');
        $this->template->layout->lang = $this->lang;
        $this->template->layout->Center = $Center;
        $this->template->layout->Packages = $SortedPackages;
        $this->template->layout->Sections = $Sections;        
    }

    public function action_DetailsModal() {        
        $req = Request::current(); //fillter requset
        $Filtered_array = Search::action_Filtered_array($req->post());
        $url = !empty($Filtered_array['url']) ? mb_strtolower($Filtered_array['url']) : NULL;
        $type = !empty($Filtered_array['type']) ? mb_strtolower($Filtered_array['type']) : NULL;
        $title = Lang::__('Details');
        $view = View::factory('new_theme/centers/DetailsModal');
        $view->set('title', $title);
        $view->set('lang', $this->lang);
        $view->set('url', $url);
        $view->set('type', $type);
        $this->response->body($view);
    }


   

}