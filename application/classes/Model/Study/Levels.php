<?php

class Model_Study_Levels extends ORM {

    protected $_table_name = 'study_levels';
    protected $_belongs_to = array(
        'Plan' => array('model' => 'Study_Plans', 'foreign_key' => 'plan'),
    );
    protected $_has_many = array(
            'Financials_Fees_Courses' => array('model' => 'Financials_Fees_Courses', 'foreign_key' => 'level'),
            'Students_Applications' => array('model' => 'Students_Applications', 'foreign_key' => 'level'),
            'Study_Levels_Courses' => array('model' => 'Study_Levels_Courses', 'foreign_key' => 'level'),
    );

    public function rules() {
        return array(
            // 'name_ar' => array(
            //     array('not_empty'),
            //     array('max_length', array(':value', 255)),
            // ),
            // 'name_en' => array(
            //     array('not_empty'),
            //     array('max_length', array(':value', 255)),
            // ),
            // 'level_order' => array(
            //     array('not_empty'),
            //     array(array($this, 'is_unique_per_plan')),
            // )
            'type' => array(
                array('not_empty'),
                array(array($this, 'is_unique_per_plan')),
            )
        );
    }

    /*
     * return array of errors
     * or return true if no errors
     */

    public function CheckDeleteRules() {
        
        $Variables1 = ORM::factory('Variables', 79)->value;
        $Variables2 = ORM::factory('Variables', 80)->value;
        $Non = array($Variables1,$Variables2);
        
        $This_ORM = $this->_object; //array_of_this_orm
        $errors = array();

        if ((!$this->_loaded) || !empty($This_ORM['is_deleted']) || in_array($This_ORM['id'], $Non)) {
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

    public function is_unique_per_plan($level_order) {
        $This_ORM = $this->_object;
        if (empty($level_order)) {
            return FALSE;
        } else {
            $Check_Prev = ORM::factory('Study_Levels')
            ->where('id', '!=', $This_ORM['id'])
            ->where('plan', '=', $This_ORM['plan'])
            ->where('type', '=', $This_ORM['type'])
            ->where('is_deleted', '=', NULL)
            ->find();
            if ($Check_Prev->loaded()) {
                return FALSE;
            }
            else{
                return TRUE;
            }

        }            
    }

}
