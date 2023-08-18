<script src="<?php echo URL::base(); ?>assets/new_admin/assets/plugins/custom/formrepeater/formrepeater.bundle.js"></script>
<!--begin::Wrapper-->
<div class="w-100">
    <!--begin::Heading-->
    <div class="pb-10 pb-lg-12">
        <!--begin::Title-->
        <h2 class="fw-bolder text-dark"><?= Lang::__('Stages')?></h2>
        <!--end::Title-->
        <!--begin::Notice-->
        <div class="text-muted fw-bold fs-6"><?= Lang::__('you_can_display_how_others_see_the_center')?>
        <a href="#" class="link-primary fw-bolder"><?= Lang::__('click_here')?></a>.</div>
        <!--end::Notice-->
    </div>
    <!--end::Heading-->
    <!--begin::Repeater-->
    <div id="stage_repeater">
        <!--begin::Form group-->
        <div class="repeater">
            <div data-repeater-list="Stages">
                <?php $Stages = ORM::factory('Study_Programs')->where('major','=',NULL)->find_all();
                      $Branches = ORM::factory('Study_Majors')->where('is_deleted','=',NULL)->find_all();
                      $Stages_view = View::factory("new_admin/centers/repeaters/items_repeater");
                      $Stages_view->set('Items', $Stages);
                      $Stages_view->set('Parents', $Branches);
                      $Stages_view->set('parent_name', "major");
                      $Stages_view->set('type', "Stages");
                      $Stages_view->set('lang', $lang);
                      isset($Obj)?$Stages_view->set('Obj', $Obj):NULL;
                      echo $Stages_view;
                    ?>
                    <?php
                    foreach ($Stages as $Stage) {
                        $Stages_view = View::factory("new_admin/centers/repeaters/items_repeater");
                        $Stages_view->set('Items', $Stages);
                        $Stages_view->set('Item', $Stage);
                        $Stages_view->set('Parents', $Branches);
                        $Stages_view->set('parent_name', "major");
                        $Stages_view->set('type', "Stages");
                        $Stages_view->set('lang', $lang);
                        echo $Stages_view;
                    } ?>
            </div>
            <!-- <input data-repeater-create type="button" value="Add"/> -->
            <div class="form-group mt-5">
            <a href="javascript:;" data-repeater-create class="btn btn-light-primary">
                <i class="la la-plus"></i><?= Lang::__('add_new')?>
            </a>
        </div>
        </div>
        <!--end::Form group-->
                    
        <!--begin::Form group-->
        
        <!--end::Form group-->
    </div>
    <!--end::Repeater-->
<script>

    $(document).ready(function () {
        'use strict';

        $('.repeater').repeater({
            defaultValues: {
                // 'textarea-input': 'foo',
                // 'text-input': 'bar',
                // 'select-input': 'B',
                // 'checkbox-input': ['A', 'B'],
                // 'radio-input': 'B'
            },
            show: function () {
                $(this).slideDown();
            },
            hide: function (deleteElement) {
                if(confirm('Are you sure you want to delete this element?')) {
                    $(this).slideUp(deleteElement);
                }
            },
            ready: function (setIndexes) {

            }
        });
    });
</script> 
</div>
<!--end::Wrapper-->