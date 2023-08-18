<?php

class Model_Menus extends ORM {

    protected $_table_name = 'menus';
    protected $_belongs_to = array(
        'Section' => array('model' => 'Sections','foreign_key' => 'section'),
    );
    protected $_has_many = array(
        'Childs' => array('model' => 'Menus', 'foreign_key' => 'parent',),
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
