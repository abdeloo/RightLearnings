<?php

class Model_Dictionary extends ORM {

    protected $_table_name = 'dictionary';

    public function rules() {
        return array(
            'token' => array(
                array('not_empty'),
                array('max_length', array(':value', 255)),
                array(array($this, 'unique'), array('token', ':value')),
            ),
            'txt_ar' => array(
                array('not_empty'),
                //array('max_length', array(':value', 255)),
            ),
            'txt_en' => array(
                //array('not_empty'),
               // array('max_length', array(':value', 255)),
            ),
        );
    }

}
