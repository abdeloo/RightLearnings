<?php

class Model_Study_Sections_Descriptions extends ORM {

    protected $_table_name = 'study_sections_descriptions';
    protected $_belongs_to = array(
        'Section' => array('model' => 'Study_Sections', 'foreign_key' => 'section'),
    );
    protected $_has_many = array(
            //'Documents' => array('model' => 'Students_Applications_Documents', 'foreign_key' => 'student_application_id'),
    );
    protected $_has_one = array(
    );

    public function rules() {
        return array(
            'section' => array(
                array('not_empty'),
                array('Model_Study_Sections_Descriptions::CheckLoaded', array('Study_Sections', ':field', ':validation', ':value')),
            ),
            // 'name_ar' => array(
            //     array('not_empty'),
            // ),
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
