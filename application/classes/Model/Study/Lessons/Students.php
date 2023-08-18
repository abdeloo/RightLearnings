<?php

class Model_Study_Lessons_Students extends ORM {

    protected $_table_name = 'study_lesson_document_students';
    protected $_belongs_to = array(
        'Study_Document' => array('model' => 'Study_Lessons_Documents', 'foreign_key' => 'study_document_id'),
    );
    

   
    
}
