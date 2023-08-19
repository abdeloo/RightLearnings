<!--begin::Wrapper-->
<div class="w-100">
    <!--begin::Heading-->
    <div class="pb-10 pb-lg-12">
        <!--begin::Title-->
        <h2 class="fw-bolder text-dark"><?= Lang::__('Branches')?></h2>
        <!--end::Title-->
        <!--begin::Notice-->
        <div class="text-muted fw-bold fs-6"><?= Lang::__('you_can_display_how_others_see_the_center')?>
        <a href="#" class="link-primary fw-bolder"><?= Lang::__('click_here')?></a>.</div>
        <!--end::Notice-->
    </div>
    <!--end::Heading-->
    <!--begin::Repeater-->
    <div id="branch_repeater">
        <!--begin::Form group-->
        <div class="form-group repeater">
            <div data-repeater-list="Branches">
                <?php $Branches = ORM::factory('Study_Majors')->where('college','=',NULL)->find_all();
                    $Branches_view = View::factory("new_admin/centers/repeaters/items_repeater");
                    $Branches_view->set('Items', $Branches);
                    $Branches_view->set('type', "Branches");
                    $Branches_view->set('lang', $lang);
                    isset($Obj)?$Branches_view->set('Obj', $Obj):NULL;
                    echo $Branches_view;
                    ?>
                    <?php
                    foreach ($Branches as $Branch) {
                        $Branches_view = View::factory("new_admin/centers/repeaters/items_repeater");
                        $Branches_view->set('Items', $Branches);
                        $Branches_view->set('Item', $Branch);
                        $Branches_view->set('type', "Branches");
                        $Branches_view->set('lang', $lang);
                        echo $Branches_view;
                    } ?>
            </div>
       
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

</div>
<!--end::Wrapper-->