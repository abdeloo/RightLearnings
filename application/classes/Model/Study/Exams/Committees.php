<?php

class Model_Study_Exams_Committees extends ORM {

    protected $_table_name = 'study_exams_committees';
    protected $_belongs_to = array(
        'Term' => array('model' => 'Study_Terms', 'foreign_key' => 'term'),
        'College' => array('model' => 'Study_Colleges', 'foreign_key' => 'college'),
    );
    protected $_has_many = array(
        'Ranges' => array('model' => 'Study_Exams_Committees_Ranges', 'foreign_key' => 'committee'),
    );

    public function rules() {
        //Check _belongs_to if exist
        $Auto_arr = array();
        $belongs_to = $this->_belongs_to;
        foreach ($belongs_to as $key => $value) {
            if (!empty($value['model']) && !empty($value['foreign_key'])) {
                $Auto_arr[$value['foreign_key']] = array(
                    array('Model_Rules::CheckLoaded', array($value['model'], ':field', ':validation', ':value')),
                );
            }
        }
        //End Check _belongs_to if exist
        return array_merge_recursive($Auto_arr, array(
            'college' => array(
                array('not_empty'),
            ),
            'term' => array(
                array('not_empty'),
            ),
            'committee_no' => array(
                array('not_empty'),
            ),
            'no_of_students' => array(
                array('not_empty'),
            ),
            'place' => array(
                array('not_empty'),
            ),
        ));
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
