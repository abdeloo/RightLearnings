<!--begin::Wrapper-->
<div class="w-100">
    <!--begin::Heading-->
    <div class="pb-10 pb-lg-15">
        <!--begin::Title-->
        <h2 class="fw-bolder d-flex align-items-center text-dark"><?= Lang::__('mandatory_details_for_the_center')?>
        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Billing is issued based on your selected account type"></i></h2>
        <!--end::Title-->
        <?if(isset($Obj)){?>
            <!--begin::Notice-->
            <div class="text-muted fw-bold fs-6"><?= Lang::__('you_can_display_how_others_see_the_section')?>
            <a target="_blank" href="<?= URL::base() . 'NewHome_Sections/CourseDetails/' . $Obj->id?>" class="link-primary fw-bolder"><?= Lang::__('click_her')?></a>.</div>
            <!--end::Notice-->
        <?}?>
    </div>
    <!--end::Heading-->

    <div class="card">
        <!--begin::Header-->
        <div class="card-header card-header-stretch overflow-auto">
            <!--begin::Tabs-->
            <ul class="nav nav-stretch nav-line-tabs fw-semibold fs-6 border-transparent flex-nowrap" role="tablist" id="kt_layout_builder_tabs">                
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" data-bs-toggle="tab" href="#kt_layout_builder_main" role="tab" aria-selected="true">
                        <?php echo Lang::__('Center_Details'); ?>
                    </a>
                </li>
                            
                <li class="nav-item" role="presentation">
                    <a class="nav-link " data-bs-toggle="tab" href="#kt_layout_builder_header" role="tab" aria-selected="false" tabindex="-1">
                        <?php echo Lang::__('Attachments'); ?>
                    </a>
                </li>
                                
                <li class="nav-item" role="presentation">
                    <a class="nav-link " data-bs-toggle="tab" href="#kt_layout_builder_content" role="tab" aria-selected="false" tabindex="-1">
                        <?php echo Lang::__('Center_address'); ?>
                    </a>
                </li>
                            
                <li class="nav-item" role="presentation">
                    <a class="nav-link " data-bs-toggle="tab" href="#kt_layout_builder_footer" role="tab" aria-selected="false" tabindex="-1">
                        <?php echo Lang::__('students_applications_forms'); ?>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link " data-bs-toggle="tab" href="#kt_layout_builder_footer2" role="tab" aria-selected="false" tabindex="-1">
                        <?php echo Lang::__('employee_applications_forms'); ?>
                    </a>
                </li>
            </ul>
            <!--end::Tabs-->
        </div>
        <!--end::Header-->

        <!--begin::Form-->
        <form class="form" method="POST" id="kt_layout_builder_form" action="/metronic8/demo7/index.php">
            <!--begin::Body-->
            <div class="card-body">
                <div class="tab-content pt-3">								
                    <!--begin::Tab pane-->
                    <div class="tab-pane active" id="kt_layout_builder_main" role="tabpanel">
                        <!--begin::Form group-->
                        <div class="form-group">
                            <!--begin::Heading-->
                            <div class="mb-6">
                                <!--begin::Input group-->
                                <div class="row g-9 mb-8">
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-6 fw-bold mb-2"><?= Lang::__('name_ar');?></label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" class="form-control form-control-lg form-control-solid" name="name_ar" placeholder="" value="" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-6 fw-bold mb-2"><?= Lang::__('name_en');?></label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" class="form-control form-control-lg form-control-solid" name="name_en" placeholder="" value="" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Heading-->             
                        </div>
                        <!--end::Form group-->
                        <!--begin::Form group-->
                        <div class="form-group d-flex flex-stack">           
                            <!--begin::Heading-->
                            <div class="mb-6">
                                <!--begin::Input group-->
                                <div class="row g-9 mb-8">
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-6 fw-bold mb-2"><?= Lang::__('desc_ar');?></label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <textarea class="form-control form-control-solid" rows="3" name="desc_ar" placeholder="<?= Lang::__('desc_ar')?>"><?php if (isset($Description)) { echo $Description->desc_ar; } ?></textarea>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-6 fw-bold mb-2"><?= Lang::__('desc_en');?></label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <textarea class="form-control form-control-solid" rows="3" name="desc_ar" placeholder="<?= Lang::__('desc_ar')?>"><?php if (isset($Description)) { echo $Description->desc_ar; } ?></textarea>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Heading-->
                        </div>
                        <!--end::Form group-->
                        <!--begin::Form group-->
                        <div class="form-group d-flex flex-stack">           
                            <!--begin::Heading-->
                            <div class="mb-6">
                                <!--begin::Input group-->
                                <div class="row g-9 mb-8">
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-6 fw-bold mb-2"><?= Lang::__('phone');?></label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" class="form-control form-control-lg form-control-solid" name="phone" placeholder="" value="" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-6 fw-bold mb-2"><?= Lang::__('email');?></label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" class="form-control form-control-lg form-control-solid" name="name_en" placeholder="" value="" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Heading-->
                        </div>
                        <!--end::Form group-->
                        <!--begin::Form group-->
                        <div class="form-group d-flex flex-stack">            
                            <!--begin::Heading-->     
                            <div class="mb-6">
                                <!--begin::Input group-->
                                <div class="row g-9 mb-8">
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-6 fw-bold mb-2"><?= Lang::__('Category');?></label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                            <?php $Categories = ORM::factory('Categories')->where('is_deleted', '=', NULL)->find_all(); ?>
                                            <select id="category" class="form-control form-control-lg select2me" name="category_id"  data-placeholder="<?php echo Lang::__('select'); ?>">
                                                <option value=""></option>
                                                <?php foreach ($Categories as $value) { ?>
                                                <option value="<?php echo $value->id; ?>" <?php if(isset($Obj) && ($Obj->category_id == $value->id)){ ?>selected=""<?php } ?>><?php echo $value->{'name_'.$lang}; ?></option>
                                                <?php } ?>
                                            </select>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-6 fw-bold mb-2"><?= Lang::__('Facebook_Page');?></label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" class="form-control form-control-lg form-control-solid" name="facebook_page" placeholder="" value="" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Heading-->                                 
                        </div>
                        <!--end::Form group-->

                        <!--begin::Form group-->
                        <div class="form-group d-flex flex-stack">            
                            <!--begin::Heading-->     
                            <div class="mb-6">
                                <!--begin::Input group-->
                                <div class="row g-9 mb-8">
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-6 fw-bold mb-2"><?= Lang::__('cc_email');?></label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" class="form-control form-control-lg form-control-solid" name="cc_email" placeholder="" value="" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-6 fw-bold mb-2"><?= Lang::__('display_in_home');?></label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input name="display_in_home" class="form-check-input" type="checkbox" value="1" <?php if(isset($Obj) && $Obj->display_in_home == 1){ ?>checked=""<?php } ?> />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Heading--> 
                        </div>
                        <!--end::Form group-->	
                    </div>
                    <!--end::Tab pane-->
                                    
                    <!--begin::Tab pane-->
                    <div class="tab-pane " id="kt_layout_builder_header" role="tabpanel">
                        <!--begin::Form group-->
                        <div class="form-group d-flex flex-stack">            
                            <!--begin::Heading-->     
                            <div class="mb-6">
                                <!--begin::Input group-->
                                <div class="row g-9 mb-8">
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <label class="required d-block fw-bold fs-6 mb-5"><?= Lang::__('Logo');?></label>
                                        <!--end::Label-->
                                        <!--begin::Image input-->
                                        <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('<?= $center_logo?>')">
                                            <!--begin::Preview existing avatar-->
                                            <div class="image-input-wrapper w-125px h-125px" style="background-image: url(<?= $center_logo?>);"></div>
                                            <!--end::Preview existing avatar-->
                                            <!--begin::Label-->
                                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                                                <i class="bi bi-pencil-fill fs-7"></i>
                                                <!--begin::Inputs-->
                                                <input type="file" name="logo_path" accept=".png, .jpg, .jpeg" />
                                                <input type="hidden" name="logo_path_remove" />
                                                <!--end::Inputs-->
                                            </label>
                                            <!--end::Label-->
                                            <!--begin::Cancel-->
                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
                                                <i class="bi bi-x fs-2"></i>
                                            </span>
                                            <!--end::Cancel-->
                                            <!--begin::Remove-->
                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
                                                <i class="bi bi-x fs-2"></i>
                                            </span>
                                            <!--end::Remove-->
                                        </div>
                                        <!--end::Image input-->
                                        <!--begin::Hint-->
                                        <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                                        <!--end::Hint-->                                                       
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <label class="required d-block fw-bold fs-6 mb-5"><?= Lang::__('Profile_image');?></label>
                                        <!--end::Label-->
                                        <!--begin::Image input-->
                                        <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('<?= $center_logo?>')">
                                            <!--begin::Preview existing avatar-->
                                            <div class="image-input-wrapper w-125px h-125px" style="background-image: url(<?= $center_logo?>);"></div>
                                            <!--end::Preview existing avatar-->
                                            <!--begin::Label-->
                                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                                                <i class="bi bi-pencil-fill fs-7"></i>
                                                <!--begin::Inputs-->
                                                <input type="file" name="logo_path" accept=".png, .jpg, .jpeg" />
                                                <input type="hidden" name="logo_path_remove" />
                                                <!--end::Inputs-->
                                            </label>
                                            <!--end::Label-->
                                            <!--begin::Cancel-->
                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
                                                <i class="bi bi-x fs-2"></i>
                                            </span>
                                            <!--end::Cancel-->
                                            <!--begin::Remove-->
                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
                                                <i class="bi bi-x fs-2"></i>
                                            </span>
                                            <!--end::Remove-->
                                        </div>
                                        <!--end::Image input-->
                                        <!--begin::Hint-->
                                        <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                                        <!--end::Hint-->                                                       
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Heading--> 
                        </div>
                        <!--end::Form group-->	 
                        <!--begin::Form group-->
                        <div class="form-group d-flex flex-stack">            
                            <!--begin::Heading-->     
                            <div class="mb-6">
                                <!--begin::Input group-->
                                <div class="row g-9 mb-8">
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <label class="required d-block fw-bold fs-6 mb-5"><?= Lang::__('Images');?></label>
                                        <!--end::Label-->  
                                        <div class="form-text">Allowed file types: png, jpg, jpeg.</div>                                                    
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <label class="required d-block fw-bold fs-6 mb-5"><?= Lang::__('upload_Video');?></label>
                                        <!--end::Label--> 
                                        <div class="form-text">Allowed file types: png, jpg, jpeg.</div>                                                      
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Heading--> 
                        </div>
                        <!--end::Form group-->	
                        <!--begin::Form group-->
                        <div class="form-group d-flex flex-stack">            
                            <!--begin::Heading-->     
                            <div class="mb-6">
                                <!--begin::Input group-->
                                <div class="row g-9 mb-8">
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <!--begin::Dropzone-->
                                        <div class="dropzone dropzone-queue mb-2" id="kt_modal_upload_dropzone">
                                            <!--begin::Controls-->
                                            <div class="dropzone-panel mb-lg-0 mb-2">
                                                <a class="dropzone-select btn btn-sm btn-primary me-2">Attach files</a>
                                                <a class="dropzone-upload btn btn-sm btn-light-primary me-2">Upload All</a>
                                                <a class="dropzone-remove-all btn btn-sm btn-light-primary">Remove All</a>
                                            </div>
                                            <!--end::Controls-->

                                            <!--begin::Items-->
                                            <div class="dropzone-items wm-200px">
                                                <div class="dropzone-item" style="display:none">
                                                    <!--begin::File-->
                                                    <div class="dropzone-file">
                                                        <div class="dropzone-filename" title="some_image_file_name.jpg">
                                                            <span data-dz-name>some_image_file_name.jpg</span>
                                                            <strong>(<span data-dz-size>340kb</span>)</strong>
                                                        </div>

                                                        <div class="dropzone-error" data-dz-errormessage></div>
                                                    </div>
                                                    <!--end::File-->

                                                    <!--begin::Progress-->
                                                    <div class="dropzone-progress">
                                                        <div class="progress">
                                                            <div
                                                                class="progress-bar bg-primary"
                                                                role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" data-dz-uploadprogress>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--end::Progress-->

                                                    <!--begin::Toolbar-->
                                                    <div class="dropzone-toolbar">
                                                        <span class="dropzone-start"><i class="bi bi-play-fill fs-3"></i></span>
                                                        <span class="dropzone-cancel" data-dz-remove style="display: none;"><i class="bi bi-x fs-3"></i></span>
                                                        <span class="dropzone-delete" data-dz-remove><i class="bi bi-x fs-1"></i></span>
                                                    </div>
                                                    <!--end::Toolbar-->
                                                </div>
                                            </div>
                                            <!--end::Items-->
                                        </div>
                                        <!--end::Dropzone-->
                                        <!--begin::Hint-->
                                            <span class="form-text text-muted">Max file size is 1MB and max number of files is 5.</span>
                                        <!--end::Hint-->
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <!--begin::Dropzone-->
                                        <div class="dropzone dropzone-queue mb-2" id="kt_modal_video_dropzone">
                                            <!--begin::Controls-->
                                            <div class="dropzone-panel mb-lg-0 mb-2">
                                                <a class="dropzone-select btn btn-sm btn-primary me-2">Attach files</a>
                                                <a class="dropzone-upload btn btn-sm btn-light-primary me-2">Upload All</a>
                                                <a class="dropzone-remove-all btn btn-sm btn-light-primary">Remove All</a>
                                            </div>
                                            <!--end::Controls-->

                                            <!--begin::Items-->
                                            <div class="dropzone-items wm-200px">
                                                <div class="dropzone-item" style="display:none">
                                                    <!--begin::File-->
                                                    <div class="dropzone-file">
                                                        <div class="dropzone-filename" title="some_image_file_name.jpg">
                                                            <span data-dz-name>some_image_file_name.jpg</span>
                                                            <strong>(<span data-dz-size>340kb</span>)</strong>
                                                        </div>

                                                        <div class="dropzone-error" data-dz-errormessage></div>
                                                    </div>
                                                    <!--end::File-->

                                                    <!--begin::Progress-->
                                                    <div class="dropzone-progress">
                                                        <div class="progress">
                                                            <div
                                                                class="progress-bar bg-primary"
                                                                role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" data-dz-uploadprogress>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--end::Progress-->

                                                    <!--begin::Toolbar-->
                                                    <div class="dropzone-toolbar">
                                                        <span class="dropzone-start"><i class="bi bi-play-fill fs-3"></i></span>
                                                        <span class="dropzone-cancel" data-dz-remove style="display: none;"><i class="bi bi-x fs-3"></i></span>
                                                        <span class="dropzone-delete" data-dz-remove><i class="bi bi-x fs-1"></i></span>
                                                    </div>
                                                    <!--end::Toolbar-->
                                                </div>
                                            </div>
                                            <!--end::Items-->
                                        </div>
                                        <!--end::Dropzone-->
                                        <!--begin::Hint-->
                                            <span class="form-text text-muted">Max file size is 1MB and max number of files is 5.</span>
                                        <!--end::Hint-->                                                      
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Heading--> 
                        </div>
                        <!--end::Form group-->	                                                					
                    </div>
                    <!--end::Tab pane-->
                                        
                    <!--begin::Tab pane-->
                    <div class="tab-pane " id="kt_layout_builder_content" role="tabpanel">
                        <!--begin::Form group-->
                        <div class="form-group">
                            <!--begin::Heading-->
                            <div class="mb-6">
                                <!--begin::Input group-->
                                <div class="row g-9 mb-8">
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-6 fw-bold mb-2"><?= Lang::__('address');?></label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" class="form-control form-control-lg form-control-solid" name="address" placeholder="" value="" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-6 fw-bold mb-2"><?= Lang::__('Country');?></label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" class="form-control form-control-lg form-control-solid" name="country" placeholder="" value="" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Heading-->             
                        </div>
                        <!--end::Form group-->
                        <!--begin::Form group-->
                        <div class="form-group">
                            <!--begin::Heading-->
                            <div class="mb-6">
                                <!--begin::Input group-->
                                <div class="row g-9 mb-8">
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-6 fw-bold mb-2"><?= Lang::__('lat');?></label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" class="form-control form-control-lg form-control-solid" name="lat" placeholder="" value="" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-6 fw-bold mb-2"><?= Lang::__('City');?></label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" class="form-control form-control-lg form-control-solid" name="city" placeholder="" value="" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Heading-->             
                        </div>
                        <!--end::Form group-->	
                        <!--begin::Form group-->
                        <div class="form-group">
                            <!--begin::Heading-->
                            <div class="mb-6">
                                <!--begin::Input group-->
                                <div class="row g-9 mb-8">
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-6 fw-bold mb-2"><?= Lang::__('lng');?></label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" class="form-control form-control-lg form-control-solid" name="lng" placeholder="" value="" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Heading-->             
                        </div>
                        <!--end::Form group-->			
                    </div>
                    <!--end::Tab pane-->
                                        
                    <!--begin::Tab pane-->
                    <div class="tab-pane " id="kt_layout_builder_footer" role="tabpanel">
                        <?$Students_Form_Content = ["college","major","program","plan","Full_Name_Arabic","Full_Name_English","ID_No","Mobile","Phone","Gender","Email","Address","country","city"];?>
                        <?foreach($Students_Form_Content as $Content){?>
                            <!--begin::Form group-->
                            <div class="form-group d-flex flex-stack">            
                                <!--begin::Heading-->     
                                <div class="d-flex flex-column">
                                    <h4 class="fw-bold text-dark"><?php echo Lang::__($Content); ?></h4>
                                </div>
                                <!--end::Heading-->
                                <!--begin::Option-->
                                <div class="d-flex justify-content-end gap-7">
                                    <!--begin::Check-->
                                    <div class="form-check form-check-custom form-check-success form-check-solid form-check-sm">
                                        <input class="form-check-input" type="radio" checked="" value="1" id="student_1_<?=$Content?>" name="student[<?=$Content?>]">
                                        <!--begin::Label-->
                                        <label class="form-check-label text-gray-700 fw-bold text-nowrap" for="student_1_<?=$Content?>"><?=Lang::__('required'); ?></label>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Check-->
                                    <!--begin::Check-->
                                    <div class="form-check form-check-custom form-check-success form-check-solid form-check-sm">
                                        <input class="form-check-input" type="radio" value="2" id="student_2_<?=$Content?>" name="student[<?=$Content?>]">
                                        <!--begin::Label-->
                                        <label class="form-check-label text-gray-700 fw-bold text-nowrap" for="student_2_<?=$Content?>"><?=Lang::__('optional'); ?></label>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Check-->
                                    <!--begin::Check-->
                                    <div class="form-check form-check-custom form-check-success form-check-solid form-check-sm">
                                        <input class="form-check-input" type="radio" value="3" id="student_3_<?=$Content?>" name="student[<?=$Content?>]">
                                        <!--begin::Label-->
                                        <label class="form-check-label text-gray-700 fw-bold text-nowrap" for="student_3_<?=$Content?>"><?=Lang::__('hidden'); ?></label>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Check-->
                                </div>
                                <!--end::Option-->
                            </div>
                            <!--end::Form group-->	
                            <div class="separator separator-dashed my-6"></div>	
                        <?}?>
                    </div>
                    <!--end::Tab pane-->


                    <!--begin::Tab pane-->
                    <div class="tab-pane " id="kt_layout_builder_footer2" role="tabpanel">
                        <?$Employees_Form_Title = ["college","major","program","plan","courses","Full_Name_Arabic","Full_Name_English","ID_No","Mobile","personal_photo","Gender","Email","cv","country","city"];?>
                        <?$Employees_Form_Content = ["college","majors","programs","plans","courses","name_first_ar","name_first_en","id_no","mobile","img_path","gender","email","cv_file","country","city"];?>
                        <?foreach($Students_Form_Content as $key => $Content){?>
                            <!--begin::Form group-->
                            <div class="form-group d-flex flex-stack">            
                                <!--begin::Heading-->     
                                <div class="d-flex flex-column">
                                    <h4 class="fw-bold text-dark"><?php echo Lang::__($Employees_Form_Title[$key]); ?></h4>
                                </div>
                                <!--end::Heading-->
                                <!--begin::Option-->
                                <div class="d-flex justify-content-end gap-7">
                                    <!--begin::Check-->
                                    <div class="form-check form-check-custom form-check-success form-check-solid form-check-sm">
                                        <input class="form-check-input" type="radio" checked="" value="1" id="employee_1_<?=$Content?>" name="employee[<?=$Content?>]">
                                        <!--begin::Label-->
                                        <label class="form-check-label text-gray-700 fw-bold text-nowrap" for="employee_1_<?=$Content?>"><?=Lang::__('required'); ?></label>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Check-->
                                    <!--begin::Check-->
                                    <div class="form-check form-check-custom form-check-success form-check-solid form-check-sm">
                                        <input class="form-check-input" type="radio" value="2" id="employee_2_<?=$Content?>" name="employee[<?=$Content?>]">
                                        <!--begin::Label-->
                                        <label class="form-check-label text-gray-700 fw-bold text-nowrap" for="employee_2_<?=$Content?>"><?=Lang::__('optional'); ?></label>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Check-->
                                    <!--begin::Check-->
                                    <div class="form-check form-check-custom form-check-success form-check-solid form-check-sm">
                                        <input class="form-check-input" type="radio" value="3" id="employee_3_<?=$Content?>" name="employee[<?=$Content?>]">
                                        <!--begin::Label-->
                                        <label class="form-check-label text-gray-700 fw-bold text-nowrap" for="employee_3_<?=$Content?>"><?=Lang::__('hidden'); ?></label>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Check-->
                                </div>
                                <!--end::Option-->
                            </div>
                            <!--end::Form group-->	
                            <div class="separator separator-dashed my-6"></div>	
                        <?}?>
                    </div>
                    <!--end::Tab pane-->
                </div>
            </div>
            <!--end::Body-->
        </form>
        <!--end::Form-->
    </div>    
</div>
<!--end::Wrapper-->