@extends("home.layouts.master")
@section("content")

<div class="container-fluid">
  <div class="row">
    <div class="col-md-5">
      <div>
        <img src="assets/images/Capture.PNG" class="float-right full-picture mr-5 faq_picture" alt="">
      </div>
      <div class="hidden">
        <p class="inverted-text-two">FAQ</p>
      </div>
      <div class="inverted-logos-1 hidden">
        <p><a href="http://www.linkedin.com/in/guidefi" target="_blank"><i class="fa fa-linkedin-square footer-logo" aria-hidden="true"></i></a></p>
        <p><a href="http://www.facebook.com/guidefi" target="_blank"><i class="fa fa-facebook-square footer-logo" aria-hidden="true"></i></a></p>
        <p><a href="http://www.twitter.com/getguidefi" target="_blank"><i class=" footer-logo fa fa-twitter-square" aria-hidden="true"></i></a></p>
        <p><a href="https://www.instagram.com/guidefi/" target="_blank"><i class=" footer-logo fa fa-instagram" aria-hidden="true"></i></a></p>
      </div>
      <div class="faqCircle"></div>
    </div>
    <div class="col-md-7 overflow-hidden faqCircleBig">
    <p class="circleTwo"></p>
      <div>
        
        <h1 class="font-42 font-weight-normal text-green ml-3" style="margin-top: 78.5px;">FAQ</h1>

        
        <div class="accordion" id="accordion2">
          <div class="accordion-group px-3 py-4 w-75">
            <div class="accordion-heading">
              <a class="accordion-toggle accordionOne font-23 text-decoration-none text-black" id="faqsOne" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
                How Do I Get Matched with a <br> Guidefi Financial Professional? <i class=" fa fa-caret-right mt-2 faq-1 text-green" aria-hidden="true"></i>
              </a>
            </div>
            <div id="collapseOne" class="accordion-body collapse mt-2">
              <div class="accordion-inner">
                Users can get matched through the Guidefi app.
              </div>
            </div>
          </div>
          <div class="accordion-group px-3 py-4 w-75">
            <div class="accordion-heading">
              <a class="accordion-toggle accordionTwo font-23 text-decoration-none text-black" id="faqsTwo" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
                How Much Does the Match Cost?<i class="fa fa-caret-right mt-2 text-green" aria-hidden="true"></i>
              </a>
            </div>
            <div id="collapseTwo" class="accordion-body collapse mt-2">
              <div class="accordion-inner">
                Users can be matched with Guidefi financial professionals free of charge.
              </div>
            </div>
          </div>
          <div class="accordion-group px-3 py-4 w-75">
            <div class="accordion-heading">
              <a class="accordion-toggle accordionThree font-23 text-decoration-none text-black" id="faqsThree" data-toggle="collapse" data-parent="#accordion2" href="#collapseThree">
                Do The Financial Professionals In The <br> Guidefi Advisor Network Work For Free?<i class="faq_arrow_position_one fa fa-caret-right text-green" aria-hidden="true"></i>
              </a>
            </div>
            <div id="collapseThree" class="accordion-body collapse mt-2">
              <div class="accordion-inner">
                No, the professionals within the Guidefi Advisor Network have a range of prices for their services.
              </div>
            </div>
          </div>
          <div class="accordion-group px-3 py-4 w-75">
            <div class="accordion-heading">
              <a class="accordion-toggle accordionFour font-23 text-decoration-none text-black" id="faqsFour" data-toggle="collapse" data-parent="#accordion2" href="#collapsefour">
                How Does Guidefi Make Money?<i class="fa fa-caret-right mt-2 text-green" aria-hidden="true"></i>
              </a>
            </div>
            <div id="collapsefour" class="accordion-body collapse mt-2">
              <div class="accordion-inner">
                <p class="rest-devices-answer">Guidefi charges the financial professionals within the Guidefi Advisor Network a fee for access to Guidefi users. Members of the Guidefi Digital Community also pay a membership fee.</p>
                <p class="tablet-answer d-none">Guidefi charges the financial professionals within the Guidefi Advisor Network a membership fee. Members of the Guidefi Digital Community also pay a membership fee. </p>
              </div>
            </div>
          </div>
          <div class="accordion-group px-3 py-4 w-75">
            <div class="accordion-heading">
              <a class="accordion-toggle accordionFive font-23 text-decoration-none text-black" id="faqsFive" data-toggle="collapse" data-parent="#accordion2" href="#collapsefive">
                Is My Information Protected?<i class="fa fa-caret-right mt-2 text-green" aria-hidden="true"></i>
              </a>
            </div>
            <div id="collapsefive" class="accordion-body collapse mt-2">
              <div class="accordion-inner">
                Yes. Guidefi does not share your information with any third parties without your consent. In addition, your information is only shared with the financial professionals you are matched with.
              </div>
            </div>
          </div>
        </div>


      </div>
    </div>
  </div>
  <div class="row mt-5 pt-5">
    <div class="col-md-12 p-0">
      <div class="dif-bg text-align mt-5">
        <h5 class="pt-5 text-green font-44 font-weight-normal">Get Started On Your Wealth Journey Today
          <h5 class=" pt-1 pb-5 text-white sign-up-banner">Sign Up For The <br> Waiting List For The Guidefi App</h5>
          <button class="btn btn-download mb-5" data-toggle="modal" data-target=".joinGuidefi">Sign Up</button>
      </div>
    </div>


  </div>

</div>



@endsection
@section('scripts')
@parent

<script>
  $(document).ready(function() {
    $('.accordionOne').on('click', function() {
      if ($('#faqsOne i').hasClass('fa-caret-right')) {
        $('#faqsOne i').removeClass('fa-caret-right').addClass('fa-caret-down');

      } else {
        $('#faqsOne i').removeClass('fa-caret-down').addClass('fa-caret-right');
        // alert('hi');
      }

    });
    $('.accordionTwo').on('click', function() {
      if ($('#faqsTwo i').hasClass('fa-caret-right')) {
        $('#faqsTwo i').removeClass('fa-caret-right').addClass('fa-caret-down');

      } else {
        $('#faqsTwo i').removeClass('fa-caret-down').addClass('fa-caret-right');
        // alert('hi');
      }

    });
    $('.accordionThree').on('click', function() {
      if ($('#faqsThree i').hasClass('fa-caret-right')) {
        $('#faqsThree i').removeClass('fa-caret-right').addClass('fa-caret-down');

      } else {
        $('#faqsThree i').removeClass('fa-caret-down').addClass('fa-caret-right');
        // alert('hi');
      }

    });
    $('.accordionFour').on('click', function() {
      if ($('#faqsFour i').hasClass('fa-caret-right')) {
        $('#faqsFour i').removeClass('fa-caret-right').addClass('fa-caret-down');

      } else {
        $('#faqsFour i').removeClass('fa-caret-down').addClass('fa-caret-right');
        // alert('hi');
      }

    });
    $('.accordionFive').on('click', function() {
      if ($('#faqsFive i').hasClass('fa-caret-right')) {
        $('#faqsFive i').removeClass('fa-caret-right').addClass('fa-caret-down');

      } else {
        $('#faqsFive i').removeClass('fa-caret-down').addClass('fa-caret-right');
        // alert('hi');
      }

    });
  });
</script>
@endsection