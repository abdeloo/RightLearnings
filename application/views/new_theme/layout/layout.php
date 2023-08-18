<?
$sitePhone = ORM::factory('Variables', 10)->value;
$useragent = $_SERVER['HTTP_USER_AGENT'];
?>
<!doctype html>
<html class="no-js" dir="<?= $dir ?>" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?= $title ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Place favicon.ico in the root directory -->
    <link rel="shortcut icon" type="image/x-icon" href="<?= URL::base() ?>assets/new_theme/assets/img/favicon.png">
    <!-- CSS here -->
    <!--    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />-->
    <link rel="stylesheet" href="<?= URL::base() ?>assets/new_theme/assets/css/select2.min.css">
    <link rel="stylesheet" href="<?= URL::base() ?>assets/new_theme/assets/css/preloader.css">
    <link rel="stylesheet" href="<?= URL::base() ?>assets/new_theme/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= URL::base() ?>assets/new_theme/assets/css/meanmenu.css">
    <!--<link rel="stylesheet" href="<? //= URL::base() ?>assets/new_theme/assets/css/animate.min.css">-->
    <link rel="stylesheet" href="<?= URL::base() ?>assets/new_theme/assets/css/animate2.min.css">
    <link rel="stylesheet" href="<?= URL::base() ?>assets/new_theme/assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="<?= URL::base() ?>assets/new_theme/assets/css/swiper-bundle.css">
    <link rel="stylesheet" href="<?= URL::base() ?>assets/new_theme/assets/css/backToTop.css">
    <link rel="stylesheet" href="<?= URL::base() ?>assets/new_theme/assets/css/magnific-popup.css">
    <link rel="stylesheet" href="<?= URL::base() ?>assets/new_theme/assets/css/huicalendar.css">
    <link rel="stylesheet" href="<?= URL::base() ?>assets/new_theme/assets/css/date_picker.css">
    <!--    <link rel="stylesheet" href="--><? //= URL::base() ?><!--assets/new_theme/assets/css/nice-select.css">-->
    <link rel="stylesheet" href="<?= URL::base() ?>assets/new_theme/assets/css/nice-select2.css">
    <link rel="stylesheet" href="<?= URL::base() ?>assets/new_theme/assets/css/fontAwesome5Pro.css">
    <!--begin::Fonts-->
    <?php if ($lang == 'ar') { ?>
        <link href="<?php echo URL::base(); ?>assets/css/NotoKufiArabic/Font_ar.css" rel="stylesheet" type="text/css"/>
    <?php } ?>
    <link rel="stylesheet" href="<?= URL::base() ?>assets/new_theme/assets/css/keen_icons.css">
    <!--end::Fonts-->
    <link rel="stylesheet" href="<?= URL::base() ?>assets/new_theme/assets/css/flaticon.css">
    <link href="<?php echo URL::base(); ?>assets/new_theme/assets/css/dataTables.bootstrap5.min.css" rel="stylesheet"
          type="text/css"/>
    <!--    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">-->
    <link rel="stylesheet" href="<?= URL::base() ?>assets/new_theme/assets/css/default.css">
    <link rel="stylesheet" href="<?= URL::base() ?>assets/new_theme/assets/css/style.css?v=1">

    <?php if ($lang == 'ar') { ?>
        <link rel="stylesheet" href="<?= URL::base() ?>assets/new_theme/assets/css/style_rtl.css?v=1">
    <?php } ?>
    <?php switch (TRUE) {
        case ($controller == 'newhome_sections' && $action == 'coursedetails'): ?>
            <link href="<?php echo URL::base(); ?>assets/site/css/student_exam.css" rel="stylesheet" type="text/css"/>
            <link href="<?php echo URL::base(); ?>assets/site/css/student_result.css" rel="stylesheet" type="text/css"/>

            <?php break;
        default:
            break;
    }
    ?>
</head>

<body>


<!--[if lte IE 9]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade
    your browser</a> to improve your experience and security.</p>
<![endif]-->

<!-- Add your site or application content here -->

<!-- pre loader area start -->
<div id="loading">
    <div id="loading-center">
        <div id="loading-center-absolute">
            <div class="loading-icon text-center d-flex flex-column align-items-center justify-content-center">
                <img width="190" height="48" src="<?= URL::base() . $logo ?>" alt="logo-imgnnnnnnnnnnn">
                <img class="loading-logo" src="<?= URL::base() ?>assets/new_theme/assets/img/logo/preloader.svg" alt="">
            </div>
        </div>
    </div>
