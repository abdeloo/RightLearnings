<?php

class Model_Hr_Employment_Applications_Approvals extends ORM {

    protected $_table_name = 'hr_employment_applications_approvals';
    protected $_belongs_to = array(
        'Department' => array('model' => 'General_Hr_Departments', 'foreign_key' => 'department'),
        'Application' => array('model' => 'Hr_Employment_Applications', 'foreign_key' => 'app_id'),
        'Replied_By' => array('model' => 'User', 'foreign_key' => 'replied_by'),
    );
    protected $_has_many = array(
    );
    protected $_has_one = array(
        'Exam' => array('model' => 'Trainers_Applications_Approvals_Exams', 'foreign_key' => 'approval_id'),
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
            'order' => array(
                array('numeric'),
            ),
            'app_id' => array(
                array('not_empty'),
            ),
            'department' => array(
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
