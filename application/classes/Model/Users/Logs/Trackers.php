<?php

class Model_Users_Logs_Trackers extends ORM {

    protected $_table_name = 'users_log_tracker';
    protected $_belongs_to = array(
        'User' => array('model' => 'User', 'foreign_key' => 'user'),
        'Log' => array('model' => 'Users_Logs', 'foreign_key' => 'log_id'),
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
            // foreach ($this->_has_many as $key => $value) {
            //     if ($this->$key->count_all() > 0) {
            //         array_push($errors, Lang::__('Unable_to_deletion_because_it_linked_with') . ' ' . Lang::__($key));
            //     }
            // }
        }

        return (!empty($errors)) ? $errors : TRUE;
    }

}
