<!--begin::Modal dialog-->
<div class="modal-dialog modal-dialog-centered mw-650px">
    <!--begin::Modal content-->
    <div class="modal-content">
        <!--begin::Modal header-->
        <div class="modal-header">
            <!--begin::Modal title-->
            <h2><?= $title?></h2>
            <!--end::Modal title-->
            <!--begin::Close-->
            <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                <i class="ki-duotone ki-cross fs-1">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </div>
            <!--end::Close-->
        </div>
        <!--end::Modal header-->
        <!--begin::Modal body-->
        <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
            <div class="d-flex align-items-center bg-light-danger rounded p-5 mb-7">
                <div class="swal2-icon swal2-error swal2-icon-show m-10" style="display: flex;">
                    <span class="swal2-x-mark">
                        <span class="swal2-x-mark-line-left"></span>
                        <span class="swal2-x-mark-line-right"></span>
                    </span>
                </div>
                <!--begin::Title-->
                <div class="flex-grow-1 me-2">
                    <a href="#" class="fw-bold text-gray-800 text-hover-primary fs-6"><?= Lang::__('Error'); ?></a>
                    <span class="text-muted fw-semibold d-block"><?= $MSG; ?></span>
                </div>
                <!--end::Title-->
            </div>
        </div>
        <!--end::Modal body-->
    </div>
    <!--end::Modal content-->
</div>
<!--end::Modal dialog-->
