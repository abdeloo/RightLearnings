<?php

class Model_Study_Sectionplans extends ORM {

    protected $_table_name = 'study_section_plans';
    protected $_belongs_to = array(
        'Course' => array('model' => 'Study_Courses', 'foreign_key' => 'course'),
        'Term' => array('model' => 'Study_Terms', 'foreign_key' => 'term'),
        'Teacher' => array('model' => 'User', 'foreign_key' => 'teacher'),
        'Plan' => array('model' => 'Study_Plans', 'foreign_key' => 'plan'),
        'Department' => array('model' => 'Study_Plandepartments', 'foreign_key' => 'department'),
    );
    protected $_has_many = array(
    );

    public function rules() {
        return array(
        );
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
