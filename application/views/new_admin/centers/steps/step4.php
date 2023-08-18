<!--begin::Wrapper-->
<div class="w-100">
    <!--begin::Heading-->
    <div class="pb-10 pb-lg-12">
        <!--begin::Title-->
        <h2 class="fw-bolder text-dark"><?= Lang::__('Plans')?></h2>
        <!--end::Title-->
        <!--begin::Notice-->
        <div class="text-muted fw-bold fs-6"><?= Lang::__('you_can_display_how_others_see_the_center')?>
        <a href="#" class="link-primary fw-bolder"><?= Lang::__('click_here')?></a>.</div>
        <!--end::Notice-->
    </div>
    <!--end::Heading-->
    <!--begin::Repeater-->
    <div id="grade_repeater">
        <!--begin::Form group-->
        <div class="form-group">
            <div data-repeater-list="Plans">
                <?php $Plans = ORM::factory('Study_Plans')->where('program','=',NULL)->find_all();
                      $Stages = ORM::factory('Study_Programs')->where('is_deleted','=',NULL)->find_all();
                      $Courses = ORM::factory('Study_Courses')->where('is_deleted','=',NULL)->find_all();
                      $Grades_view = View::factory("new_admin/centers/repeaters/items_repeater");
                      $Grades_view->set('Items', $Plans);
                      $Grades_view->set('Parents', $Stages);
                      $Grades_view->set('parent_name', "program");
                      $Grades_view->set('Courses', $Courses);
                      $Grades_view->set('type', "Grades");
                      $Grades_view->set('lang', $lang);
                      isset($Obj)?$Grades_view->set('Obj', $Obj):NULL;
                      echo $Grades_view;
                    ?>
                    <?php
                    foreach ($Plans as $Plan) {
                        $Grades_view = View::factory("new_admin/centers/repeaters/items_repeater");
                        $Grades_view->set('Items', $Plans);
                        $Grades_view->set('Item', $Plan);
                        $Grades_view->set('Parents', $Stages);
                        $Grades_view->set('parent_name', "program");
                        $Grades_view->set('Courses', $Courses);
                        $Grades_view->set('type', "Grades");
                        $Grades_view->set('lang', $lang);
                        echo $Grades_view;
                    } ?>
            </div>
        </div>
        <!--end::Form group-->

        <!--begin::Form group-->
        <div class="form-group mt-5">
            <a href="javascript:;" data-repeater-create class="btn btn-light-primary">
                <i class="la la-plus"></i><?= Lang::__('add_new')?>
            </a>
        </div>
        <!--end::Form group-->
    </div>
    <!--end::Repeater-->

</div>
<!--end::Wrapper-->