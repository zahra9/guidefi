@extends("home.layouts.master")
@section("content")



<div class="container-fluid">
  <div class="row">
    <div class="col-md-6">

      <div class="row">
        <div class="col-md-12">
          <div class="charlene-picture  mt-5">
            <img src="{{ asset('assets/images/CaptureOne.PNG') }}" class="float-right pt-2 mr-5 pr-5 commitment-picture" width="55%" alt="">
          </div>
          <div class="hidden ourCommitmentText">
            <p class="inverted-text-three">FAQS</p>
          </div>
          <div class="ourCommitmentLogo inverted-logos-2 hidden">
            <p><a href="http://www.linkedin.com/in/guidefi" target="_blank"><i class="fa fa-linkedin-square footer-logo" aria-hidden="true"></i></a></p>
            <p><a href="http://www.facebook.com/guidefi" target="_blank"><i class="fa fa-facebook-square footer-logo" aria-hidden="true"></i></a></p>
            <p><a href="http://www.twitter.com/getguidefi" target="_blank"><i class=" footer-logo fa fa-twitter-square" aria-hidden="true"></i></a></p>
            <p><a href="https://www.instagram.com/guidefi/" target="_blank"><i class=" footer-logo fa fa-instagram" aria-hidden="true"></i></a></p>
          </div>
        </div>
        <div class="col-md-12">
          <div>
            <h6 class="w-50 float-right font-weight-bold commitment">Guidefi is committed to driving
              a more inclusive financial ecosystem
              through the following measures:</h6>
          </div>
          <div class="commitmentTwo">
          <img src="{{asset('assets/images/Line-37.svg')}}" class="mb-4" width="40px" alt="">
            <p class="text-gray-actual wePlan ">We plan to continue this ongoing commitment by expanding these initial measures over time.</p>

          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-sm-10 overflow-hidden">
      <div>
        <h1 class="mt-5 font-weight-bold">OUR COMMITMENT</h1>
        <p class="about-us-p-width mt-4 font-weight-bold">Guidefi was built on the deep core belief that everyone
          deserves access to affordable financial services. But we
          know that everyone does not have access right now.</p>
          <img src="{{asset('assets/images/Line-37.svg')}}" alt="">
        <p class="font-12 text-gray-actual ourCommitment-p-width mt-4">
          Many members of the Black community have had limited access to fair and affordable
          financial services for hundreds of years. The financial services industry was designed
          with a legacy of systemic racism evidenced by centuries of predatory banking
          practices and discriminatory bank regulations. So it is no surprise that these factors
          have led to massive economic inequality across the Black community.
        </p>
        <p class="font-12 text-gray-actual ourCommitment-p-width mt-4">
          Thankfully, we believe the financial services industry can be <strong>redesigned</strong>. At Guidefi,
          we are imagining a financial ecosystem that is as inclusive and diverse as the
          population it serves.
        </p>
      </div>
      <div class="row mt-5 pt-2 pl-5 padding_lead">
        <div class="col-md-5 col-lg-6">
          <div class="leadershipSection">
            <h5 class="font-weight-bold">
              <div class="smallCircleFour"></div> Leadership
            </h5>
            <p class="text-gray-actual font-18" style="font-size: 18px;">Guidefi is and will remain Black woman-led.</p>
          </div>
        </div>
        <div class="col-md-7 col-lg-6">
          <div class="hiringSection">
            <h5 class="font-weight-bold">
              <div class="smallCircleTwo"></div>Hiring
            </h5>
            <p class="text-gray-actual font-18"style="font-size: 18px;">The Guidefi Advisor Network features a
              significant number of financial advisors, coaches,
              and counselors from the Black community. We
              plan to continue this commitment to supporting financial professionals of color.</p>
          </div>
        </div>
      </div>
      <div class="row pl-5 padding_lead">
        <div class="col-md-5 col-lg-6">
          <div class="educationSection">
            <h5 class="font-weight-bold">
              <div class="smallCircleThree"></div>Education
            </h5>
            <p class="text-gray-actual font-18"style="font-size: 18px;">We are committed to increasing
              financial literacy and financial
              capability levels across the Black
              community.</p>
          </div>
        </div>
        <div class="col-md-7 col-lg-6">
          <div class="investorsSection">
            <h5 class="font-weight-bold">
              <div class="smallCircleOne"></div>Investors
            </h5>
            <p class="text-gray-actual font-18"style="font-size: 18px;">Our current investors and any
              future investors must demonstrate
              a history of impactful commitment
              to Black Founders.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 p-0">
      <div class="dif-bg text-align mt-5">
        <h5 class="mb-0 pb-5 text-white text-left pl-3 sign-up-banner-1">In the summer of 2020, Guidefi joined the <a href="" class="text-white"><u>Fintech Equality Coalition</u></a>,
          a network of fintech firms committed to a more inclusive and equitable
          financial ecosystem. As a part of the Coalition, we plan to report on our
          progress on the measures listed above. We are excited about
          the inclusive financial future we are creating together.</h5>
      </div>
    </div>


  </div>

</div>




@endsection