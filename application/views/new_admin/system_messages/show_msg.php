<!--begin::Authentication - Signup Welcome Message -->
<div class="d-flex flex-column flex-center flex-column-fluid">
    <!--begin::Content-->
    <div class="d-flex flex-column flex-center text-center p-10">
        <!--begin::Wrapper-->
        <div class="card card-flush w-lg-650px py-5">
            <div class="card-body py-15 py-lg-20">
                <div class="d-flex align-items-center bg-light-danger rounded p-5 mb-7">
                    <div class="swal2-icon swal2-error swal2-icon-show m-10" style="display: flex;">
                        <span class="swal2-x-mark">
                            <span class="swal2-x-mark-line-left"></span>
                            <span class="swal2-x-mark-line-right"></span>
                        </span>
                    </div>
                     <!--begin::Title-->
                    <h1 class="fw-bolder fs-hx text-gray-900 mb-4"><?= $msg?>!</h1>
                    <!--end::Title-->
                </div>
                   
                <!--begin::Text-->
                <!--<div class="fw-semibold fs-6 text-gray-500 mb-7"><?//= $msg?>.</div>-->
                <!--end::Text-->
                
                <!--begin::Illustration-->
                <div class="mb-3">
                    <img src="assets/media/auth/404-error.png" class="mw-100 mh-300px theme-light-show" alt="" />
                    <img src="assets/media/auth/404-error-dark.png" class="mw-100 mh-300px theme-dark-show" alt="" />
                </div>
                <!--end::Illustration-->
                <!--begin::Link-->
                <div class="mb-0">
                    <a href="<?= URL::base()?>NewVersion" class="btn btn-sm btn-primary"><?= Lang::__('go_to_homepage')?></a>
                </div>
                <!--end::Link-->
            </div>
        </div>
        <!--end::Wrapper-->
    </div>
    <!--end::Content-->
</div>
<!--end::Authentication - Signup Welcome Message-->