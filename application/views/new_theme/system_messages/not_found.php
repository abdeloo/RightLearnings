

<main>
   <!-- content-error-area -->
   <div class="content-error-area pt-120 pb-120">
      <div class="container">
         <div class="row justify-content-center">
            <div class="col-xl-8">
               <div class="content-error-item text-center">
                  <div class="error-thumb">
                     <img src="<?= URL::base() ?>assets/new_theme/custom/images/error-thumb.png" alt="image not found">
                  </div>
                  <div class="section-title">
                     <h2 class="mb-20"><?php echo $msg; ?>.</h2>
                     <p><?php echo Lang::__('or') . " " . $msg_meta; ?>.</p>
                  </div>
                  <div class="error-btn">
                     <a class="edu-btn" href="/NewHome"><?= Lang::__('back_to_homepage')?></a>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- content-error-end -->
</main>