</div>
<!-- pre loader area end -->

<? if ((!empty($user_online)) && $user_online->user_groub == 3) { //student
    $total_cart = 0;
    ?>
    <!-- cart mini area start -->
    <div class="cartmini__area">
        <div class="cartmini__wrapper">
            <div class="cartmini__title">
                <h4><?= Lang::__('shopping_cart') ?></h4>
            </div>
            <div class="cartmini__close">
                <button type="button" class="cartmini__close-btn"><i class="fal fa-times"></i></button>
            </div>
            <div class="cartmini__widget">
                <div class="cartmini__inner">
                    <ul>
                        <? foreach ($Waiting_courses as $cart) {
                            $cart_course = $cart->Section;
                            $total_cart = $total_cart + $cart_course->price;
                            $section_logo = ($cart_course->logo_path != NULL) ? $cart_course->logo_path : $cart_course->Course->img;
                            if ($section_logo == NULL) {
                                $section_logo = ($cart_course->College->file_path != NULL) ? $cart_course->College->file_path : ORM::factory('Variables', 1)->value;
                            }
                            $description = $cart_course->Descriptions->where('is_deleted', '=', NULL)->find();
                            if ($description->loaded() && $description->{'name_' . $lang} != NULL) {
                                $name = $description->{'name_' . $lang};
                            } else {
                                $name = ($cart_course->{'name_' . $lang} != NULL) ? $cart_course->{'name_' . $lang} : $cart_course->Course->{'name_' . $lang};
                            }
                            ?>
                            <li>
                                <div class="cartmini__thumb">
                                    <a href="<?= URL::base() . 'NewHome_Sections/CourseDetails/' . $cart->section ?>">
                                        <img width="200" height="260" src="<?= URL::base() . $section_logo ?>"
                                             alt="image not found">
                                    </a>
                                </div>
                                <div class="cartmini__content">
                                    <h5>
                                        <a href="<?= URL::base() . 'NewHome_Sections/CourseDetails/' . $cart->section ?>"><?= $name ?></a>
                                    </h5>
                                    <div class="product__sm-price-wrapper">
                                        <span class="product__sm-price"><?= $cart_course->price . ' ' . $cart_course->Currency->{'name_' . $lang} ?></span>
                                    </div>
                                </div>
                                <a href="<?= URL::base() . 'NewHome_Sections/CourseDetails/' . $cart->section ?>"
                                   class="cartmini__del"><i class="fal fa-times"></i></a>
                            </li>
                        <? } ?>
                    </ul>
                </div>
                <div class="cartmini__checkout">
                    <div class="cartmini__checkout-title mb-30">
                        <h4><?= Lang::__('total') ?>:</h4>
                        <span><?= $total_cart ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="body-overlay"></div>
    <!-- cart mini area end -->
<? } ?>

<!-- profile mini area start -->
<div class="profilemini__area">
    <div class="profilemini__wrapper">
        <div class="cartmini__title">
            <h4><?= Lang::__('My Profile') ?></h4>
        </div>
        <div class="cartmini__close">
            <button type="button" class="profilemini__close-btn"><i class="fal fa-times"></i></button>
        </div>
        <div class="cartmini__widget">
            <div class="cartmini__inner">
                <ul>
                    <li>
                        <a href="<?= URL::base() . 'NewHome_Students_Profile' ?>" class="nav-link active" id="home-tab" type="button" role="tab" aria-controls="home" aria-selected="true">
                            <i class="fas fa-tachometer-alt-fast"></i><?= Lang::__('Dashboard') ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?= URL::base() . 'NewHome_Live_Meetings' ?>" class="nav-link active" type="button">
                                <i class="fas fa-user"></i><?= Lang::__('live_meetings') ?>
                        </a>
                    </li>
                    <li>
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">
                                <i class="fas fa-bookmark"></i>Wishlist
                        </button>
                    </li>
                    <li>
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">
                                <i class="fas fa-sign-out-alt"></i>Logout
                        </button>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</div>
<!-- profile mini area end -->


