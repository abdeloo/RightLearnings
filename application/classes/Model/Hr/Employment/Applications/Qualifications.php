<?php

class Model_Hr_Employment_Applications_Qualifications extends ORM {

    protected $_table_name = 'hr_employment_applications_qualifications';
    protected $_belongs_to = array(
        'Hr_Employment_Application' => array('model' => 'Hr_Employment_Applications', 'foreign_key' => 'application_id'),
    );
    protected $_has_many = array(
            //'Documents' => array('model' => 'Students_Applications_Documents', 'foreign_key' => 'student_application_id'),
    );
    protected $_has_one = array(
    );

    public function rules() {
        return array(
            'application_id' => array(
                array('not_empty'),
                array('Model_Hr_Employment_Applications_Qualifications::CheckLoaded', array('Hr_Employment_Applications', ':field', ':validation', ':value')),
            ),
            'school_name' => array(
                array('not_empty'),
                //array('Model_Rules::arabiconly', array(':field', ':validation', ':value')),
            ),
            'major' => array(
                //array('Model_Rules::arabiconly', array(':field', ':validation', ':value')),
            ),
            'grade' => array(
                array('not_empty'),
            ),
            'graduation_year' => array(
                array('not_empty'),
                array('date'),
            ),
        );
    }

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

}
