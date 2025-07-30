@extends("home.layouts.master")
@section("content")

<div class="container-fluid">
    <div class="row">
        <div class="col-md-5 col-xl-6">
            <div class="charlene-picture">
                <img src="{{ asset('assets/images/charlene.jpg') }}" class="float-right rounded-circle mt-4 mr-4" width="359px" alt="">
            </div>
            <div class="hidden">
                <p class="inverted-text">ABOUT US</p>
            </div>
            <div class="inverted-logos hidden">
                <p><a href=""><i class="fa fa-linkedin-square footer-logo" aria-hidden="true"></i></a></p>
                <p><a href=""><i class="fa fa-facebook-square footer-logo" aria-hidden="true"></i></a></p>
                <p><a href=""><i class=" footer-logo fa fa-twitter-square" aria-hidden="true"></i></a></p>
                <p><a href=""><i class=" footer-logo fa fa-instagram" aria-hidden="true"></i></a></p>
            </div>
            <div class="circleBottom">

            </div>
        </div>
        <div class="col-md-7 col-xl-6 overflow-hidden">
            <div>
                <p class="circle"></p>
                <h1 class="charlene mt-5">Charlene</h1>
                <h1 class="font-42 fadirepo font-weight-bold ml-5 pl-4">FADIREPO</h1>
                <p class="about-us-p-width mt-5">founded Guidefi to help women and people of color make
                    better decisions with their money. She started the company
                    out of frustration when she was unable to find a culturally
                    competent and trustworthy financial advisor that was a fit
                    for her multicultural family.</p>
                    <img src="{{asset('assets/images/Line-37.svg')}}" width="40px" class="mb-3 mt-4" alt="">
                <p class="text-gray-actual about-us-p-width-1 mt-4" >
                    Charlene started her career in banking after studying Electrical Engineering at the 
                    University of Virginia. Charlene got her start as a financial analyst at Citigroup and later
                    worked as a tax credit investment underwriter with the Royal Bank of Canada’s Housing
                    Finance Group. She then went on to complete her MBA at the Fuqua School of Business 
                    at Duke University, and graduated in May 2009 following the 2008 financial crisis. For
                    the next 10 years, she focused her career on making the banking industry more safe and
                    inclusive for all American families. She spent 6 years at PwC as a Bank Regulation
                    Consulting Manager, leading projects on Wall Street to support compliance with the
                    Dodd Frank Act. Later she joined the Federal Reserve Board of Governors as a bank 
                    regulation expert and led projects to strengthen consumer protection regulations and
                    promote the safety and soundness of the US banking system.
                </p>
                <p class="text-gray-actual about-us-p-width-1 mt-5">
                    After her 20 year career in banking, she realized that the financial system was broken —
                    and just didn't work for everyone. So she decided to start Guidefi. Launched in 2019,
                    Guidefi has garnered multiple awards including First Place in the In3 Labs Pitch
                    Competition, and admission into the Washington, DC-based SeedSpot Impact
                    Accelerator. Charlene currently teaches financial education to youth through Guidefi's
                    sister organization, GuidefiKids. She is also the author of two children’s books about
                    money, Ayo's Money Jar and Ayo’s Had Enough. Charlene is based in Washington, DC
                    where she lives with her husband, two wonderful children, Sade and Ayo, and a
                    Foxhound named Wuffie.
                </p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 p-0">
            <div class="dif-bg text-align mt-5">
                <h5 class="text-green font-44 font-weight-normal">Get Started On Your Wealth Journey Today</h5>
                <h5 class=" pt-4 pb-5 text-white sign-up-banner">Sign Up For The <br> Waiting List For The Guidefi App</h5>
                <button class="btn btn-download mb-5" data-toggle="modal" data-target=".joinGuidefi">Sign Up</button>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')


@endsection