<!-- notification mini area start -->
<div class="notificationmini__area">
    <div class="notificationmini__wrapper">
        <div class="cartmini__title">
            <h4><?= Lang::__('my_notifiction') ?></h4>
        </div>
        <div class="cartmini__close">
            <button type="button" class="notificationmini__close-btn"><i class="fal fa-times"></i></button>
        </div>
        <div class="cartmini__widget">
            <div class="cartmini__inner">


                <ul>

                    <li>
                        <div class="notificationmini__thumb">
                            <img src="<?= URL::base() . "files/s0ew4rmezihulupvirra.jpg" ?>" alt="image not found">
                        </div>
                        <div class="notificationmini__content">
                            <h5><a href="#">add new Lesson add new Lesson add new Lesson add new Lesson add new Lesson
                                    add new Lesson</a></h5>
                            <div class="product__sm-price-wrapper">
                            </div>
                        </div>
                        <div class="cartmini__del">
                            <i style="color:red" class="fa fa-bell-on fa-lg" aria-hidden="true"></i>
                        </div>
                        <div class="notificationmini__info">

                            <a href="#" class=""><i class="flaticon-wall-clock"></i><span> Now</span></a>

                            <a href="#" class=" "> <i class="fa fa-user"></i><span> Edu man</span></a>
                        </div>
                    </li>

                    <li class="unread">
                        <div class="notificationmini__thumb">
                            <img src="<?= URL::base() . "files/s0ew4rmezihulupvirra.jpg" ?>" alt="image not found">
                        </div>
                        <div class="notificationmini__content">
                            <h5><a href="#">add new Lesson</a></h5>
                            <div class="product__sm-price-wrapper">
                            </div>
                        </div>
                        <div class="notificationmini__info">

                            <a href="#" class=""><i class="flaticon-wall-clock"></i><span> 8 month</span></a>

                            <a href="#" class=" "> <i class="fa fa-user"></i><span> Edu man</span></a>
                        </div>
                    </li>
                    <li>
                        <div class="notificationmini__thumb">
                            <img src="<?= URL::base() . "files/courses/2021/08/zksfrw59uwe.jpg" ?>"
                                 alt="image not found">
                        </div>
                        <div class="notificationmini__content">
                            <h5><a href="#">add new Lesson</a></h5>
                            <div class="product__sm-price-wrapper">
                            </div>
                        </div>
                        <div class="notificationmini__info">

                            <a href="#" class=""><i class="flaticon-wall-clock"></i><span> 8 month</span></a>

                            <a href="#" class=" "> <i class="fa fa-user"></i><span> Edu man</span></a>
                        </div>
                    </li>

                </ul>
            </div>

        </div>
    </div>
</div>
<!-- notification mini area end -->


<!-- side toggle start -->
<div class="fix">
    <div class="side-info">
        <div class="side-info-content">
            <div class="offset-widget offset-logo mb-40">
                <div class="row align-items-center">
                    <div class="col-9">
                        <a href="<?= Lang::__('home') ?>">
                            <img width="190" height="48" src="<?= URL::base() . $logo ?>" alt="Logo">
                        </a>
                    </div>
                    <div class="col-3 text-end">
                        <button class="side-info-close"><i class="fal fa-times"></i></button>
                    </div>
                </div>
            </div>
            <div class="mobile-menu d-xl-none fix"></div>
            <div class="offset-widget offset_searchbar mb-30">
                <div class="menu-search position-relative ">
                    <form action="#" class="filter-search-input">
                        <input type="text" placeholder="Search keyword">
                        <button><i class="fal fa-search"></i></button>
                    </form>
                </div>
            </div>
            <div class="offset-widget offset_menu-top mb-20">
                <div class="header-menu-top-icon mb-20">
                    <a href="https://api.whatsapp.com/send?phone=<?= $sitePhone ?>&text=<?= ORM::factory('Variables', 2)->{'value_' . $lang}; ?>"
                       class="float" target="_blank"><i class="fas fa-phone"></i><?= $sitePhone ?></a>
                    <a href="#"><i class="fal fa-envelope"></i><?= $email ?></a>
                    <i class="fal fa-map-marker-alt"></i><span><?= ORM::factory('Variables', 23)->{'value_' . $lang} ?></span>
                </div>
            </div>
            <div class="offset-widget button mb-20">
                <? if (empty($user_online)) { ?>
                    <a class="edu-four-btn" id="Show_SignInModel"><?= Lang::__('Enroll now') ?></a>
                    <!--<a class="edu-four-btn" href="#" data-bs-toggle="modal" data-bs-target="#SignInModal"><? //= Lang::__('Enroll now')?></a>-->
                <? } else { ?>
                    <a class="edu-four-btn"
                       href="<?php echo URL::base() . 'NewHome_Login/logout'; ?>"><?= Lang::__('Log_out') ?></a>
                <? } ?>
            </div>
        </div>
    </div>
