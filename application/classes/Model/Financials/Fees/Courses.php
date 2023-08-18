<?php

class Model_Financials_Fees_Courses extends ORM {

    protected $_table_name = 'financials_courses_fees';
    protected $_belongs_to = array(
        'Student_Type' => array('model' => 'Students_Types', 'foreign_key' => 'student_type'),
        'College' => array('model' => 'Study_Colleges', 'foreign_key' => 'college'),
        'Level' => array('model' => 'Study_Levels', 'foreign_key' => 'level'),
        'Course' => array('model' => 'Study_Courses', 'foreign_key' => 'course'),
        'Department' => array('model' => 'Study_Departments', 'foreign_key' => 'department'),
        'Major' => array('model' => 'Study_Majors', 'foreign_key' => 'major'),
        'Program' => array('model' => 'Study_Programs', 'foreign_key' => 'program'),
        'Plan' => array('model' => 'Study_Plans', 'foreign_key' => 'plan'),
    );
    protected $_has_many = array(
    );

    public function rules() {
        return array(
            'id' => array(
                array('Model_Financials_Fees_Courses::CheckRul', array('Students_Types', ':field', ':validation', ':value')),
            ),
            //'student_type' => array(
                //array('not_empty'),
                //array('Model_Financials_Fees_Courses::CheckLoaded', array('Students_Types', ':field', ':validation', ':value')),
            //),
            //'level' => array(
                //array('not_empty'),
                //array('Model_Financials_Fees_Courses::CheckLoaded', array('Study_Levels', ':field', ':validation', ':value')),
            //),
            'major' => array(
                //array('not_empty'),
                array('Model_Financials_Fees_Courses::CheckLoaded', array('Study_Majors', ':field', ':validation', ':value')),
            ),
            'program' => array(
                //array('not_empty'),
                array('Model_Financials_Fees_Courses::CheckLoaded', array('Study_Programs', ':field', ':validation', ':value')),
            ),
            'plan' => array(
                //array('not_empty'),
                array('Model_Financials_Fees_Courses::CheckLoaded', array('Study_Plans', ':field', ':validation', ':value')),
            ),
            'college' => array(
                //array('not_empty'),
                array('Model_Financials_Fees_Courses::CheckLoaded', array('Study_Colleges', ':field', ':validation', ':value')),
            ),
            'course' => array(
                //array('not_empty'),
                array('Model_Financials_Fees_Courses::CheckLoaded', array('Study_Courses', ':field', ':validation', ':value')),
            ),
            //'department' => array(
                //array('not_empty'),
                //array('Model_Financials_Fees_Courses::CheckLoaded', array('Study_Departments', ':field', ':validation', ':value')),
            //),
            //'degree' => array(
                //array('not_empty'),
                //array('Model_Financials_Fees_Courses::CheckLoaded', array('Study_Degrees', ':field', ':validation', ':value')),
            //),
            'course_price' => array(
                array('numeric'),
            ),
            'level_price' => array(
                array('numeric'),
            ),
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

    public static function CheckLoaded($model, $field, $validation, $id) {
        if (!empty($id)) {
            $R = ORM::factory($model, $id);
            if ($R->loaded()) {
                
            } else {
                $validation->error($field, 'Not_Loaded_In_Database');
            }
        }
    }

    public static function CheckRul($model, $field, $validation, $id) {

        $data = $validation->data();
        #if (empty($data['major']) && empty($data['program']) && empty($data['college']) && empty($data['plan']) && empty($data['department']) && empty($data['degree']) && empty($data['student_type']) && empty($data['level']) && empty($data['course'])) {
        if (empty($data['major']) && empty($data['program']) && empty($data['college']) && empty($data['plan']) && empty($data['course'])) {
            $validation->error($field, 'All_Fields_Empty');
        }
        if(empty($data['course_price']) && empty($data['level_price'])){
            $validation->error($field, 'course_or_level_price_must_be_defined');
        }
        if((!empty($data['plan'])) && (!empty($data['major']) || !empty($data['program']) || !empty($data['college']) || !empty($data['course']))){
            if (!empty($data['course'])) {
                $validation->error('course', 'Must_be_Undefined');
            }
            if (!empty($data['college'])) {
                $validation->error('college', 'Must_be_Undefined');
            }
            if (!empty($data['program'])) {
                $validation->error('program', 'Must_be_Undefined');
            }
            if (!empty($data['major'])) {
                $validation->error('major', 'Must_be_Undefined');
            }
        }elseif((empty($data['plan']) && !empty($data['program'])) && (!empty($data['major']) || !empty($data['college']) || !empty($data['course']))){
            if (!empty($data['course'])) {
                $validation->error('course', 'Must_be_Undefined');
            }
            if (!empty($data['college'])) {
                $validation->error('college', 'Must_be_Undefined');
            }
            if (!empty($data['major'])) {
                $validation->error('major', 'Must_be_Undefined');
            }
        }elseif((empty($data['plan']) && empty($data['program']) && !empty($data['major'])) && (!empty($data['college']) || !empty($data['course']))){
            if (!empty($data['course'])) {
                $validation->error('course', 'Must_be_Undefined');
            }
            if (!empty($data['college'])) {
                $validation->error('college', 'Must_be_Undefined');
            }
        }elseif((empty($data['plan']) && empty($data['program']) && empty($data['major']) && !empty($data['college'])) && (!empty($data['course']))){
            if (!empty($data['course'])) {
                $validation->error('course', 'Must_be_Undefined');
            }
        }elseif((empty($data['plan']) && empty($data['program']) && empty($data['major']) && empty($data['college']) && !empty($data['course'])) && (empty($data['course_price']))){
            $validation->error('course_price', 'Required');
        }
    }

}
