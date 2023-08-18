<?php

class Model_Study_Levels_Courses extends ORM {

    protected $_table_name = 'study_levels_courses';
    protected $_belongs_to = array(
        'Level' => array('model' => 'Study_Levels','foreign_key' => 'level'),
        'Course' => array('model' => 'Study_Courses','foreign_key' => 'course'),
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
            'level' => array(
                array('not_empty'),
            ),
           
        );
    }

}
