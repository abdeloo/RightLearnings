<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_NewHome_Students_Sections extends Controller_Template_Theme
{
    public function __construct(Request $request, Response $response)
    {
        // You must call parent::__construct at some point in your function
        parent::__construct($request, $response);
        // Do whatever else you want

        $this->Current_Term = ORM::factory('Study_Terms', ORm::factory('Variables', 78)->value);
        if ($this->user_online && $this->user_online->user_groub == 3 && $this->user_online->Student_Information->S_Type == 1) {
            $this->studentId = $this->user_online->id;
        } else {
            $this->studentId = NULL;
        }
        $this->locale = ["ar", "ar", "en"];
        $this->rating_types = ["","lesson","file","exam"];
    }



    public function action_AE_Rate() {
        $results = array();

        $req = Request::current(); //fillter requset
        $Filtered_array = Search::action_Filtered_array($req->post());
        $section_id = $Filtered_array['section_id'];
        $rate = $Filtered_array['rate'];
        $item_type = $Filtered_array['item_type'];
        $item_id = $Filtered_array['item_id'];

        $item_type = array_search($item_type, $this->rating_types);
        $StudentRate = ORM::factory('Students_Sections_Ratings')->where('student','=',$this->studentId)->where('section','=',$section_id)->where('item_type','=',$item_type)->where('item_id','=',$item_id)->find();
        if(!$StudentRate->loaded()){
            $StudentRate->section = $section_id;
            $StudentRate->item_type = $item_type;
            $StudentRate->item_id = $item_id;
            $StudentRate->student = $this->user_online->id;
            $StudentRate->Created_by = $this->user_online->id;
            $StudentRate->Created_date = date("Y-m-d H:i:s");
        }else{
            $StudentRate->last_update_by = $this->user_online->id;
            $StudentRate->last_update_date = date("Y-m-d H:i:s");
        }
        $StudentRate->rate = $rate;
        try {
            if ($StudentRate->save()) {
                $results['Success'] = array(
                    'text' => Lang::__('Saved_successfully'),
                );
            }
        } catch (ORM_Validation_Exception $e) {

            $errors = $e->errors('');
            $results['Errors'] = General::CatchErrorMSGSAjax($errors);
        }
        echo json_encode($results);
    }



    public function action_AE_Comment() {
        $results = array();

        $req = Request::current(); //fillter requset
        $Filtered_array = Search::action_Filtered_array($req->post());
        $item_type = $Filtered_array['item_type'];
        $Filtered_array['item_type'] = array_search($item_type, $this->rating_types);
        $Student_Comment = ORM::factory('Study_Sections_Comments');
        $Student_Comment->Created_by = $this->user_online->id;
        $Student_Comment->Created_date = date("Y-m-d H:i:s");
        $Student_Comment->values($Filtered_array);
        try {
            if ($Student_Comment->save()) {
                $results['Success'] = array(
                    'text' => Lang::__('Saved_successfully'),
                );
            }
        } catch (ORM_Validation_Exception $e) {

            $errors = $e->errors('');
            $results['Errors'] = General::CatchErrorMSGSAjax($errors);
        }
        echo json_encode($results);
    }

    public function action_ToggleSectionFromWishlist(){
        $results = array();
        $req = Request::current(); //fillter requset
        $Filtered_array = Search::action_Filtered_array($req->post());
        if ((!empty($this->user_online)) && ($this->user_online->user_groub == 3))  {//student
            $Student_Section = ORM::factory('Students_Sections')->where('student','=',$this->user_online->id)->where('section','=',$Filtered_array['section_id'])->where('status','=',1)->where('state','=',3)->where('is_deleted','=',NULL)->find();
            if($Student_Section->loaded()){
                $Obj = ORM::factory('Students_Sections_Wishlists')->where('student','=',$this->user_online->id)->where('section','=',$Filtered_array['section_id']);
                if(isset($Filtered_array['item_type'])){
                    $Filtered_array['item_type'] = array_search($Filtered_array['item_type'], $this->rating_types);
                    $Obj = $Obj->where('item_type','=',$Filtered_array['item_type']);
                }
                if(isset($Filtered_array['item_id'])){
                    $Obj = $Obj->where('item_id','=',$Filtered_array['item_id']);
                }
                $Obj = $Obj->find();
                if (!$Obj->loaded()) {
                    $Obj->values($Filtered_array);
                    $Obj->section = $Filtered_array['section_id'];
                    $Obj->student = $this->user_online->id;
                    $Obj->Created_date = date("Y-m-d H:i:s");
                    if ($Obj->save()) {
                        $results['Success'] = array(
                            'title' => Lang::__('Done'),
                            'content' => Lang::__('Saved_successfully'),
                            'status' => 'add',
                        );
                    }
                }else{
                    if($Obj->delete()){
                        $results['Success'] = array(
                            'title' => Lang::__('Done'),
                            'content' => Lang::__('has been deleted successfully'),
                            'status' => 'remove',
                        );
                    }
                }
            }else{
                $results['Errors'] = Lang::__('You_dont_have_permission_to_do_this_action');
            }                
        } else {
            $results['Errors'] = Lang::__('You_dont_have_permission_to_do_this_action');
        }
    
        echo json_encode($results);
    }






}