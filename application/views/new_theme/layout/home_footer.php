<!-- footer-area-start -->
    <footer>
        <div class="university-footer-area pt-100 pb-60">
            <div class="footer">
                <div class="container">
                    <div class="footer-main">
                        <div class="row">
                            <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-6">
                                <div class="university-footer-widget uf-w1 mb-40">
                                    <div class="footer-widget-head">
                                        <div class="footer-logo mb-30">
                                            <a href="<?= URL::base() . 'NewHome' ?>">
                                                <img width="190" height="48" src="<?= URL::base() . $logo?>" alt="image not found">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="university-footer-widget-body">
                                        <p class="mb-30"><?= $footer->{'text_'.$lang}?>.</p>

                                        <div class="university-footer-icon">
                                            <ul>
                                                <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                                <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                                <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                                <li><a href="#"> <i class="fab fa-linkedin-in"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-6">
                                <div class="university-footer-widget uf-w2 mb-40">
                                    <div class="university-footer-widget-head mb-35">
                                        <h4 class="university-footer-widget-title"><?= Lang::__('College') ?></h4>
                                    </div>
                                    <div class="university-footer-widget-body">
                                        <div class="university-footer-link">
                                            <ul>
                                                <li><a href="<?php echo URL::base().'News/ShowAll'; ?>"><?php echo Lang::__('News'); ?></a></li>
                                                <li><a href="<?php echo URL::base().'Announcements/ShowAll'; ?>"><?php echo Lang::__('Announcements'); ?></a></li>
                                                <li><a href="<?php echo URL::base().'Page/view/1'; ?>"><?php echo ORM::factory('Pages',1)->{'title_'.$lang}; ?></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php $M7 = ORM::factory('Menus',7); ?>
                            <?php if($M7->loaded() && empty($M7->is_deleted)){ ?>
                                <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-6">
                                    <div class="university-footer-widget uf-w3 mb-40">
                                        <div class="university-footer-widget-head mb-35">
                                            <h4 class="university-footer-widget-title"><?php echo $M7->{'name_'.$lang}; ?></h4>
                                        </div>
                                        <div class="university-footer-widget-body">
                                            <div class="university-footer-link">
                                                <ul>
                                                    <?php
                                                        $M7Childs = $M7->Childs->where('is_deleted','=',NULL)->find_all();
                                                        foreach ($M7Childs as $value) { 
                                                    ?>
                                                        <li>
                                                            <a <?php if ($value->target == 2) { ?>target="_blank"<?php } ?> href="<?php echo empty($value->link) ? '#' : ($value->internal_link == 1) ? URL::base() . $value->link : $value->link; ?>">
                                                                <?php echo $value->{'name_' . $lang}; ?> 
                                                            </a>
                                                        </li>
                                                    <?}?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?}?>
                            <?php $M4 = ORM::factory('Menus',4); ?>
                            <?php if($M4->loaded() && empty($M4->is_deleted)){ ?>
                                <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-6">
                                    <div class="university-footer-widget uf-w4 mb-40">
                                        <div class="footer-widget-head mb-35">
                                            <h4 class="footer-widget-title"><?php echo $M4->{'name_'.$lang}; ?></h4>
                                        </div>
                                        <div class="university-footer-widget-body">
                                            <div class="university-footer-link">
                                                <ul>
                                                    <?php
                                                        $M7Childs = $M4->Childs->where('is_deleted','=',NULL)->find_all();
                                                        foreach ($M7Childs as $value) { 
                                                    ?>
                                                        <li>
                                                            <a <?php if ($value->target == 2) { ?>target="_blank"<?php } ?> href="<?php echo empty($value->link) ? '#' : ($value->internal_link == 1) ? URL::base() . $value->link : $value->link; ?>">
                                                                <?php echo $value->{'name_' . $lang}; ?> 
                                                            </a>
                                                        </li>
                                                    <?}?>
                                                </ul>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            <?}?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="university-sub-footer">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-5">
                        <div class="sub-footer-text">
                            <span>© <?php echo Lang::__('All Rights Reserved'); ?> <a href="https://rightlearning.net/"><?php echo Date('Y'); ?></a>
                            </span>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-7">
                        <div class="sub-footer-link">
                            <ul>
                                <li><a href="#">Privacy Policy</a></li>
                                <li><a href="#">Terms & Condition</a></li>
                                <li><a href="#">Sitemap</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- footer-area-end -->




