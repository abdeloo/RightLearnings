<?php

class Model_Study_Rooms extends ORM {

    protected $_table_name = 'study_rooms';
    protected $_belongs_to = array(
        'College' => array('model' => 'Study_Colleges', 'foreign_key' => 'college',),
    );
    protected $_has_many = array(
            'Study_Sections' => array('model' => 'Study_Sections', 'foreign_key' => 'room',),
            'Study_Labs' => array('model' => 'Study_Labs', 'foreign_key' => 'room',),
    );

    public function rules() {
        return array(
            'name' => array(
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
