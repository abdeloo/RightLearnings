<?php

class Model_General_Legalforms extends ORM {

    protected $_table_name = 'general_legalforms';
    protected $_belongs_to = array(
    );
    protected $_has_many = array(
            'Financials_Sponsors' => array('model' => 'Financials_Sponsors', 'foreign_key' => 'legalform',),
            'Students_Applications' => array('model' => 'Students_Applications', 'foreign_key' => 'Sponsor_Legal_Form',),
    );

    public function rules() {
        return array(
            'name_ar' => array(
                array('not_empty'),
                array('max_length', array(':value', 255)),
                array(array($this, 'unique'), array('name_en', ':value')),
            ),
            'name_en' => array(
                array('not_empty'),
                array('max_length', array(':value', 255)),
                array(array($this, 'unique'), array('name_en', ':value')),
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
