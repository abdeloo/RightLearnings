<style>
    .hidden {
        display: none;
    }
</style>
<!-- footer-area-start -->
<footer>
    <div class="footer-area pt-100">
        <div class="container">
            <div class="footer-main mb-60">
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                        <div class="footer-widget f-w1 mb-40">
                            <div class="footer-img">
                                <a href="<?= Lang::__('home') ?>"> <img width="190" height="48" src="<?= URL::base() . $logo ?>" alt="footer-logo"></a>
                                <p><?= $footer->{'text_' . $lang} ?>.</p>
                            </div>
                            <div class="footer-icon">
                                <a href="#"><i class="fab fa-facebook-f"></i></a>
                                <a href="#"><i class="fab fa-twitter"></i></a>
                                <a href="#"><i class="fab fa-instagram"></i></a>
                                <a href="#"> <i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                        <div class="footer-widget f-w2 mb-40">
                            <h3><?= Lang::__('College') ?></h3>
                            <ul>
                                <li>
                                    <a href="<?php echo URL::base() . 'News/ShowAll'; ?>"><?php echo Lang::__('News'); ?></a>
                                </li>
                                <li>
                                    <a href="<?php echo URL::base() . 'Announcements/ShowAll'; ?>"><?php echo Lang::__('Announcements'); ?></a>
                                </li>
                                <li>
                                    <a href="<?php echo URL::base() . 'Page/view/1'; ?>"><?php echo ORM::factory('Pages', 1)->{'title_' . $lang}; ?></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php $M7 = ORM::factory('Menus', 7);
                    if ($M7->loaded() && empty($M7->is_deleted)) {
                        ?>
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                            <div class="footer-widget f-w3 mb-40">
                                <h3><?php echo $M7->{'name_' . $lang}; ?></h3>
                                <ul>
                                    <?php
                                    $M7Childs = $M7->Childs->where('is_deleted', '=', NULL)->find_all();
                                    foreach ($M7Childs as $value) {
                                        ?>
                                        <li>
                                            <a <?php if ($value->target == 2) { ?>target="_blank"<?php } ?>
                                               href="<?php echo empty($value->link) ? '#' : ($value->internal_link == 1) ? URL::base() . $value->link : $value->link; ?>"><?php echo $value->{'name_' . $lang}; ?></a>
                                        </li>
                                    <?
                                    } ?>
                                </ul>
                            </div>
                        </div>
                    <? }
                    $M4 = ORM::factory('Menus', 4);
                    if ($M4->loaded() && empty($M4->is_deleted)) {
                        ?>
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                            <div class="footer-widget f-w4 mb-40">
                                <h3><?php echo $M4->{'name_' . $lang}; ?></h3>
                                <ul>
                                    <?php
                                    $M7Childs = $M4->Childs->where('is_deleted', '=', NULL)->find_all();
                                    foreach ($M7Childs as $value) {
                                        ?>
                                        <li>
                                            <a <?php if ($value->target == 2) { ?>target="_blank"<?php } ?>
                                               href="<?php echo empty($value->link) ? '#' : ($value->internal_link == 1) ? URL::base() . $value->link : $value->link; ?>">
                                                <?php echo $value->{'name_' . $lang}; ?>
                                            </a>
                                        </li>
                                    <? } ?>
                                </ul>
                            </div>
                        </div>
                    <? } ?>
                </div>
            </div>
            <div class="copyright-area">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                            <div class="copyright-text border-line">
                                <p>© Copyrighted & Designed
                                    by <a href="https://rightlearning.net/"><span>RightLearning</span></a></p>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-sm-6">
                            <div class="copy-right-support border-line-2">
                                <div class="copy-right-svg">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="43.945" height="50"
                                         viewBox="0 0 43.945 50">
                                        <g id="headphones" transform="translate(-31)">
                                            <g id="Group_2171" data-name="Group 2171"
                                               transform="translate(36.859 20.508)">
                                                <g id="Group_2170" data-name="Group 2170">
                                                    <path id="Path_2983" data-name="Path 2983"
                                                          d="M95.395,210A4.4,4.4,0,0,0,91,214.395v11.914a4.395,4.395,0,1,0,8.789,0V214.395A4.4,4.4,0,0,0,95.395,210Z"
                                                          transform="translate(-91 -210)" fill="#2467ec"/>
                                                </g>
                                            </g>
                                            <g id="Group_2173" data-name="Group 2173"
                                               transform="translate(31 23.669)">
                                                <g id="Group_2172" data-name="Group 2172">
                                                    <path id="Path_2984" data-name="Path 2984"
                                                          d="M33.93,243.6a7.268,7.268,0,0,1,.125-1.234A4.386,4.386,0,0,0,31,246.529v6.055a4.386,4.386,0,0,0,3.054,4.163,7.268,7.268,0,0,1-.125-1.234Z"
                                                          transform="translate(-31 -242.366)" fill="#2467ec"/>
                                                </g>
                                            </g>
                                            <g id="Group_2175" data-name="Group 2175"
                                               transform="translate(48.578 20.508)">
                                                <g id="Group_2174" data-name="Group 2174">
                                                    <path id="Path_2985" data-name="Path 2985"
                                                          d="M227.113,210a4.4,4.4,0,0,0-4.395,4.395v11.914a4.4,4.4,0,0,0,4.395,4.395,4.335,4.335,0,0,0,1.259-.206,4.386,4.386,0,0,1-4.189,3.136h-4.664a4.395,4.395,0,1,0,0,2.93h4.664a7.333,7.333,0,0,0,7.324-7.324V214.395A4.4,4.4,0,0,0,227.113,210Z"
                                                          transform="translate(-211 -210)" fill="#2467ec"/>
                                                </g>
                                            </g>
                                            <g id="Group_2177" data-name="Group 2177"
                                               transform="translate(71.891 23.669)">
                                                <g id="Group_2176" data-name="Group 2176">
                                                    <path id="Path_2986" data-name="Path 2986"
                                                          d="M449.722,242.366a7.266,7.266,0,0,1,.125,1.234v11.914a7.266,7.266,0,0,1-.125,1.234,4.386,4.386,0,0,0,3.055-4.163v-6.055A4.386,4.386,0,0,0,449.722,242.366Z"
                                                          transform="translate(-449.722 -242.366)" fill="#2467ec"/>
                                                </g>
                                            </g>
                                            <g id="Group_2179" data-name="Group 2179" transform="translate(31)">
                                                <g id="Group_2178" data-name="Group 2178">
                                                    <path id="Path_2987" data-name="Path 2987"
                                                          d="M52.973,0A22,22,0,0,0,31,21.973v.037a7.253,7.253,0,0,1,3-1.361,19.02,19.02,0,0,1,37.952,0,7.256,7.256,0,0,1,3,1.361v-.037A22,22,0,0,0,52.973,0Z"
                                                          transform="translate(-31)" fill="#2467ec"/>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </div>
                                <div class="copyright-svg-content">
                                    <p>Have a question? Call us 24/7</p>
                                    <h5><a href="tel:(987)547587587">(987) 547587587</a></h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-5 col-lg-5 col-md-12">
                            <div class="copyright-subcribe">
                                <form action="#" class="widget__subscribe">
                                    <div class="field">
                                        <input type="email" placeholder="Enter Email">
                                    </div>
                                    <button type="submit">Subscribe<i class="fas fa-paper-plane"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- footer-area-end -->


<!-- signup-area-start -->

<!-- sigin-area sart-->
<!--<div id="display_content" class="video-area">
    <div class="signin-area-wrapper">
        <div id="show_file" class="signup-form-wrapper">
            <? // if($Video->file_path != NULL){?>
            <video width="100%" controls controlsList="nodownload" id="myvideo">
                <source id="video_src" src="#" type="video/mp4">
            </video>
            <? //} else{?>
            <? // $Video->youtube_link ?>
            <? //}?>
        </div>
    </div>
</div>-->
<!-- sigin-area end-->
