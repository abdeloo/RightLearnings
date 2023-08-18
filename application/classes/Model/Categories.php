<?php

class Model_Categories extends ORM {

    protected $_table_name = 'categories';
    protected $_belongs_to = array(
        'User' => array('model' => 'User', 'foreign_key' => 'Created_by'),
        'MainCategory' => array('model' => 'Categories', 'foreign_key' => 'parent'),
    );
    protected $_has_many = array(
        'Colleges' => array('model' => 'Study_Colleges', 'foreign_key' => 'category_id'),
        'SubCategories' => array('model' => 'Categories', 'foreign_key' => 'parent'),
    );
    protected $_has_one = array(

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
