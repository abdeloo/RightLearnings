<?php

class Model_Users_Notifications_Attachments extends ORM {

    protected $_table_name = 'users_notifications_attachments';
    protected $_belongs_to = array(
        'Notification' => array('model' => 'Users_Notifications', 'foreign_key' => 'user_notification_id'),
        'Created' => array('model' => 'User', 'foreign_key' => 'Created_by'),
    );
    protected $_has_many = array(
    );

    public function rules() {
        return array(
                  
        );
    }

}
