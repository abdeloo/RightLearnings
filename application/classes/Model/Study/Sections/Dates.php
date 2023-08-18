<?php

class Model_Study_Sections_Dates extends ORM {

    protected $_table_name = 'study_sections_dates';
    protected $_belongs_to = array(
        'Study_Sections' => array('model' => 'Study_Sections','foreign_key' => 'section'),
    );
    protected $_has_many = array(
    );
    public function rules() {
        return array(
           
           
        );
    }

}
