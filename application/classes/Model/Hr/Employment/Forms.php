<?php

class Model_Hr_Employment_Forms extends ORM {

    protected $_table_name = 'hr_employment_forms';
    protected $_belongs_to = array(
        'College' => array('model' => 'Study_Colleges', 'foreign_key' => 'college'),
    );
    

}