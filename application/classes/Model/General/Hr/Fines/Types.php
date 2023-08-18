<?php

class Model_General_Hr_Fines_Types extends ORM {

    protected $_table_name = 'general_hr_fines_types';
    protected $_belongs_to = array(
    );
    protected $_has_many = array(
        'Hr_Fines' => array('model' => 'Hr_Fineswarnings', 'foreign_key' => 'fine_type'),
    );
    public function rules() {
        return array(
            'id' => array(
                array('Model_General_Hr_Fines_Types::OneOfTow', array(':field', ':validation', ':value')),
            ),
            'name_ar' => array(
                array('not_empty'),
                array('max_length', array(':value', 255)),
                array(array($this, 'unique'), array('name_ar', ':value')),
            ),
            'name_en' => array(
                array('not_empty'),
                array('max_length', array(':value', 255)),
                array(array($this, 'unique'), array('name_en', ':value')),
            ),
            'amount' => array(
                array('numeric'),
                array('Model_Rules::positive_number', array(':field', ':validation', ':value')),
            ),
            'percentage' => array(
                array('numeric'),
                array('range', array(':value', 1, 100)),
            ),
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
            foreach ($this->_has_many as $key => $value) {
                if ($this->$key->count_all() > 0) {
                    array_push($errors, Lang::__('Unable_to_deletion_because_it_linked_with') . ' ' . Lang::__($key));
                }
            }
        }

        return (!empty($errors)) ? $errors : TRUE;
    }
    
    public static function OneOfTow($field, $validation, $id) {
        $data = $validation->data();
        if (empty($data['percentage']) && empty($data['amount'])) {
            $validation->error($field, 'You_must_specify_either_percentage_or_amount');
        }
        if (!empty($data['percentage']) && !empty($data['amount'])) {
            $validation->error($field, 'You_must_specify_either_percentage_or_amount_not_booth');
        }
    }

}
