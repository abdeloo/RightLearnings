<?php

class Model_Study_Courses_Prerequisite extends ORM {

    protected $_table_name = 'study_courses_prerequisite';
    protected $_belongs_to = array(
        'Course' => array('model' => 'Study_Courses','foreign_key' => 'course'),
        'Prerequisite_Course' => array('model' => 'Study_Courses','foreign_key' => 'prerequisite_course'),
    );
    protected $_has_many = array(
        //'Fields' => array('model' => 'Fields', 'foreign_key' => 'categorie',),
        //'Products' => array('model' => 'Products', 'foreign_key' => 'categorie',),
    );
    public function rules() {
        return array(
            'course' => array(
                array('not_empty'),
            ),
            'prerequisite_course' => array(
                array('not_empty'),
            ),
           
        );
    }

}
