<footer class="page-footer font-small blue pt-5 text-gray">
    <div class="row w-100">
        <div class="col-md-6 ml-auto mr-auto">
            <div class="text-center">
                <img src="<?php echo e(asset('assets/images/logo.svg')); ?>" alt="">
            </div>
            <div class="row mt-5 border-bottom">
                <div class="col-md-6 col-sm-6 contact-us">
                    <div>
                        <h5 class="float-left font-weight-bolder">Contact Us</h5>
                        <p><i class="fa fa-envelope ml-3 footer-logo" aria-hidden="true"></i> engage@guidefi.com</p>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="follow-us float-right">
                        <h5 class="float-left font-weight-bolder">Follow Us</h5>
                        <p class="float-right">
                            <a href="http://www.linkedin.com/in/guidefi" target="_blank"><i class="fa fa-linkedin-square ml-3 footer-logo" aria-hidden="true"></i></a>
                            <a href="http://www.facebook.com/guidefi" target="_blank"><i class="fa fa-facebook-square footer-logo ml-1" aria-hidden="true"></i></a>
                            <a href="http://www.twitter.com/getguidefi" target="_blank"><i class=" footer-logo fa fa-twitter-square ml-1" aria-hidden="true"></i></a>
                            <a href="https://www.instagram.com/guidefi/" target="_blank"><i class=" footer-logo fa fa-instagram ml-1" aria-hidden="true"></i></a></p>
                    </div>
                </div>
                <hr>
            </div>
            <div class="text-center mt-3">
                <p class="font-weight-normal" style="font-family: lato;">Copyright © 2020 Guidefi</p>
            </div>
        </div>
    </div>
</footer>

<div class="modal fade joinGuidefi"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 ml-2">
          <h5 class="modal-title ml-5 pl-4 stay-connected font-weight-bolder" id="exampleModalLabel">Stay Connected To Guidefi</h5>
        </div>


          <form id="contact_us" method="post" action="javascript:void(0)">
              <?php echo csrf_field(); ?>
          <div class="modal-body ml-5 mr-5 mt-3">
            <div class="form-group">
              <label for="exampleInputEmail1" class="font-weight-bolder">Name</label>
              <input type="name" placeholder="Name" name="name" class="form-control" id="contactInputName" aria-describedby="emailHelp" required>
            </div>
            <div class="form-group mt-4">
              <label for="exampleInputEmail1" class="font-weight-bolder">Email</label>
              <input type="email" placeholder="Email" name="email" class="form-control" id="contactInputEmail" aria-describedby="emailHelp" required>
            </div>
            <div class="form-group mt-4">
              <label for="exampleInputPhone1" class="font-weight-bolder">Phone</label>
              <input type="tel" placeholder="Phone" name="phone" class="form-control" id="contactInputPhone" required>
            </div>
            <div class="form-group mt-4">
              <label for="exampleFormControlSelect1" class="font-weight-bolder">Are you a financial professional?</label>
              <select class="form-control" name="financial_professional" id="contactFinancialProfessional" required>
                <option value="" disabled>Select</option>
                <option>YES</option>
                <option>NO</option>
              </select>
            </div>
            <div class="form-group mt-4">
              <label for="exampleFormControlSelect2" class="font-weight-bolder">Are you interested in?</label>
              <select class="form-control" name="interested_in" id="contactInterestedIn" required>
                <option disabled>Select</option>
                <option>Being matched with a financial professional</option>
                <option>Joining our Digital Community</option>
                <option>Both</option>
              </select>
            </div>
            <div class="form-group mt-4 form-check">
              <input type="checkbox" name="mail_list" class="form-check-input" id="contactMailList">
              <label class="form-check-label check-box" for="exampleCheck1">Yes, I would like to be added to our mailing list.</label>
            </div>
          </div>
          <div class="modal-footer border-top-0">
              <button type="submit" id="send_contact_form" class="btn btn-sign-up ml-auto mr-auto">Sign Up</button>
          </div>
        </form>
      </div>
    </div>
  </div><?php /**PATH C:\wamp64\www\guidefi\resources\views/home/partials/footer.blade.php ENDPATH**/ ?>