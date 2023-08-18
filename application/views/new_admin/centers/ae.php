
<!--begin::Modal dialog-->
<div class="modal-dialog modal-dialog-centered mw-1000px">
    <!--begin::Modal content-->
    <div class="modal-content">
        <!--begin::Modal header-->
        <div class="modal-header">
            <!--begin::Modal title-->
            <h2><?= $title?></h2>
            <!--end::Modal title-->
            <!--begin::Close-->
            <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                <span class="svg-icon svg-icon-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                        <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
                    </svg>
                </span>
                <!--end::Svg Icon-->
            </div>
            <!--end::Close-->
        </div>
        <!--end::Modal header-->
        <!--begin::Modal body-->
        <div class="modal-body py-lg-10 px-lg-10">
            <!--begin::Stepper-->
            <div class="stepper stepper-pills stepper-column d-flex flex-column flex-xl-row flex-row-fluid" id="kt_create_account_stepper">
                <!--begin::Aside-->
                <div class="d-flex justify-content-center justify-content-xl-start flex-row-auto w-100 w-xl-300px">
                        <!--begin::Nav-->
                        <div class="stepper-nav ps-lg-10">
                            <!--begin::Step 1-->
                            <div class="stepper-item current" data-kt-stepper-element="nav" data-kt-stepper-action="step">
                                <!--begin::Wrapper-->
                                <div class="stepper-wrapper">
                                    <!--begin::Icon-->
                                    <div class="stepper-icon w-40px h-40px">
                                        <i class="ki-duotone ki-check stepper-check fs-2"></i>
                                        <span class="stepper-number">1</span>
                                    </div>
                                    <!--end::Icon-->
                                    <!--begin::Label-->
                                    <div class="stepper-label">
                                        <h3 class="stepper-title"><?= Lang::__('main_data')?></h3>
                                        <div class="stepper-desc"><?= Lang::__('main_data_for_the_center')?></div>
                                    </div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Wrapper-->
                                <!--begin::Line-->
                                <div class="stepper-line h-40px"></div>
                                <!--end::Line-->
                            </div>
                            <!--end::Step 1-->
                            <!--begin::Step 2-->
                            <div class="stepper-item" data-kt-stepper-element="nav" data-kt-stepper-action="step">
                                <!--begin::Wrapper-->
                                <div class="stepper-wrapper">
                                    <!--begin::Icon-->
                                    <div class="stepper-icon w-40px h-40px">
                                        <i class="ki-duotone ki-check stepper-check fs-2"></i>
                                        <span class="stepper-number">2</span>
                                    </div>
                                    <!--begin::Icon-->
                                    <!--begin::Label-->
                                    <div class="stepper-label">
                                        <h3 class="stepper-title"><?= Lang::__('Branches')?></h3>
                                        <div class="stepper-desc"><?= Lang::__('Branches')?></div>
                                    </div>
                                    <!--begin::Label-->
                                </div>
                                <!--end::Wrapper-->
                                <!--begin::Line-->
                                <div class="stepper-line h-40px"></div>
                                <!--end::Line-->
                            </div>
                            <!--end::Step 2-->
                            <!--begin::Step 3-->
                            <div class="stepper-item" data-kt-stepper-element="nav" data-kt-stepper-action="step">
                                <!--begin::Wrapper-->
                                <div class="stepper-wrapper">
                                    <!--begin::Icon-->
                                    <div class="stepper-icon w-40px h-40px">
                                        <i class="ki-duotone ki-check stepper-check fs-2"></i>
                                        <span class="stepper-number">3</span>
                                    </div>
                                    <!--end::Icon-->
                                    <!--begin::Label-->
                                    <div class="stepper-label">
                                        <h3 class="stepper-title"><?= Lang::__('Stages')?></h3>
                                        <div class="stepper-desc"><?= Lang::__('Stages')?></div>
                                    </div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Wrapper-->
                                <!--begin::Line-->
                                <div class="stepper-line h-40px"></div>
                                <!--end::Line-->
                            </div>
                            <!--end::Step 3-->
                            <!--begin::Step 4-->
                            <div class="stepper-item" data-kt-stepper-element="nav" data-kt-stepper-action="step">
                                <!--begin::Wrapper-->
                                <div class="stepper-wrapper">
                                    <!--begin::Icon-->
                                    <div class="stepper-icon w-40px h-40px">
                                        <i class="ki-duotone ki-check stepper-check fs-2"></i>
                                        <span class="stepper-number">4</span>
                                    </div>
                                    <!--end::Icon-->
                                    <!--begin::Label-->
                                    <div class="stepper-label">
                                        <h3 class="stepper-title"><?= Lang::__('Plans')?></h3>
                                        <div class="stepper-desc"><?= Lang::__('Plans')?></div>
                                    </div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Wrapper-->
                                <!--begin::Line-->
                                <div class="stepper-line h-40px"></div>
                                <!--end::Line-->    
                            </div>
                            <!--end::Step 4-->
                            <!--begin::Step 5-->
                            <div class="stepper-item" data-kt-stepper-element="nav" data-kt-stepper-action="step">
                                <!--begin::Wrapper-->
                                <div class="stepper-wrapper">
                                    <!--begin::Icon-->
                                    <div class="stepper-icon w-40px h-40px">
                                        <i class="ki-duotone ki-check stepper-check fs-2"></i>
                                        <span class="stepper-number">5</span>
                                    </div>
                                    <!--end::Icon-->
                                    <!--begin::Label-->
                                    <div class="stepper-label">
                                        <h3 class="stepper-title"><?= Lang::__('inherit_settings')?></h3>
                                        <div class="stepper-desc"><?= Lang::__('inherit_settings')?></div>
                                    </div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Wrapper-->
                                <!--begin::Line-->
                                    <div class="stepper-line h-40px"></div>
                                <!--end::Line-->
                            </div>
                            <!--end::Step 5-->
                        </div>
                        <!--end::Nav-->
                    
                </div>
                <!--begin::Aside-->
                <!--begin::Content-->
                <div class="flex-row-fluid py-lg-5 px-lg-15">
                    <!--begin::Form-->
                    <form class="form" novalidate="novalidate" id="kt_create_account_form" par1="<?php if(isset($Obj)){echo $Obj->id;}else{echo 'add';} ?>">
                        <!--begin::Step 1-->
                        <div class="current" data-kt-stepper-element="content">
                            <?php
                                $Step1_view = View::factory("new_admin/centers/steps/step1");
                                $Step1_view->set('lang', $lang);
                                $Step1_view->set('center_logo', $center_logo);
                                isset($Obj)?$Step1_view->set('Obj', $Obj):NULL;
                                echo $Step1_view;
                            ?>                            
                        </div>
                        <!--end::Step 1-->
                        <!--begin::Step 2-->
                        <div data-kt-stepper-element="content">
                            <?php
                                $Step2_view = View::factory("new_admin/centers/steps/step2");
                                $Step2_view->set('lang', $lang);
                                isset($Obj)?$Step2_view->set('Obj', $Obj):NULL;
                                echo $Step2_view;
                            ?> 
                        </div>
                        <!--end::Step 2-->
                        <!--begin::Step 3-->
                        <div data-kt-stepper-element="content">
                            <?php
                                $Step3_view = View::factory("new_admin/centers/steps/step3");
                                $Step3_view->set('lang', $lang);
                                isset($Obj)?$Step3_view->set('Obj', $Obj):NULL;
                                echo $Step3_view;
                            ?> 
                        </div>
                        <!--end::Step 3-->
                        <!--begin::Step 4-->
                        <div data-kt-stepper-element="content">
                            <?php
                                $Step4_view = View::factory("new_admin/centers/steps/step4");
                                $Step4_view->set('lang', $lang);
                                isset($Obj)?$Step4_view->set('Obj', $Obj):NULL;
                                echo $Step4_view;
                            ?>
                        </div>
                        <!--end::Step 4-->
                        <!--begin::Step 5-->
                        <div data-kt-stepper-element="content">
                            <!--begin::Wrapper-->
                            <div class="w-100">
                                <!--begin::Heading-->
                                <div class="pb-10 pb-lg-12">
                                    <!--begin::Title-->
                                    <h2 class="fw-bolder text-dark"><?= Lang::__('inherit_settings')?></h2>
                                    <!--end::Title-->
                                    <!--begin::Notice-->
                                    <div class="text-muted fw-bold fs-6"><?= Lang::__('you_can_display_how_others_see_the_center')?>
                                    <a href="#" class="link-primary fw-bolder"><?= Lang::__('click_here')?></a>.</div>
                                    <!--end::Notice-->
                                </div>
                                <!--end::Heading-->
                                <!--begin::Form group-->
                                <div class="form-group">
                                    <?php $Centers = ORM::factory('Study_Colleges')->where('is_deleted','=',NULL)->find_all();?>
                                    <div class="form-group row">
                                        <label class="required fs-6 fw-bold mb-2"><?= Lang::__("select_center_to_inherit_settings")?></label>
                                        <select class="form-select select2me form-select-solid" data-kt-repeater="select2" data-allow-clear="true" data-placeholder="<?= Lang::__('select')?>" name="from_center">
                                            <option></option>
                                            <?foreach($Centers as $Center){?>
                                                <option value="<?= $Center->id?>"><?= $Center->{'name_'.$lang}?></option>
                                            <?}?>
                                        </select>
                                    </div>
                                </div>
                                <!--end::Form group-->
                            </div>
                            <!--end::Wrapper-->
                        </div>
                        <!--end::Step 5-->

                        
                        <!--begin::Actions-->
                        <div class="d-flex flex-stack pt-10">
                            <!--begin::Wrapper-->
                            <div class="mr-2">
                                <button type="button" class="btn btn-lg btn-light-primary me-3" data-kt-stepper-action="previous">
                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr063.svg-->
                                <span class="svg-icon svg-icon-4 ms-1 me-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="black" />
                                        <path d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z" fill="black" />
                                    </svg>
                                </span>
                                <!--end::Svg Icon--><?= Lang::__('back')?></button>
                            </div>
                            <!--end::Wrapper-->
                            <!--begin::Wrapper-->
                            <div>
                                <button type="button" class="btn btn-lg btn-primary me-3" data-kt-stepper-action="submit">
                                    <span class="indicator-label"><?= Lang::__('save')?>
                                    
                                    <span class="indicator-progress"><?= Lang::__('Please wait')?>...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                                <button type="button" class="btn btn-lg btn-primary" data-kt-stepper-action="next"><?=Lang::__('Continue')?>
                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr064.svg-->
                                <span class="svg-icon svg-icon-4 me-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="6" y="11" width="13" height="2" rx="1" fill="black" />
                                        <path d="M8.56569 11.4343L12.75 7.25C13.1642 6.83579 13.1642 6.16421 12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75L5.70711 11.2929C5.31658 11.6834 5.31658 12.3166 5.70711 12.7071L11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25C13.1642 17.8358 13.1642 17.1642 12.75 16.75L8.56569 12.5657C8.25327 12.2533 8.25327 11.7467 8.56569 11.4343Z" fill="black" />
                                    </svg>
                                </span>
                                <!--end::Svg Icon--></button>
                            </div>
                            <!--end::Wrapper-->
                        </div>
                        <!--end::Actions-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Stepper-->
        </div>
        <!--end::Modal body-->
    </div>
    <!--end::Modal content-->
</div>
<!--end::Modal dialog-->
