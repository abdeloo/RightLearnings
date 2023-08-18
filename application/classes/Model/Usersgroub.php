<?php

class Model_Usersgroub extends ORM {

    protected $_table_name = 'users_group';

    public function rules() {
        return array(
            'name_ar' => array(
                array('not_empty'),
                array('max_length', array(':value', 255)),
                array('min_length', array(':value', 3)),
            ),
            'name_en' => array(
                
            ),
          
        );
    }

}
