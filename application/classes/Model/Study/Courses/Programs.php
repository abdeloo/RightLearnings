<?php

class Model_Study_Courses_Programs extends ORM {

    protected $_table_name = 'study_courses_programs';
    protected $_belongs_to = array(
        'College' => array('model' => 'Study_Colleges', 'foreign_key' => 'college'),    //المدرسة
        'Major' => array('model' => 'Study_Majors', 'foreign_key' => 'major'),         //الفرع
        'Program' => array('model' => 'Study_Programs', 'foreign_key' => 'program'),   //المرحلة
        'Plan' => array('model' => 'Study_Plans', 'foreign_key' => 'plan'),            //الصف
        'Course' => array('model' => 'Study_Courses', 'foreign_key' => 'course'),
        'Createdby' => array('model' => 'User', 'foreign_key' => 'Created_by'),
      );
    protected $_has_many = array(
       );

    public function rules() {
        return array(
            // 'course' => array(
            //     array('not_empty'),
            // ),
        );
        
    }

    /*
     * return array of errors
     * or return true if no errors
     */

    public function CheckDeleteRules() {
        $This_ORM = $this->_object; //array_of_this_orm
        $errors = array();


        if ( (!$this->_loaded) || !empty($This_ORM['is_deleted'])){
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
