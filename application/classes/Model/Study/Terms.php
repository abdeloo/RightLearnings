<?php

class Model_Study_Terms extends ORM {

    protected $_table_name = 'study_terms';
    protected $_belongs_to = array(
        'Academicyear' => array('model' => 'Study_Academicyears', 'foreign_key' => 'academicyear'),
    );
    protected $_has_many = array(
        'Financials_Discounts' => array('model' => 'Financials_Discounts', 'foreign_key' => 'term'),
        'Financials_Discount_Students' => array('model' => 'Financials_Discount_Students', 'foreign_key' => 'term'),
        'Financials_Finesmanagement' => array('model' => 'Financials_Finesmanagement', 'foreign_key' => 'term'),
        'Financials_Manminpayment' => array('model' => 'Financials_Manminpayment', 'foreign_key' => 'term'),
        'Financials_Manminpayment_Custom' => array('model' => 'Financials_Manminpayment_Custom', 'foreign_key' => 'term'),
        'Financials_Payments' => array('model' => 'Financials_Payments', 'foreign_key' => 'term'),
        'Financials_Sponsorships' => array('model' => 'Financials_Sponsorships', 'foreign_key' => 'term'),
        'Students_Sections' => array('model' => 'Students_Sections', 'foreign_key' => 'term'),
        'Students_Terms' => array('model' => 'Students_Terms', 'foreign_key' => 'term'),
        'Study_Sections' => array('model' => 'Study_Sections', 'foreign_key' => 'term'),
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
            'academicyear' => array(
                array('not_empty'),
            ),
            'beginning_term' => array(
                array('not_empty'),
                array('date'),
            ),
            'end_term' => array(
                array('not_empty'),
                array('date'),
            ),
            // 'reg_date_start' => array(
            //     array('not_empty'),
            //     array('date'),
            // ),
            // 'reg_date_end' => array(
            //     array('not_empty'),
            //     array('date'),
            // ),
            // 'fine_reg_date_start' => array(
            //     array('not_empty'),
            //     array('date'),
            // ),
            // 'fine_reg_date_end' => array(
            //     array('not_empty'),
            //     array('date'),
            // ),
            // 'drag_add_date_start' => array(
            //     array('not_empty'),
            //     array('date'),
            // ),
            // 'drag_add_date_end' => array(
            //     array('not_empty'),
            //     array('date'),
            // ),
            'type' => array(
                array('not_empty'),
                array(array($this, 'is_unique_per_term')),
            )
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
    /*
     * فترة الفصل الدراسي الحالية
     */
     public function StateOfTerm() {
        $CurrentTime = time();

        $reg_date_start = strtotime($this->reg_date_start);
        $reg_date_end = strtotime($this->reg_date_end);
        $fine_reg_date_start = strtotime($this->fine_reg_date_start);
        $fine_reg_date_end = strtotime($this->fine_reg_date_end);
        $drag_add_date_start = strtotime($this->drag_add_date_start);
        $drag_add_date_end = strtotime($this->drag_add_date_end);

        if ($CurrentTime >= $reg_date_start && $CurrentTime <= $reg_date_end) {
            return 'Regular registration period';
        } elseif ($CurrentTime >= $fine_reg_date_start && $CurrentTime <= $fine_reg_date_end) {
            return 'Registration period fine';
        } elseif ($CurrentTime >= $drag_add_date_start && $CurrentTime <= $drag_add_date_end) {
            return 'Drag and added period';
        } else {
            return NULL;
        }
    }

    public function is_unique_per_term($type) {
        $This_ORM = $this->_object;
        if (empty($type)) {
            return FALSE;
        } else {
            $Check_Prev = ORM::factory('Study_Terms')
            ->where('id', '!=', $This_ORM['id'])
            ->where('academicyear', '=', $This_ORM['academicyear'])
            ->where('type', '=', $This_ORM['type'])
            ->where('is_deleted', '=', NULL)
            ->find();
            if ($Check_Prev->loaded()) {
                return FALSE;
            }
            else{
                return TRUE;
            }

        }            
    }

}
