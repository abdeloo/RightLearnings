<?php

class Model_Study_Terms_Hours extends ORM {

    protected $_table_name = 'study_terms_degree_hours';
    protected $_belongs_to = array(
        'Term' => array('model' => 'Study_Terms','foreign_key' => 'term'),
        'Degree' => array('model' => 'Study_Majors','foreign_key' => 'degree'),
    );
    protected $_has_many = array(
        //'Levels' => array('model' => 'Study_Levels', 'foreign_key' => 'degree',),
        //'Courses' => array('model' => 'Study_Courses', 'foreign_key' => 'degree',),
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
            'term' => array(
                array('not_empty'),
            ),
            'degree' => array(
                array('not_empty'),
            ),
            'min_hours' => array(
                array('not_empty'),
                array('numeric'),
            ),
            'max_hours' => array(
                array('not_empty'),
                array('numeric'),
            ),
           
        ));
    }

}