</div>
<div class="offcanvas-overlay"></div>
<div class="offcanvas-overlay-white"></div>
<!-- side toggle end -->

<!-- Common Header -->
<!-- header-top start-->
<div class="header-top-area ">
    <div class="container-fluid">
        <div class="header-top-inner">
            <div class="row align-items-center">
                <div class="col-xl-8 col-lg-8 col-4">
                    <div class="header-top-icon ">
                        <a class="d-none d-md-inline-block " href="tel:(555)674890556"><i
                                    class="fas fa-phone"></i><?= ORM::factory('Variables', 4)->value ?></a>
                        <a class="d-none d-md-inline-block  " href="mailto:info@example.com"><i
                                    class="fal fa-envelope"></i><?= ORM::factory('Variables', 11)->value ?></a>
                        <div class="country">
                            <span class="country-span"></span>
                            <select name="cdCountry" class=" cdCountry form-control">
                                <? foreach ($ActiveCountries as $Country) { ?>
                                    <option selected
                                            value="<?= $Country->countryCode ?>"><?= $Country->{'name_' . $lang} ?></option>
                                <? } ?>
                            </select>
                        </div>
                    </div>


                </div>
                <div class="col-xl-4 col-lg-4 col-8">
                    <div class="header-top-login d-flex f-<?= ($lang == 'ar') ? 'left' : 'right' ?>">
                        <? if (empty($user_online)) { ?>
                            <div class="header-user-login p-relative d-none d-md-inline-block">
                                <span><a class="user-btn-sign-up" href="#" data-bs-toggle="modal"
                                         data-bs-target="#SignUpModal">Login / Register</a></span>
                            </div>
                        <? } else { ?>
                            <div class="header-user-login p-relative d-none d-md-inline-block">
                                <span><a class="user-btn-sign-up"
                                         href="<?php echo URL::base() . 'NewHome_Login/logout'; ?>"><?= Lang::__('Log_out') ?></a></span>
                            </div>
                        <? } ?>
                        <div id="header_search_mobile" class="d-none d-sm-inline-block d-md-none header-search">
                            <form action="#">
                                <div class="search-icon p-relative">
                                    <input type="text" id="text-search-mobile" placeholder="<?= Lang::__('Search') ?>">
                                    <button type="submit"><i class="fas fa-search"></i></button>
                                </div>
                            </form>
                        </div>
                        <div class=" header-social p-relative ">
                                <span>
                                    <a id="icon_search_header" class="d-sm-inline-block  d-md-none"><i
                                                class="fas fa-search"></i></a>
                                    <a class="d-sm-inline-block d-md-none"
                                       href="<?= ORM::factory('Variables', 38)->value ?>"><i
                                                class="fas fa-phone"></i></a>
                                    <a class="d-none d-md-inline-block "
                                       href="<?= ORM::factory('Variables', 38)->value ?>"><i
                                                class="fab fa-facebook-f"></i></a>
                                    <a class="d-none d-md-inline-block "
                                       href="<?= ORM::factory('Variables', 39)->value ?>"><i class="fab fa-twitter"></i></a>
                                    <a class="d-none d-md-inline-block "
                                       href="<?= ORM::factory('Variables', 94)->value ?>"><i
                                                class="fab fa-snapchat"></i></a>
                                    <a class="d-none d-md-inline-block "
                                       href="<?= ORM::factory('Variables', 95)->value ?>"><i class="fab fa-youtube"></i></a>
                                </span>
                        </div>
                        <div class="header-user-language">
                                <span>
                                    <?php if ($lang == 'ar') { ?>
                                        <a class="user-btn-sign-up"
                                           href="<?php echo URL::base() . 'General/ChngLang/en'; ?>">English</a>
                                    <? } else { ?>
                                        <a class="user-btn-sign-up"
                                           href="<?php echo URL::base() . 'General/ChngLang/ar'; ?>">العربية</a>
                                    <? } ?>
                                </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- header-top end -->
