<?php

class Model_Study_Degrees extends ORM {

    protected $_table_name = 'study_degrees';
    protected $_belongs_to = array(
        //'Section' => array('model' => 'Sections','foreign_key' => 'section'),
    );
    protected $_has_many = array(
        
        'Courses' => array('model' => 'Study_Courses', 'foreign_key' => 'degree',),
    );
    public function rules() {
        return array(
            'name_ar' => array(
                array('not_empty'),
                array('max_length', array(':value', 255)),
            ),
            'name_en' => array(
                array('not_empty'),
                array('max_length', array(':value', 255)),
            ),
           
        );
    }

}
