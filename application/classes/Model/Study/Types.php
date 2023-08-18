<?php

class Model_Study_Types extends ORM {

    protected $_table_name = 'study_types';
    protected $_belongs_to = array(
        //'Section' => array('model' => 'Sections','foreign_key' => 'section'),
    );
    protected $_has_many = array(
        'Student_Types' => array('model' => 'Students_Types', 'foreign_key' => 'study_type',),
        //'Products' => array('model' => 'Products', 'foreign_key' => 'categorie',),
    );
    public function rules() {
        return array(
//            'Full_Name_Arabic' => array(
//                array('not_empty'),
//                array('max_length', array(':value', 255)),
//            ),
//            'Full_Name_English' => array(
//                array('not_empty'),
//                array('max_length', array(':value', 255)),
//            ),
           
        );
    }

}