<!-- Common Header -->


<?php if (!($controller == 'newhome' && $action == 'index')) { ?>
    <!-- Pages Header -->
    <?php $header_view = View::factory("new_theme/layout/header");
    $header_view->set('user_online', $user_online);
    $header_view->set('lang', $lang);
    $header_view->set('logo', $logo);
    $header_view->set('Categories', $Categories);
    if ((!empty($user_online)) && $user_online->user_groub == 3) { //student
        $header_view->set('Waiting_courses', $Waiting_courses);
    }
    echo $header_view;
    ?>
    <!-- end Pages Header -->
    <div class="modal fade" id="modal1" tabindex="-1" aria-labelledby="Modal1Label" aria-hidden="true">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal2" tabindex="-1" aria-labelledby="Modal1Label" aria-hidden="true">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
    <main>
        <?php echo $layout; ?>
    </main>
    <!-- Pages Footer -->
    <?php $footer_view = View::factory("new_theme/layout/footer");
    $footer_view->set('user_online', $user_online);
    $footer_view->set('lang', $lang);
    $footer_view->set('logo', $logo);
    $footer_view->set('footer', $footer);
    echo $footer_view;
    ?>
    <!-- end Pages Header -->
<? } else { ?>
    <?php echo $layout; ?>
<? } ?>

<!-- Pages Modals -->
<?php $footer_view = View::factory("new_theme/layout/general_modals");
$footer_view->set('user_online', $user_online);
$footer_view->set('lang', $lang);
$footer_view->set('logo', $logo);
$footer_view->set('footer', $footer);
$footer_view->set('Countries', $Countries);
$footer_view->set('Genders', $Genders);
$footer_view->set('Centers', $Centers);
echo $footer_view;
?>
<!-- end Pages Header -->


<!-- back to top start -->
<div class="progress-wrap">
    <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
        <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"/>
    </svg>
</div>
<!-- back to top end -->


<!-- JS here -->
<script>var URLBase = '<?php echo URL::base(); ?>';</script>
<script src="<?= URL::base() ?>assets/new_theme/assets/js/vendor/jquery-3.6.0.min.js"></script>
<script src="<?php echo URL::base(); ?>assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<?php if ($lang == 'ar') { ?>
    <script type="text/javascript" src="<?php echo URL::base(); ?>assets/global/plugins/jquery-validation/js/localization/messages_ar.js"></script>
<?php } ?>
<script src="<?php echo URL::base(); ?>assets/global/plugins/jquery-validation/js/additional-methods.js" type="text/javascript"></script>
<!--    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>-->
<!--    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>-->
<script src="<?= URL::base() ?>assets/new_theme/assets/js/sweetalert2.min.js"></script>
<script>
    var Lang;
    $.ajax({
        type: "POST",
        url: URLBase + 'General/jsLang',
        data: '',
        async: false,
        dataType: "json",
        success: function (data) {
            Lang = data;
        }
    });
    // swal({
    //     title: "Are you sure?",
    //     text: "Once deleted, you will not be able to recover !",
    //     icon: "warning",
    //     buttons: true,
    //     dangerMode: true,
    //     })
    //     .then((willDelete) => {
    //     if (willDelete) {
    //         swal("Poof! Your imaginary file has been deleted!", {
    //         icon: "success",
    //         });
    //     } else {
    //         swal("Your imaginary file is safe!");
    //     }
    // });
</script>
<!--    <script src="-->
<?php //echo URL::base(); ?><!--assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>-->
<!--    --><?php //if($lang == 'ar'){ ?><!--<script type="text/javascript" src="-->
<?php //echo URL::base(); ?><!--assets/global/plugins/jquery-validation/js/localization/messages_ar.js"> </script>--><?php //} ?>
<!--    <script src="-->
<?php //echo URL::base(); ?><!--assets/global/plugins/jquery-validation/js/additional-methods.js" type="text/javascript"></script>-->

