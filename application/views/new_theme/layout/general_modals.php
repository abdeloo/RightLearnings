
<!-- sigin-area sart
<div class="signin-area">-->
<div class="modal fade" id="SignInModal" tabindex="-1" aria-labelledby="SignInModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="col-3 d-block d-sm-none">
                    <button class="side-info-close" onclick="$('#SignInModal').modal('hide');"><i class="fal fa-times"></i></button>
                </div>
                <div class="signin-area-wrapper">
                    <div class="signup-box text-center">
                        <div class="signup-text">
                            <h3><?php echo Lang::__('Login'); ?></h3>
                        </div>
                        <div class="signup-thumb">
                            <img src="<?= URL::base() . $logo ?>" alt="image not found">
                        </div>
                    </div>
                    <form id="Login_Modal">
                        <div class="signup-form-wrapper">
                            <div class="form-group">
                                <div class="alert alert-danger Server_alerts hidden">
                                    <button class="close" data-close="alert"></button> <span id="Server_alerts"></span> 
                                </div>  
                            </div>
                            <div class="form-group">
                                <div class="signup-wrapper">
                                    <input name="username" type="text" class="form-control" required placeholder="<?php echo Lang::__('Username'); ?>">
                                </div>
                            </div>
                            <div class="signup-wrapper">
                                <input name="password" type="password" class="form-control" required autocomplete="off" placeholder="<?php echo Lang::__('Password'); ?>">
                            </div>
                            <div class="signup-action">
                                <div class="course-sidebar-list">
                                    <input type="checkbox" id="rememberme" name="remember" class="signup-checkbo">
                                    <label class="sign-check" for="sing-in"><span><?php echo Lang::__('Remember_me'); ?></span></label>
                                </div>
                            </div>
                            <div class="sing-buttom mb-20">
                                <button type="submit" class="sing-btn" data-loading-text="Loading..."><?= Lang::__('Login'); ?></button>
                            </div>
                            <div class="registered wrapper">
                                <div class="not-register">
                                    <span><?= Lang::__('Not_registered') ?>?</span><span><a href="#"> <?= Lang::__('sign_up') ?></a></span>
                                </div>
                                <div class="forget-password">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#ResetPasswordModal"><?= Lang::__('forgot_password') ?>?</a>
                                </div>
                            </div>
                            <div class="sign-social text-center">
                                <span><?= Lang::__('or_sign_in_with') ?></span>
                            </div>
                            <div class="sign-social-icon">
                                <div class="sign-facebook">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="9.034" height="18.531"
                                        viewBox="0 0 9.034 18.531">
                                        <path id="Path_212" data-name="Path 212"
                                            d="M183.106,757.2v-1.622c0-.811.116-1.274,1.39-1.274h1.621v-3.127h-2.664c-3.243,0-4.285,1.506-4.285,4.169v1.969h-2.085v3.127h1.969v9.265h4.054v-9.265h2.664l.347-3.243Z"
                                            transform="translate(-177.083 -751.176)" fill="#2467ec"/>
                                    </svg>
                                    <a href="#">Facebook</a>
                                </div>
                                <div class="sign-gmail">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="21.692" height="16.273"
                                        viewBox="0 0 21.692 16.273">
                                        <g id="gmail" transform="translate(0 -63.953)">
                                            <path id="Path_8685" data-name="Path 8685"
                                                d="M1.479,169.418H4.93v-8.381l-2.26-3.946L0,157.339v10.6a1.479,1.479,0,0,0,1.479,1.479Z"
                                                transform="translate(0 -89.192)" fill="#0085f7"/>
                                            <path id="Path_8686" data-name="Path 8686"
                                                d="M395.636,169.418h3.451a1.479,1.479,0,0,0,1.479-1.479v-10.6l-2.666-.248-2.264,3.946v8.381Z"
                                                transform="translate(-378.874 -89.192)" fill="#00a94b"/>
                                            <path id="Path_8687" data-name="Path 8687"
                                                d="M349.816,65.436,347.789,69.3l2.027,2.541,4.93-3.7V66.176A2.219,2.219,0,0,0,351.2,64.4Z"
                                                transform="translate(-333.054)" fill="#ffbc00"/>
                                            <path id="Path_8688" data-name="Path 8688"
                                                d="M72.7,105.365l-1.932-4.08L72.7,98.956l5.916,4.437,5.916-4.437v6.409L78.619,109.8Z"
                                                transform="translate(-67.773 -33.52)" fill="#ff4131" fill-rule="evenodd"/>
                                            <path id="Path_8689" data-name="Path 8689"
                                                d="M0,66.176v1.972l4.93,3.7V65.436L3.55,64.4A2.219,2.219,0,0,0,0,66.176Z"
                                                transform="translate(0)" fill="#e51c19"/>
                                        </g>
                                    </svg>
                                    <a href="<?= URL::base() . 'NewHome_Login/SigninGoogle'?>">Google</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- sigin-area end-->


