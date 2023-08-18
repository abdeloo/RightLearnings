<?php

defined('SYSPATH') or die('No direct script access.');

class Common_Livemeeting_VClasses {

    public static function Todb($user_online_id, $virtual_class_site, $section, $chapter, $lesson, $details, $obj, $Filtered_array) {
       
        $results = array();

        try{
            $virtual_class = ORM::factory('VClassrooms_Classes', $details['id']);
            $virtual_class->Created_by = $user_online_id;
            $virtual_class->Created_date = date("Y-m-d H:i:s");
            $virtual_class->teacher = $user_online_id;
            $virtual_class->section = $section;
            $virtual_class->chapter = $chapter;
            $virtual_class->lesson = $lesson;
            $virtual_class->title = $details['title'];
            $virtual_class->start_time = $details['start_time'];
            $virtual_class->is_recorded = $details['create_recording'] == "True" ? 1 : 0;
            if($virtual_class_site == 1){
                if($obj != NULL){
                    $virtual_class->class_id = $obj->meetingID;
                    $virtual_class->attendee_pw = $obj->attendeePW;
                    $virtual_class->moderator_pw = $obj->moderatorPW;
                }
                $virtual_class->class_type = 1;
            }  
            $virtual_class->values($Filtered_array);
            if($virtual_class->save()){
                //بداية ربط الفصل بالشعب التابعة له
                if (empty($Filtered_array['follower_sections'])) {
                    DB::delete(ORM::factory('VClassrooms_Followers')->table_name())->where('class_id', '=', $section)->execute();
                } else {
                    $Rel_To_deleted = array();
                    $Relations = ORM::factory('VClassrooms_Followers')
                            ->where('class_id', '=', $section)
                            ->find_all();
                    foreach ($Relations as $value) {
                        $Rel_To_deleted[$value->id] = '';
                    }
                    foreach ($Filtered_array['follower_sections'] as $value) {
                        $Relation = ORM::factory('VClassrooms_Followers')
                                ->where('class_id', '=', $virtual_class->id)
                                ->where('section_id', '=', $section)
                                ->where('follower_id', '=', $value)
                                ->find();
                        if ($Relation->loaded()) {
                            unset($Rel_To_deleted[$Relation->id]);
                        } else {
                            $Relation = ORM::factory('VClassrooms_Followers');
                            $Relation->class_id = $virtual_class->id;
                            $Relation->section_id = $section;
                            $Relation->follower_id = $value;
                            $Relation->Created_by = $this->user_online->id;
                            $Relation->Created_date = date("Y-m-d H:i:s");
                            $Relation->save();
                        }
                    }
                    foreach ($Rel_To_deleted as $key => $value) {
                        ORM::factory('VClassrooms_Followers', $key)->delete();
                    }
                }
                //نهاية ربط الفصل بالشعب التابعة له  
                $results['Success'] = $virtual_class;
            }
        }catch (ORM_Validation_Exception $e) {
            $errors = $e->errors('');
            $results['Errors'] = General::CatchErrorMSGSAjax($errors);
        }
        return $results;
    }
   

}
