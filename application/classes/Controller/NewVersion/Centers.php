<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_NewVersion_Centers extends Controller_Template_NewAdmin {

    public $Browse = FALSE;
    public $Add = FALSE;
    public $Edit = FALSE;
    public $Delete = FALSE;

    public function __construct(Request $request, Response $response) {
        // You must call parent::__construct at some point in your function
        parent::__construct($request, $response);
        // Do whatever else you want
        $this->Browse = $this->user_online->hasRole('roles', ORM::factory('Role', array('name' => 'B_Colleges')));
        $this->Add = $this->user_online->hasRole('roles', ORM::factory('Role', array('name' => 'A_Colleges')));
        $this->Edit = $this->user_online->hasRole('roles', ORM::factory('Role', array('name' => 'E_Collegess')));
        $this->Delete = $this->user_online->hasRole('roles', ORM::factory('Role', array('name' => 'D_Colleges')));
        $this->Current_Term = ORM::factory('Study_Terms', ORM::factory('Variables', 78)->value);
    }

    public function action_index() {
        
        $this->template->layout = new View('new_admin/centers/index');
        $this->template->layout->title = Lang::__('Colleges');
        $this->template->layout->lang = $this->lang;
        $this->template->layout->user_online = $this->user_online;
        $this->template->layout->Schools = ORM::factory('Study_Colleges')->where('is_deleted','=',NULL)->find_all();
        $this->template->layout->Courses = ORM::factory('Study_Courses')->where('is_deleted','=',NULL)->find_all();
        $this->template->layout->Currencies = ORM::factory('General_Currencies')->where('is_deleted', '=', NULL)->find_all(); ;
        $this->template->layout->Teachers = Model_Auth_User::GetAllTeachers(); 



    }

    public function action_GetData() {
        $post = $this->request->post();
        /*
            * All coulums will shown in table
            */
        $ALL_coulums = array(
            1 => "id",
        );

        if($this->user_online->college == NULL){
            $All_ORM = ORM::factory('Study_Colleges')
                    //->where('status', '=', 1)
                    ->where('is_deleted', '=', NULL)
                    ->order_by('id', 'DESC');
        }else{
            $All_ORM = ORM::factory('Study_Colleges')
                    //->where('status', '=', 1)
                    ->where('id', '=', $this->user_online->college)
                    ->where('is_deleted', '=', NULL)
                    ->order_by('id', 'DESC');
        }

        $Succesful_msg = Lang::__('Action_successfully_has_been_completed_Well_done');
        $C_Acti_status = "OK";

        /*
            * If Action exists
            */
        if (isset($post['customActionName'])) {
            switch ($post['customActionName']) {
                case 'Delete':

                    break;


                default:
                    break;
            }
        }


        /*
            * if search exists
            */
        if (isset($post['action']) && (!is_array($post['action'])) && mb_strtolower($post['action']) == 'filter') {
            if (!empty($post['course'])) {
                $All_ORM->where('course', '=', $post['course']);
            }
            if (!empty($post['room'])) {
                $All_ORM->where('room', '=', $post['room']);
            }
            if (!empty($post['college'])) {
                $All_ORM->where('id', '=', $post['college']);
            }
            if (!empty($post['major'])) {
                $All_ORM->where('major', '=', $post['major']);
            }
            if (!empty($post['program'])) {
                $All_ORM->where('program', '=', $post['program']);
            }
            if (!empty($post['plan'])) {
                $All_ORM->where('plan', '=', $post['plan']);
            }
        }

        /*
            * Paging
            */
        $All_ORM->reset(FALSE);
        $iTotalRecords = $All_ORM->count_all();

        $iDisplayLength = intval($post['length']);
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
        $iDisplayStart = intval($post['start']);
        $sEcho = intval($post['draw']);

        $records = array();
        $records["data"] = array();

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        $All_ORM->offset($iDisplayStart);
        $All_ORM->limit($iDisplayLength);


        /*
            * Prepare JSON Array
            */
        foreach ($All_ORM->find_all() as $value) {

            $buttons = '<td class="text-end">
                            <a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                            <span class="svg-icon svg-icon-5 m-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="black" />
                                </svg>
                            </span>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">';
                                // <div class="menu-item px-3">
                                //     <a href="../../demo7/dist/apps/user-management/users/view.html" class="menu-link px-3">Edit</a>
                                // </div>
                                // <div class="menu-item px-3">
                                //     <a href="#" class="menu-link px-3" data-kt-users-table-filter="delete_row">Delete</a>
                                // </div>
                            


            $buttons.= '<div class="menu-item px-3">'. '<a link="' . ORM::factory('Variables',2)->value . 'Center/CenterDetails/' . $value->id . '" class="menu-link px-3 copy_link"><i class="fa fa-copy"></i> ' . Lang::__('copy_link') . '</a>' .'</div>';
            ($this->Edit)? $buttons.= '<div class="menu-item px-3">'. '<a href="' . URL::base() . 'NewVersion_Centers/CenterDetails/' . $value->id . '" class="menu-link px-3"><i class="fa fa-edit"></i> ' . Lang::__('Edit') . '</a>' .'</div>' : NULL;
            ($this->Delete && ($value->CheckDeleteRules() === TRUE)) ? $buttons .= '<div class="menu-item px-3">'. '<a par1="' . $value->id . '" Dtitle="' . Lang::__('Alert') . '" Dcontent="' . Lang::__('Are_you_sure_you_want_to_delete_it') . '" confirmButton="' . Lang::__('Yes') . '" cancelButton="' . Lang::__('Cancel') . '" class="menu-link px-3 Delete"> ' . Lang::__('Delete') . '</a></div>' : NULL;

         
            $status_img = ($value->display_in_home == 1)? '<i class="glyphicon glyphicon-ok"></i>' : '<i class="glyphicon glyphicon-remove"></i>';

            $records["data"][] = array(
                '<input type="checkbox" name="id[]" value="' . $value->id . '">',
                '<div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                    <a target="_blank" href="' . URL::base() . 'NewHome_Centers/Details/' . $value->id . '">
                        <div class="symbol-label">
                            <img src="' . URL::base() . $value->file_path .'" alt="'. $value->{'name_' . $this->lang}  .'" class="w-100" />
                        </div>
                    </a>
                </div>
                <div class="d-flex flex-column">
                    <a target="_blank" href="' . URL::base() . 'NewHome_Centers/Details/' . $value->id . '" class="text-gray-800 text-hover-primary mb-1">' . $value->{'name_' . $this->lang} . '</a>
                    <span>' . $value->email . '</span>
                </div>',
                $value->Country->{'name_'.$this->lang},
                $value->Category->{'name_'.$this->lang},
                $value->phone,
                ($value->display_in_home == 1)? Lang::__('yes') : Lang::__('no'),
                //$total_rating,
                //'<a par1="' . $value->id . '" Dtitle="' . Lang::__('Alert') . '" Dcontent="' . Lang::__('Are_you_sure_you_want_to_change_section_status') . '" confirmButton="' . Lang::__('Yes') . '" cancelButton="' . Lang::__('Cancel') . '" class="btn btn-sm red btn-outline Toggle"> ' . $status_img  . '</a>',
                $buttons,
            );
        }
        if (isset($post["customActionType"]) && $post["customActionType"] == "group_action") {
            $records["customActionStatus"] = $C_Acti_status; // pass custom message(useful for getting status of group actions)
            $records["customActionMessage"] = $Succesful_msg; // pass custom message(useful for getting status of group actions)
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        echo json_encode($records);
    }

    public function action_AE() {        
        $req = Request::current(); //fillter requset
        $Filtered_array = Search::action_Filtered_array($req->post());
        $par1 = !empty($Filtered_array['par1']) ? mb_strtolower($Filtered_array['par1']) : NULL;
        $view = View::factory('new_admin/centers/ae');
        $title = Lang::__('add_new');
        $Obj = ORM::factory('Study_Colleges', $par1);
        $center_logo = URL::base() .'assets/new_admin/assets/media/svg/avatars/blank.svg';
        if($Obj->loaded()){
            $view->set('Obj', $Obj);
            $title = Lang::__('edit');
        }
        $view->set('title', $title);
        $view->set('center_logo', $center_logo);
        $view->set('lang', $this->lang);
        $this->response->body($view);
    }
    





}

// End Centers
