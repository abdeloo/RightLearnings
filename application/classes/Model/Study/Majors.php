<?php

class Model_Study_Majors extends ORM {

    protected $_table_name = 'study_majors';
    protected $_belongs_to = array(
        'College' => array('model' => 'Study_Colleges', 'foreign_key' => 'college'),
        'Category' => array('model' => 'Categories', 'foreign_key' => 'category_id'),
    );
    protected $_has_many = array(
        'Programs' => array('model' => 'Study_Programs', 'foreign_key' => 'major'),
        
        'Financials_Fees_Courses' => array('model' => 'Financials_Fees_Courses', 'foreign_key' => 'major'),
        'Financials_Discounts' => array('model' => 'Financials_Discounts', 'foreign_key' => 'major'),
        'Financials_Manminpayment' => array('model' => 'Financials_Manminpayment', 'foreign_key' => 'major'),
        'Students_Applications' => array('model' => 'Students_Applications', 'foreign_key' => 'major'),
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
            'college' => array(
                array('not_empty'),
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

}