<script src="<?= URL::base() ?>assets/new_theme/assets/js/vendor/waypoints.min.js"></script>
<script src="<?= URL::base() ?>assets/new_theme/assets/js/bootstrap.bundle.min.js"></script>
<script src="<?= URL::base() ?>assets/new_theme/assets/js/meanmenu.js"></script>
<script src="<?= URL::base() ?>assets/new_theme/assets/js/swiper-bundle.min.js"></script>
<script src="<?= URL::base() ?>assets/new_theme/assets/js/owl.carousel.min.js"></script>
<script src="<?= URL::base() ?>assets/new_theme/assets/js/magnific-popup.min.js"></script>
<script src="<?= URL::base() ?>assets/new_theme/assets/js/huicalendar.js"></script>
<script src="<?= URL::base() ?>assets/new_theme/assets/js/parallax.min.js"></script>
<script src="<?= URL::base() ?>assets/new_theme/assets/js/backToTop.js"></script>
<!--    <script src="--><? //= URL::base() ?><!--assets/new_theme/assets/js/nice-select.min.js"></script>-->
<script src="<?= URL::base() ?>assets/new_theme/assets/js/nice-select2.js"></script>
<script src="<?= URL::base() ?>assets/new_theme/assets/js/counterup.min.js"></script>
<script src="<?= URL::base() ?>assets/new_theme/assets/js/ajax-form.js"></script>
<script src="<?= URL::base() ?>assets/new_theme/assets/js/wow.min.js"></script>
<script src="<?= URL::base() ?>assets/new_theme/assets/js/isotope.pkgd.min.js"></script>
<script src="<?= URL::base() ?>assets/new_theme/assets/js/imagesloaded.pkgd.min.js"></script>
<script src="<?= URL::base() ?>assets/new_theme/assets/js/select2.min.js"></script>
<script src="<?= URL::base() ?>assets/new_theme/assets/js/moment.js"></script>
<script src="<?= URL::base() ?>assets/new_theme/assets/js/date_picker.js"></script>
<script src="<?= URL::base() ?>assets/new_theme/assets/js/main.js?v=1"></script>
<!--<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>-->
<script src="<?= URL::base() ?>assets/new_theme/custom/js/register_students.js?v=17"></script>
<script src="<?= URL::base() ?>assets/new_theme/custom/js/general.js?v=4"></script>
<script src="<?= URL::base() ?>assets/new_theme/assets/js/dataTables.min.js"></script>
<!--    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>-->
<script src="<?= URL::base() ?>assets/new_theme/assets/js/dataTables.bootstrap5.min.js"></script>
<!--    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>-->
<script src="<?= URL::base() ?>assets/new_theme/custom/js/custom_popup.js"></script>

<? if (!empty($user_online) && $user_online->user_groub == 3) { ?>
    <script src="<?= URL::base() ?>assets/new_theme/custom/js/attend_live_session.js"></script>
<?}?>

<?php switch (TRUE) {

case ($controller == 'newhome_students_profile' && $action == 'index'): ?>
    <script src="<?= URL::base() ?>assets/new_theme/custom/js/students/profile.js?v=1554545445"></script>
<?php break;
case ($controller == 'newhome_sections' && $action == 'index'): ?>
    <script src="<?= URL::base() ?>assets/new_theme/custom/js/sections/view_all.js?v=1"></script>
<?php break;
case ($controller == 'newhome_sections' && $action == 'coursedetails'): ?>
    <script src="<?= URL::base() ?>assets/new_theme/custom/js/course_details.js?v=8"></script>
    <script src="<?php echo URL::base() . 'assets/site/'; ?>ajax/student_exams.js"></script>
    <script src="<?php echo URL::base() . 'assets/site/'; ?>ajax/exam_questions/jquery.nestable-rtl.js"
            type="text/javascript"></script>
<!--            <script src="--><?//= URL::base() ?><!--assets/new_theme/assets/js/jquery.validate.min.js"></script>-->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $('[data-toggle="tooltip"]').tooltip();
        $('span.glyphicon.glyphicon-alert').click(function () {
            $(this).toggleClass('clicked');
        })
        // activate Nestable for list 1
        $('#nestable_list_1,#nestable_connect').nestable({
            group: 1
        })
    </script>
<?php break;
case ($controller == 'newhome_instructors_memberships' && $action == 'register'): ?>
    <script src="<?= URL::base() ?>assets/new_theme/custom/js/instructors/register.js"></script>
<?php break;
case ($controller == 'newhome' && $action == 'cart'): ?>
    <script src="<?= URL::base() ?>assets/new_theme/custom/js/cart.js?v=1"></script>
    <?php break;

    default:
        break;
}
?>


</body>

</html>