<?php

class Model_Users_Logins extends ORM {

    protected $_table_name = 'user_logins';
    protected $_belongs_to = array(
        'User' => array('model' => 'User', 'foreign_key' => 'user_id'),
        'Created' => array('model' => 'User', 'foreign_key' => 'Created_by'),
    );
    protected $_has_many = array(
    );

    public function rules() {
        return array(
                  
        );
    }  

}