<!-- signup-area-start -->

<div class="modal fade" id="SignUpModal" tabindex="-1" aria-labelledby="SignUpModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="register-modal-body modal-body">
                <div class="sign-up-wrapper">
                    <div class="signup-box text-center">
                        <div class="signup-text">
                            <h3><?= Lang::__('Sign_up') ?></h3>
                        </div>
                        <div class="signup-message">
                            <img src="<?= URL::base() ?>assets/new_theme/assets/img/sing-up/sign-up-message.png" alt="image not found">
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
                                    <input class="signup-checkbo form-control" type="checkbox" id="sing-up" required>
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
                                    <a href="<?= URL::base() . 'NewHome_Login/SigninGoogle'?>">Google</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- reset-password-area start
<div class="signin-area">-->
<div class="modal fade" id="ResetPasswordModal" tabindex="-1" aria-labelledby="ResetPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="col-3 text-end d-block d-sm-none">
                    <button class="side-info-close" onclick="$('#ResetPasswordModal').modal('hide');"><i class="fal fa-times"></i></button>
                </div>
                <div class="signin-area-wrapper">
                    <div class="signup-box text-center">
                        <div class="signup-text">
                            <h3><?php echo Lang::__('Reset_Password'); ?></h3>
                        </div>
                        <div class="signup-thumb">
                            <img src="<?= URL::base() . $logo ?>" alt="image not found">
                        </div>
                    </div>
                    <form id="Reset_Password_Modal">
                        <div class="signup-form-wrapper"> 
                            <div id="send_token">
                                <div class="form-group">
                                    <div class="signup-wrapper">
                                        <input name="email" id="user_email" type="text" class="form-control" required placeholder="<?php echo Lang::__('enter_your_email'); ?>">
                                    </div>
                                </div>
                            </div>
                            <div id="check_token" class="hidden">
                                <div class="signup-wrapper">
                                    <input name="token" type="password" class="form-control" required autocomplete="off" placeholder="<?php echo Lang::__('confirmation_token'); ?>">
                                </div>
                            </div>
                            <div id="reset_password" class="hidden">
                                <div class="signup-wrapper">
                                    <input name="password" type="password" class="form-control" required autocomplete="off" placeholder="<?php echo Lang::__('Password'); ?>">
                                </div>
                                <div class="signup-wrapper">
                                    <input name="confirm_password" type="password" class="form-control" required autocomplete="off" placeholder="<?php echo Lang::__('Password'); ?>">
                                </div>
                            </div>
                            
                            <div class="sing-buttom mb-20" id="send_token_div">
                                <button type="button" class="sing-btn" id="send_token_button" data-loading-text="Loading..."><?php echo Lang::__('Reset_Password'); ?></button>
                            </div>
                            <div class="registered wrapper">
                                <div class="not-register">
                                    <span id="confirmation_text"><?= Lang::__('confirmation_token_has_sent_to_your_email') ?>?</span><span><a href="#"><?= Lang::__('resend_token') ?></a></span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- reset-password-area end-->

