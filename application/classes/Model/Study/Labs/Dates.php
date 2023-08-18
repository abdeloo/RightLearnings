<?php

class Model_Study_Labs_Dates extends ORM {

    protected $_table_name = 'study_labs_dates';
    protected $_belongs_to = array(
        'Study_Labs' => array('model' => 'Study_Labs','foreign_key' => 'lab'),
    );
    protected $_has_many = array(
    );
    public function rules() {
        return array(
           
           
        );
    }

}
