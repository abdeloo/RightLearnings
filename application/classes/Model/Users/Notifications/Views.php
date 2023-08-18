<?php

class Model_Users_Notifications_Views extends ORM {

    protected $_table_name = 'users_notifications_views';
    protected $_belongs_to = array(
        'Notification' => array('model' => 'Users_Notifications', 'foreign_key' => 'note_id'),
        'Created' => array('model' => 'User', 'foreign_key' => 'Created_by'),
    );
    protected $_has_many = array(
    );

    public function rules() {
        return array(
                  
        );
    }

}