<!-- signup-area-start -->

<div class="modal fade" id="SignUpModal" tabindex="-1" aria-labelledby="SignUpModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg">

        <div class="modal-content">

            <div class="modal-body">

                <div class="sign-up-wrapper">
                    <div class="signup-box text-center">
                        <div class="signup-text">
                            <h3><?= Lang::__('Sign_up') ?></h3>
                        </div>
                        <div class="signup-message">
                            <img src="<?= URL::base() ?>assets/new_theme/assets/img/sing-up/sign-up-message.png"
                                 alt="image not found">
                        </div>
                        <div class="signup-thumb">
                            <img src="<?= URL::base() . $logo ?>" alt="image not found">
                        </div>
                    </div>
                    <form id="register_student" class="needs-validation" novalidate>
                        <div class="signup-form-wrapper">
                            <div class="signup-wrapper">
                                <div class="country-select">
                                    <select id="select_school" name="college" class="select2 form-control">
                                       <option value=""><?= Lang::__('select')?></option>
                                        <? foreach ($Centers as $Center) { ?>
                                            <option value="<?= $Center->id ?>"><?= $Center->{'name_' . $lang} ?></option>
                                        <? } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="signup-wrapper hidden">
                                <div class="country-select">
                                    <select name="major" id="select_branch" placeholder="<?= Lang::__('Branch') ?>" class="select2">
                                        <option value=""><?= Lang::__('select')?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="signup-wrapper hidden">
                                <div class="country-select">
                                    <select name="program" id="select_stage" placeholder="<?= Lang::__('Stage') ?>"  class="select2">
                                        <option value=""><?= Lang::__('select')?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="signup-wrapper hidden">
                                <div class="country-select">
                                    <select name="plan" id="select_grade" placeholder="<?= Lang::__('the_grade') ?>" class="select2">
                                        <option value=""><?= Lang::__('select')?></option>
                                    </select>

                                </div>
                            </div>
                            <div class="signup-wrapper hidden">
                                <div class="country-select">
                                    <select name="Gender" id="select_gender" placeholder="<?= Lang::__('Gender') ?>" class="form-control select2">
                                        <option value=""><?= Lang::__('select')?></option>
                                        <?php foreach ($Genders as $Gender) { ?>
                                            <option value="<?php echo $Gender->id; ?>"><?php echo $Gender->{"name_" . $lang}; ?></option>
                                        <?php } ?>
                                    </select>

                                </div>
                            </div>
                            <div class="signup-wrapper hidden">
                                <input class="form-control" name="Full_Name_Arabic" type="text" placeholder="<?php echo Lang::__('Full_Name_Arabic'); ?>">
                            </div>
                            <div class="signup-wrapper hidden">
                                <input class="form-control" name="Full_Name_English" type="text" placeholder="<?php echo Lang::__('Full_Name_English'); ?>">
                            </div>
                            <div class="signup-wrapper hidden">
                                <input class="form-control" name="Email" type="text" placeholder="<?php echo Lang::__('Email'); ?>">
                            </div>
                            <div class="signup-wrapper hidden">
                                <input class="form-control" name="Address" type="text" placeholder="<?php echo Lang::__('Address'); ?>">
                            </div>
                            <div class="signup-wrapper hidden">
                                <input class="form-control" name="ID_No" type="text" placeholder="<?php echo Lang::__('ID_No'); ?>">
                            </div>
                            <div class="signup-wrapper hidden">
                                <input class="form-control" name="Mobile" type="text" placeholder="<?php echo Lang::__('Mobile'); ?>">
                            </div>

                            <div class="signup-wrapper hidden">
                                <input class="form-control" name="Phone" type="text" placeholder="<?php echo Lang::__('Home_Phone'); ?>">
                            </div>

                            <div class="signup-wrapper hidden">
                                <div class="country-select">
                                    <select name="country" id="select_country" placeholder="<?= Lang::__('country') ?>" class="select2 form-control">
                                        <option value=""><?= Lang::__('select')?></option>
                                        <? foreach ($Countries as $Country) { ?>
                                            <option value="<?= $Country->id ?>"><?= $Country->{'name_' . $lang} ?></option>
                                        <? } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="signup-wrapper hidden">
                                <div class="country-select">
                                    <select name="city" id="select_city" placeholder="<?= Lang::__('city') ?>" class="select2 form-control">
                                        <option value=""><?= Lang::__('select')?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="signup-action">
                                <div class="course-sidebar-list">
                                    <input class="signup-checkbo" type="checkbox" id="sing-up">
                                    <label class="sign-check" for="sing-up">
                                        <span><?= Lang::__('Accept_the_terms_and ') ?><a href="#"><?= Lang::__('Privacy_ Policy') ?></a></span>
                                    </label>
                                </div>
                            </div>
                            <div id="submit-buttons" class="sing-buttom mb-20">
                                <button type="submit" id="submit-buttons" class="sing-btn"><?= Lang::__('Register_now') ?></button>
                            </div>
                            <div class="acount-login text-center">
                                <a href="#"><span><?= Lang::__('have_account_sign_in') ?>? <?= Lang::__('Log_in') ?></span></a>
                            </div>
                            <div class="sign-social text-center">
                                <span><?= Lang::__('Or_Sign_in_with') ?></span>
                            </div>
                            <div class="sign-social-icon">
                                <div class="sign-facebook">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="9.034" height="18.531"
                                         viewBox="0 0 9.034 18.531">
                                        <path id="Path_2121121" data-name="Path 212"
                                              d="M183.106,757.2v-1.622c0-.811.116-1.274,1.39-1.274h1.621v-3.127h-2.664c-3.243,0-4.285,1.506-4.285,4.169v1.969h-2.085v3.127h1.969v9.265h4.054v-9.265h2.664l.347-3.243Z"
                                              transform="translate(-177.083 -751.176)" fill="#2467ec"/>
                                    </svg>
                                    <a href="#">Facebook</a>
                                </div>
                                <div class="sign-gmail">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="21.692" height="16.273"
                                         viewBox="0 0 21.692 16.273">
                                        <g id="gmail-01" transform="translate(0 -63.953)">
                                            <path id="Path_868365" data-name="Path 863185"
                                                  d="M1.479,169.418H4.93v-8.381l-2.26-3.946L0,157.339v10.6a1.479,1.479,0,0,0,1.479,1.479Z"
                                                  transform="translate(0 -89.192)" fill="#0085f7"/>
                                            <path id="Path_863286" data-name="Path 8683106"
                                                  d="M395.636,169.418h3.451a1.479,1.479,0,0,0,1.479-1.479v-10.6l-2.666-.248-2.264,3.946v8.381Z"
                                                  transform="translate(-378.874 -89.192)" fill="#00a94b"/>
                                            <path id="Path_8322687" data-name="Path 831687"
                                                  d="M349.816,65.436,347.789,69.3l2.027,2.541,4.93-3.7V66.176A2.219,2.219,0,0,0,351.2,64.4Z"
                                                  transform="translate(-333.054)" fill="#ffbc00"/>
                                            <path id="Path_863088" data-name="Path 868038"
                                                  d="M72.7,105.365l-1.932-4.08L72.7,98.956l5.916,4.437,5.916-4.437v6.409L78.619,109.8Z"
                                                  transform="translate(-67.773 -33.52)" fill="#ff4131"
                                                  fill-rule="evenodd"/>
                                            <path id="Path_8682519" data-name="Path 868921"
                                                  d="M0,66.176v1.972l4.93,3.7V65.436L3.55,64.4A2.219,2.219,0,0,0,0,66.176Z"
                                                  transform="translate(0)" fill="#e51c19"/>
                                        </g>
                                    </svg>
                                    <a href="#">Google</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>


            </div>

        </div>

    </div>
</div>


<!-- signup-area-start -->