<?php

class Model_Pages extends ORM {

    protected $_table_name = 'pages';
    protected $_belongs_to = array(
        'User' => array('model' => 'User', 'foreign_key' => 'Created_by'),
    );
    protected $_has_many = array(

    );
    protected $_has_one = array(

    );
    public function rules() {
        return array(
            'title_ar' => array(
                array('not_empty'),
                array('max_length', array(':value', 255)),
            ),
            'title_en' => array(
                array('not_empty'),
                array('max_length', array(':value', 255)),
            ),
          
            'content_ar' => array(
                array('not_empty'),
            ),
            'content_en' => array(
                array('not_empty'),
            ),
          
          
            
        );
    }


}
