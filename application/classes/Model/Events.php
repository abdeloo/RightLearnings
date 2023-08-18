<?php

class Model_Events extends ORM {

    protected $_table_name = 'events';
    protected $_belongs_to = array(
        'Createdby' => array('model' => 'User', 'foreign_key' => 'Created_by'),
        'Teacher' => array('model' => 'User', 'foreign_key' => 'Teacher'),
        'Currency' => array('model' => 'General_Currencies', 'foreign_key' => 'currency'),

    );
    protected $_has_many = array(
        'VClasses' => array('model' => 'VClassrooms_Events', 'foreign_key' => 'event'),
    );

    public function rules() {
        return array(
            'title' => array(
                array('not_empty'),
                array('max_length', array(':value', 255)),
            ),
            'description' => array(
                array('not_empty'),
            ),
            'event_date' => array(
                array('not_empty'),
            ),
            // 'img_path' => array(
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
