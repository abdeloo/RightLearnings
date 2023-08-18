<?php

class Model_Study_Colleges extends ORM {

    protected $_table_name = 'study_colleges';
    protected $_belongs_to = array(
        'University' => array('model' => 'Study_Universities', 'foreign_key' => 'university'),
        'City' => array('model' => 'General_Cities', 'foreign_key' => 'city'),
        'Country' => array('model' => 'General_Countries', 'foreign_key' => 'country'),
        'Category' => array('model' => 'Categories', 'foreign_key' => 'category_id'),
    );
    protected $_has_many = array(
        'Majors' => array('model' => 'Study_Majors', 'foreign_key' => 'college'),
        'Departments' => array('model' => 'Study_Departments', 'foreign_key' => 'college'),
        'Financials_Fees_Courses' => array('model' => 'Financials_Fees_Courses', 'foreign_key' => 'college'),
        'Employment_Applications' => array('model' => 'Hr_Employment_Applications', 'foreign_key' => 'college'),
    );

    public function rules() {
        return array(
            'name_ar' => array(
                array('not_empty'),
                array('max_length', array(':value', 255)),
            ),
            'name_en' => array(
                array('not_empty'),
                array('max_length', array(':value', 255)),
            ),
        );
    }

    /*
     * return array of errors
     * or return true if no errors
     */

    public static function CheckLoaded($model, $field, $validation, $id) {
        if (!empty($id)) {
            $R = ORM::factory($model, $id);
            if ($R->loaded()) {
                return TRUE;
            } else {
                $validation->error($field, 'Not_Loaded_In_Database');
            }
        }
    }
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
