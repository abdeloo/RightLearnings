<?php

class Model_Users_Files extends ORM {

    protected $_table_name = 'users_files';
    protected $_belongs_to = array(
        'User' => array('model' => 'User', 'foreign_key' => 'Created_by'),
    );
    protected $_has_many = array(
    );
    protected $_has_one = array(
    );

    public function rules() {
        return array(
            'file_path' => array(
                array('not_empty'),
                array('max_length', array(':value', 255)),
            ),
            'date_to_delete' => array(
                array('date'),
            ),
        );
    }

}
