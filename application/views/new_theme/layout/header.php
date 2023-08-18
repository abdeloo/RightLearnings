
<header>
    <div class="header-area sticky-header">
        <div class="container-fluid">
            <div class="header-main-wrapper">
                <div class="row align-items-center">
                    <div class="col-xl-7 col-lg-7 col-md-5 col-sm-9 col-9">
                        <div class="header-left d-flex align-items-center">
                            <div class="header-logo">
                                <a href="<?= URL::base() . 'NewHome' ?>"><img width="100px" height="50px" src="<?= URL::base() . $logo ?>" alt="logo"></a>
                            </div>
                            <div class="category-menu d-none d-xxl-block">
                                <div class="Category-click">
                                    <figure class="cattext">
                                        <svg class="icons" id="menu_1_" data-name="menu (1)"
                                             xmlns="http://www.w3.org/2000/svg" width="18.087" height="18.087"
                                             viewBox="0 0 18.087 18.087">
                                            <path id="Path_25" data-name="Path 25"
                                                  d="M3.768,0H.754A.754.754,0,0,0,0,.754V3.768a.754.754,0,0,0,.754.754H3.768a.754.754,0,0,0,.754-.754V.754A.754.754,0,0,0,3.768,0Z"
                                                  fill="#141517"></path>
                                            <path id="Path_26" data-name="Path 26"
                                                  d="M3.768,9H.754A.754.754,0,0,0,0,9.754v3.015a.754.754,0,0,0,.754.754H3.768a.754.754,0,0,0,.754-.754V9.754A.754.754,0,0,0,3.768,9Z"
                                                  transform="translate(0 -2.217)" fill="#141517"></path>
                                            <path id="Path_27" data-name="Path 27"
                                                  d="M3.768,18H.754A.754.754,0,0,0,0,18.754v3.015a.754.754,0,0,0,.754.754H3.768a.754.754,0,0,0,.754-.754V18.754A.754.754,0,0,0,3.768,18Z"
                                                  transform="translate(0 -4.434)" fill="#141517"></path>
                                            <path id="Path_28" data-name="Path 28"
                                                  d="M12.768,0H9.754A.754.754,0,0,0,9,.754V3.768a.754.754,0,0,0,.754.754h3.015a.754.754,0,0,0,.754-.754V.754A.754.754,0,0,0,12.768,0Z"
                                                  transform="translate(-2.217)" fill="#141517"></path>
                                            <path id="Path_29" data-name="Path 29"
                                                  d="M12.768,9H9.754A.754.754,0,0,0,9,9.754v3.015a.754.754,0,0,0,.754.754h3.015a.754.754,0,0,0,.754-.754V9.754A.754.754,0,0,0,12.768,9Z"
                                                  transform="translate(-2.217 -2.217)" fill="#141517"></path>
                                            <path id="Path_30" data-name="Path 30"
                                                  d="M12.768,18H9.754A.754.754,0,0,0,9,18.754v3.015a.754.754,0,0,0,.754.754h3.015a.754.754,0,0,0,.754-.754V18.754A.754.754,0,0,0,12.768,18Z"
                                                  transform="translate(-2.217 -4.434)" fill="#141517"></path>
                                            <path id="Path_31" data-name="Path 31"
                                                  d="M21.768,0H18.754A.754.754,0,0,0,18,.754V3.768a.754.754,0,0,0,.754.754h3.015a.754.754,0,0,0,.754-.754V.754A.754.754,0,0,0,21.768,0Z"
                                                  transform="translate(-4.434)" fill="#141517"></path>
                                            <path id="Path_32" data-name="Path 32"
                                                  d="M21.768,9H18.754A.754.754,0,0,0,18,9.754v3.015a.754.754,0,0,0,.754.754h3.015a.754.754,0,0,0,.754-.754V9.754A.754.754,0,0,0,21.768,9Z"
                                                  transform="translate(-4.434 -2.217)" fill="#141517"></path>
                                            <path id="Path_33" data-name="Path 33"
                                                  d="M21.768,18H18.754a.754.754,0,0,0-.754.754v3.015a.754.754,0,0,0,.754.754h3.015a.754.754,0,0,0,.754-.754V18.754A.754.754,0,0,0,21.768,18Z"
                                                  transform="translate(-4.434 -4.434)" fill="#141517"></path>
                                        </svg>
                                        <span class="text"><?= Lang::__('Categories')?></span></figure>
                                    <div class="dropdown-category">
                                        <nav>
                                            <ul>
                                                <?foreach($Categories as $Category){
                                                    $SubCategories = $Category->SubCategories->where('is_deleted','=',NULL)->find_all();
                                                ?>
                                                    <li class="<?= (count($SubCategories) > 0)? 'item-has-children': ''?>"><a href="#"><?= $Category->{'name_'.$lang}?></a>
                                                        <?if(count($SubCategories) > 0){
                                                            foreach($SubCategories as $SubCategory){?>
                                                            <ul class="category-submenu">
                                                                <li><a href="#"><?= $SubCategory->{'name_'.$lang}?></a></li>
                                                            </ul>
                                                        <?}}?>
                                                    </li>
                                                <?}?>
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                            <div class="main-menu d-none d-xl-block">
                                <nav id="mobile-menu">
                                    <ul>
                                        <?php
                                        $Menus = ORM::factory('Menus')->order_by('order')->where('parent', '=', NULL)->where('is_deleted', '=', NULL)->find_all();
                                        $i = 1;
                                        foreach ($Menus as $level1) {
                                            $Menus_Level2 = $level1->Childs->order_by('order')->where('is_deleted', '=', NULL)->find_all();
                                            $Menus_Level2_Count = count($Menus_Level2);
                                            ?>
                                            <li class="<?php if ($Menus_Level2_Count > 0) { ?>menu-item-has-children<?php } ?>">
                                                <a class="<?php if ($Menus_Level2_Count > 0) { ?>dropdown-toggle<?php } ?>"
                                                   <?php if ($level1->target == 2) { ?>target="_blank"<?php } ?>
                                                   href="<?php echo empty($level1->new_link) ? '#' : ($level1->internal_link == 1) ? URL::base() . $level1->new_link : $level1->new_link; ?>">
                                                    <?php echo $level1->{'name_' . $lang}; ?>
                                                </a>
                                                <?php if ($Menus_Level2_Count > 0) { ?>
                                                <ul class="sub-menu">
                                                    <?php } ?>
                                                    <?php foreach ($Menus_Level2 as $Menus_Level2_value) { ?>
                                                        <?php
                                                        $Menus_Level3 = $Menus_Level2_value->Childs->order_by('order')->where('is_deleted', '=', NULL)->find_all();
                                                        $Menus_Level3_Count = count($Menus_Level3);
                                                        ?>
                                                        <li <?php if ($Menus_Level3_Count > 0 || $Menus_Level2_value->id == 54) { ?>class="menu-item-has-children"<?php } ?>>
                                                            <a <?php if ($Menus_Level2_value->target == 2) { ?>target="_blank"<?php } ?>
                                                               href="<?php echo empty($Menus_Level2_value->new_link) ? '#' : ($Menus_Level2_value->internal_link == 1) ? URL::base() . $Menus_Level2_value->new_link : $Menus_Level2_value->new_link; ?>">
                                                                <?php echo $Menus_Level2_value->{'name_' . $lang}; ?>
                                                            </a>
                                                            <?php if ($Menus_Level2_value->id == 54) {
                                                                $Courses_types = ORM::factory('Trainers_Courses_Types')->where('is_deleted', '=', NULL)->find_all();
                                                                ?>
                                                                <ul class="sub-menu">
                                                                    <?php foreach ($Courses_types as $Courses_type) {
                                                                        $courses = ORM::factory('Trainers_Courses')->where('course_type_id', '=', $Courses_type->id)->where('app_status', '=', 1)->where('is_local', '=', 2)->where('is_approved', '=', 1)->where('is_deleted', '=', NULL)->find_all();
                                                                        ?>
                                                                        <li <?php if (count($courses) > 0){ ?>class="menu-item-has-children"<?php } ?>>
                                                                            <a href="<?php echo '#'; ?>">
                                                                                <?php echo $Courses_type->{'name_' . $lang}; ?>
                                                                            </a>
                                                                            <?php if (count($courses) > 0) { ?>
                                                                                <ul class="sub-menu">
                                                                                    <?php foreach ($courses as $course) { ?>
                                                                                        <li>
                                                                                            <a href="<?php echo URL::base() . 'Admission_Courses/details/' . $course->id; ?>">
                                                                                                <?php echo $course->{'name_' . $lang}; ?>
                                                                                            </a>
                                                                                        </li>
                                                                                    <?php } ?>
                                                                                </ul>
                                                                            <?php } ?>
                                                                        </li>
                                                                    <?php } ?>
                                                                </ul>
                                                            <? } else { ?>
                                                                <?php if ($Menus_Level3_Count > 0) { ?>
                                                                    <ul class="sub-menu">
                                                                <?php } ?>
                                                                <?php foreach ($Menus_Level3 as $Menus_Level3_val) { ?>
                                                                    <li>
                                                                        <a href="<?php echo empty($Menus_Level3_val->new_link) ? '#' : ($Menus_Level3_val->internal_link == 1) ? URL::base() . $Menus_Level3_val->new_link : $Menus_Level3_val->new_link; ?>">
                                                                            <?php echo $Menus_Level3_val->{'name_' . $lang}; ?>
                                                                        </a>
                                                                    </li>
                                                                <?php } ?>
                                                                <?php if ($Menus_Level3_Count > 0) { ?>
                                                                    </ul>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        </li>

                                                    <?php } ?>
                                                    <?php if ($Menus_Level2_Count > 0) { ?>
                                                </ul>
                                            <?php } ?>
                                            </li>
                                        <?php } ?>

                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-5 col-lg-5 col-md-7 col-sm-3 col-3">
                        <div class="header-right d-flex align-items-center justify-content-end">
                            <div class="header-search d-none d-xxl-block mr-30 ml-30">
                                <form action="#">
                                    <div class="search-icon p-relative">
                                        <input type="text" placeholder="<?= Lang::__('search')?>...">
                                        <button type="submit"><i class="fas fa-search"></i></button>
                                    </div>
                                </form>
                            </div>

                            <!--                            <div class="menu-bar d-xl-none ml-20">-->
                            <!--                                <div class="header-user-language">-->
                            <!--                            <span>-->
                            <!--                                <a class="user-btn-sign-up" href="#">En</a>-->
                            <!--                            </span>-->
                            <!--                                </div>-->
                            <!---->
                            <!--                            </div>-->


                            <div class="cart-wrapper mr-20">
                                <a href="javascript:void(0);" class="cart-toggle-btn" <?= (!empty($user_online))? '' : 'data-bs-toggle="modal" data-bs-target="#SignInModal"'?>>
                                    <div class="header__cart-icon p-relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="19.988" height="19.988"
                                             viewBox="0 0 19.988 19.988">
                                            <g id="trolley-cart" transform="translate(-1 -1)">
                                                <path id="Path_36" data-name="Path 36"
                                                      d="M1.666,2.333H3.8L6.159,12.344a1.993,1.993,0,0,0,.171,3.98H17.656a.666.666,0,1,0,0-1.333H6.33a.666.666,0,0,1,0-1.333H17.578a1.992,1.992,0,0,0,1.945-1.541l1.412-6a2,2,0,0,0-1.946-2.456H5.486L4.98,1.514A.666.666,0,0,0,4.331,1H1.666a.666.666,0,0,0,0,1.333ZM18.989,5a.677.677,0,0,1,.649.819l-1.412,6a.662.662,0,0,1-.648.514H7.524L5.8,5Z"
                                                      transform="translate(0 0)" fill="#141517"/>
                                                <path id="Path_37" data-name="Path 37"
                                                      d="M20,27a2,2,0,1,0,2-2A2,2,0,0,0,20,27Zm2.665,0A.666.666,0,1,1,22,26.333.666.666,0,0,1,22.665,27Z"
                                                      transform="translate(-6.341 -8.01)" fill="#141517"/>
                                                <path id="Path_38" data-name="Path 38"
                                                      d="M9,27a2,2,0,1,0,2-2A2,2,0,0,0,9,27Zm2.665,0A.666.666,0,1,1,11,26.333.666.666,0,0,1,11.665,27Z"
                                                      transform="translate(-2.67 -8.01)" fill="#141517"/>
                                            </g>
                                        </svg>
                                        <span style="<?= (empty($user_online))? 'background:#ec2424' : ''?>" class="item-number"><?= (isset($Waiting_courses))? count($Waiting_courses) : 0?></span>
                                    </div>
                                </a>
                            </div>
                            <div class="cart-wrapper mr-20 user-profile">
                                <a href="javascript:void(0);" class="profile-toggle-btn">
                                    <div class="header__cart-icon p-relative">
                                        <? if (empty($user_online)){ ?>
                                            <a class="user-btn-sign-in" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#SignInModal">
                                                <i class="fa fa-user-circle fa-lg" aria-hidden="true"></i><span><img style="background:#ec2424" class="item-number" src="<?php echo URL::base() . 'assets/new_theme/assets/img/shape/shape-light.png'; ?>"></span>
                                            </a>
                                        <?}else {?>
                                            <i class="fa fa-user-circle fa-lg" aria-hidden="true"></i><span><img class="item-number" src="<?php echo URL::base() . 'assets/new_theme/assets/img/shape/shape-light.png'; ?>"></span>
                                        <?}?>
                                    </div>
                                </a>
                            </div>
                            <div class="cart-wrapper mr-20 ml-10 user-profile">
                                <a href="javascript:void(0);" class="notification-toggle-btn">
                                    <div class="header__cart-icon p-relative">
                                        <? if (empty($user_online)){ ?>
                                            <a class="user-btn-sign-in" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#SignInModal">
                                                <i class="fa fa-bell fa-lg" aria-hidden="true"></i><span class="item-number">3</span>
                                            </a>
                                        <?}else {?>
                                            <i class="fa fa-bell fa-lg" aria-hidden="true"></i>
                                            <span class="item-number">3</span>
                                        <?}?>
                                    </div>
                                </a>
                            </div>
                            <? if ($user_online) { ?>
                                <div class="d-none d-md-block">
                                    <a class="edu-btn" href="<?php echo URL::base() . 'NewHome_Login/logout'; ?>">Sign Out</a>
                                </div>
                            <?} else { ?>
                                <!--<div class="user-btn-inner p-relative d-none d-md-block">
                                    <div class="user-btn-wrapper">
                                        <div class="user-btn-content ">
                                            <a class="user-btn-sign-in" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#SignInModal">Sign In</a>
                                        </div>
                                    </div>
                                </div>-->
                                <div class="d-none d-md-block">
                                    <a class="user-btn-sign-up edu-btn" href="javascript:void(0)">Sign Up</a>
                                </div>
                                <div class="menu-bar d-xl-none ml-20">
                                    <a class="side-toggle" href="javascript:void(0)">
                                        <div class="bar-icon">
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                        </div>
                                    </a>
                                </div>


                            <? } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- header-area-end -->

