<?php

class Model_Users_Notifications extends ORM {

    protected $_table_name = 'users_notifications';
    protected $_belongs_to = array(
        'User' => array('model' => 'User', 'foreign_key' => 'user'),
        'Group' => array('model' => 'Usersgroub', 'foreign_key' => 'group'),
        'Created' => array('model' => 'User', 'foreign_key' => 'Created_by'),
    );
    protected $_has_many = array(
        'Attachments' => array('model' => 'Users_Notifications_Attachments', 'foreign_key' => 'user_notification_id'),
    );

    public function rules() {
        return array(
                  
        );
    }

    /*
     * return array of errors
     * or return true if no errors
     */

    public function CheckDeleteRules() {

        $This_ORM = $this->_object; //array_of_this_orm
        $errors = array();

        if ((!$this->_loaded) || !empty($This_ORM['is_deleted'])) {
            array_push($errors, Lang::__('Not_found_the_desired_item'));
        } else {
            // foreach ($this->_has_many as $key => $value) {
            //     if ($this->$key->count_all() > 0) {
            //         array_push($errors, Lang::__('Unable_to_deletion_because_it_linked_with') . ' ' . Lang::__($key));
            //     }
            // }
        }

        return (!empty($errors)) ? $errors : TRUE;
    }

    public static function SendNotification($Created_by, $user,$title,$details,$valid_to,$url,$color,$icon) {
        $This_ORM = ORM::factory('Users_Notifications'); //array_of_this_orm
        $This_ORM->user = $user;
        $This_ORM->title = $title;
        $This_ORM->details = $details;
        $This_ORM->valid_to = $valid_to;
        $This_ORM->url = $url;
        $This_ORM->icon = $icon;
        $This_ORM->color_class = $color;
        $This_ORM->Created_by = $Created_by;
        $This_ORM->Created_date = date("Y-m-d H:i:s");
        $This_ORM->save();
    }

}
