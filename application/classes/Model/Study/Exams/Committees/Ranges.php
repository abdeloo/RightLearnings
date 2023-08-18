<?php

class Model_Study_Exams_Committees_Ranges extends ORM {

    protected $_table_name = 'study_exam_committee_ranges';
    protected $_belongs_to = array(
        'Committee' => array('model' => 'Study_Exams_Committees', 'foreign_key' => 'committee'),
        'Term' => array('model' => 'Study_Terms', 'foreign_key' => 'term'),
    );
    
    public function rules() {
        //Check _belongs_to if exist
        $Auto_arr = array();
        $belongs_to = $this->_belongs_to;
        foreach ($belongs_to as $key => $value) {
            if (!empty($value['model']) && !empty($value['foreign_key'])) {
                $Auto_arr[$value['foreign_key']] = array(
                    array('Model_Rules::CheckLoaded', array($value['model'], ':field', ':validation', ':value')),
                );
            }
        }
        //End Check _belongs_to if exist
        return array_merge_recursive($Auto_arr, array(
            'committee' => array(
                array('not_empty'),
            ),
            'from' => array(
                array('not_empty'),
                array(array($this, 'unique_range_per_term')),
            ),
            'to' => array(
                array('not_empty'),
                array(array($this, 'larger_than_from')),
                array(array($this, 'less_equal_max_students')),
            ),
        ));
    }

    public function unique_range_per_term($from) {
        $This_ORM = $this->_object;
        if (empty($from)) {
            return FALSE;
        } else {
            $from = $This_ORM['from'];
            $to = $This_ORM['to'];
            $is_repeated = FALSE;    
            $current_orm = ORM::factory('Study_Exams_Committees',$This_ORM['committee']);  
            $Check_Prevs = ORM::factory('Study_Exams_Committees')->where('term','=',$current_orm->term)->where('is_deleted','=',NULL)->find_all();
            foreach($Check_Prevs as $Check_Prev){
                $Prev_Ranges = $Check_Prev->Ranges->where('id','!=',$This_ORM['id'])->where('is_deleted','=',NULL)->find_all();
                foreach($Prev_Ranges as $Prev_Range){
                    for($j = $Prev_Range->from; $j <= $Prev_Range->to; $j++){
                        if(($from <= $j) && ($j <= $to)){
                            //print_r($j);
                            //print_r(count($Prev_Ranges));
                            $is_repeated = TRUE;
                            break;
                        }
                    }
                }
            }
            if ($is_repeated == TRUE) {
                return FALSE;
            }
            else{
                return TRUE;
            }

        }            
    }

    public function larger_than_from($to) {
        $This_ORM = $this->_object;
        if (empty($to)) {
            return FALSE;
        } else {
            if($This_ORM['to'] < $This_ORM['from'] ){
                return FALSE;
            }else{
                return TRUE;
            }
        }
    }

    public function less_equal_max_students($to) {
        $This_ORM = $this->_object;
        if (empty($to)) {
            return FALSE;
        } else {
            $total_number = 0;
            $no_of_students = 0;
            $no_of_students +=  $This_ORM['to'] -  $This_ORM['from'] + 1;

            $Committee = ORM::factory('Study_Exams_Committees',$This_ORM['committee']);
            $CommitteeRanges = $Committee->Ranges->where('id','!=',$This_ORM['id'])->where('is_deleted','=',NULL)->find_all();
            foreach($CommitteeRanges as $Prev_Range){
                $total_number += ($Prev_Range->to - $Prev_Range->from + 1);
            }
            if(($total_number + $no_of_students) > $Committee->no_of_students){
                return FALSE;
            }else{
                return TRUE;
            }
        }
    }

    /*
     * return array of errors
     * or return true if no errors
     */

    public function CheckDeleteRules() {
        $This_ORM = $this->_object; //array_of_this_orm
        $errors = array();

        if ((!$this->_loaded) || !empty($This_ORM['is_deleted'])) {
            array_push($errors, Lang::__('Not_found_the_desired_item'));
        } else {
            foreach ($this->_has_many as $key => $value) {
                if ($this->$key->count_all() > 0) {
                    array_push($errors, Lang::__('Unable_to_deletion_because_it_linked_with') . ' ' . Lang::__($key));
                }
            }
        }

        return (!empty($errors)) ? $errors : TRUE;
    }
}
?>
