<?php

use Dingo\Api\Routing\Router;
use Illuminate\Http\Request;
Route::get('stripe', 'StripePaymentController@stripe');
Route::post('stripe', 'StripePaymentController@stripePost')->name('stripe.post');

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Create Dingo Router
$api = app(Router::class);

// Create a Dingo Version Group
$api->version('v1', ['middleware' => 'api'], function ($api) {

    $api->get('tutorials', [
        'as' => 'tutorials',
        'uses' => 'App\\Api\\V1\\Controllers\\TutorialsController@list',
    ]);

    $api->group(['prefix' => 'auth'], function ($api) {

        $api->post('register', [
            'as' => 'register',
            'uses' => 'App\\Api\\V1\\Controllers\\RegisterController@register',
        ]);


        $api->post('login', ['as' => 'login', 'uses' => 'App\\Api\\V1\\Controllers\\LoginController@login']);
        $api->get('truncateData', ['as' => 'truncateData', 'uses' => 'App\\Api\\V1\\Controllers\\LoginController@truncateData']);


        $api->get('logout', [
            'middleware' => 'auth:api',
            'as' => 'logout',
            'uses' => 'App\\Api\\V1\\Controllers\\LogoutController@logout',
        ]);

        $api->post('recovery', [
            'as' => 'password.email',
            'uses' => 'App\\Api\\V1\\Controllers\\ForgotPasswordController@sendResetEmail',
        ]);
        $api->get('verify/{token}', 'App\\Api\\V1\\Controllers\\ForgotPasswordController@verifyString');
        $api->post('resetCustom/{token}', 'App\\Api\\V1\\Controllers\\ResetPasswordController@updatePasswordCustom');
        $api->get('{client}/resetCustom/', 'App\\Api\\V1\\Controllers\\ResetPasswordController@deeplinkingRoute');

        $api->post('reset', 'App\\Api\\V1\\Controllers\\ResetPasswordController@resetPassword');
        $api->post('savepasswordapi', 'App\\Api\\V1\\Controllers\\ResetPasswordController@savepasswordapi');

    });

    // Protected routes



    $api->group(['middleware' => 'auth:api'], function ($api) {
        $api->post('update-first-login-password', 'App\\Api\\V1\\Controllers\\UserController@updateFirstLoginPassword');
        $api->get('truncate-data', 'App\\Api\\V1\\Controllers\\UserController@truncateData');
        $api->get('client-profile', 'App\\Api\\V1\\Controllers\\UserController@getProfile');
        $api->post('update-availability', 'App\\Api\\V1\\Controllers\\UserController@updateAvailability');
        $api->get('resendActivationEmail', 'App\\Api\\V1\\Controllers\\UserController@resendActivation');
        $api->post('update-profile', 'App\\Api\\V1\\Controllers\\UserController@updateProfile');
        $api->post('upload-user-image', 'App\\Api\\V1\\Controllers\\UserController@updateUserImage');
        $api->get('remove-user-image', 'App\\Api\\V1\\Controllers\\UserController@removeUserImage');
        $api->get('bp_questions', 'App\\Api\\V1\\Controllers\\QuestionController@bp_questions');
        $api->get('question', 'App\\Api\\V1\\Controllers\\QuestionController@index');




        // Starting Advisor apis  01/09/2020
        $api->get('connected_advisors', 'App\\Api\\V1\\Controllers\\AdvisorController@connectedAdvisors');
        $api->post('update_connected_advisor', 'App\\Api\\V1\\Controllers\\AdvisorController@updateConnectedAdvisor');
        $api->get('client_offered_advisors', 'App\\Api\\V1\\Controllers\\AdvisorController@clientAdvisors');
        $api->get('discovery_call_status', 'App\\Api\\V1\\Controllers\\AdvisorController@discoveryCallStatus');
        $api->get('advisor_rate_cards', 'App\\Api\\V1\\Controllers\\AdvisorController@AdvisorRateCards');

        $api->post('send_offer_to_client', 'App\\Api\\V1\\Controllers\\AdvisorController@sendOfferToClient');
        $api->post('reject_ratecard_offer', 'App\\Api\\V1\\Controllers\\AdvisorController@rejectOffer');


        $api->get('advisor_profile', 'App\\Api\\V1\\Controllers\\AdvisorController@AdvisorProfile');

        $api->get('advisor_timing', 'App\\Api\\V1\\Controllers\\AdvisorController@AdvisorTimings');
        $api->get('one-time', 'App\\Api\\V1\\Controllers\\AdvisorController@oneTime');

        $api->get('trending-video', 'App\\Api\\V1\\Controllers\\VideoController@trendingVideo');
        $api->get('profile-views', 'App\\Api\\V1\\Controllers\\AdvisorController@profileViews');
        $api->get('analytics-income', 'App\\Api\\V1\\Controllers\\AdvisorController@analyticsIncome');

        $api->post('accept_match', 'App\\Api\\V1\\Controllers\\AdvisorController@acceptMatch');
        $api->post('accept_offer', 'App\\Api\\V1\\Controllers\\ClientController@acceptOffer');

        //contract request process for new and renew
        $api->get('renew-request', 'App\\Api\\V1\\Controllers\\ClientController@renewRequest');

        $api->post('renew-offer', 'App\\Api\\V1\\Controllers\\ClientController@renewOffer');

        $api->get('advisor_offered_clients', 'App\\Api\\V1\\Controllers\\AdvisorController@advisorsOfferedClients');

        $api->post('book-appointment', 'App\\Api\\V1\\Controllers\\AdvisorController@book_appointment');


        $api->get('get-appt-timing', 'App\\Api\\V1\\Controllers\\AppointmentController@getApptTiming');


        // Ending Advisor apis  01/09/2020



        /*
      * Starting new appointment system
      * */

        $api->get('get-timing-temp', 'App\\Api\\V1\\Controllers\\AdvisorController@gettimingTemp');
        $api->get('get-time-slot', 'App\\Api\\V1\\Controllers\\AdvisorController@getTimeSlotsNew');
        $api->post('save-advisor-availability', 'App\\Api\\V1\\Controllers\\AdvisorController@saveAdvisorAvailability');
        $api->get('savetimeSlots', 'App\\Api\\V1\\Controllers\\AdvisorController@savetimeSlots');
        $api->get('get-active-timeslots', 'App\\Api\\V1\\Controllers\\AdvisorController@getActiveTimeslots'); // Therapist will view all his settings.


        $api->get('get-advisor-time-slots', 'App\\Api\\V1\\Controllers\\AdvisorController@getAdvisorTimeSlot');


        $api->get('my_advisors', 'App\\Api\\V1\\Controllers\\ClientController@myAdvisors'); // client will check his contracted advisors
        $api->get('my_clients', 'App\\Api\\V1\\Controllers\\AdvisorController@myClients'); // advisor will check his contracted clients

        $api->get('payment-history', 'App\\Api\\V1\\Controllers\\PaymentController@paymentHistory'); // advisor will check his contracted clients


        $api->post('client-feedback', 'App\\Api\\V1\\Controllers\\FeedbackController@clientFeedback');
        $api->post('advisor-feedback', 'App\\Api\\V1\\Controllers\\FeedbackController@advisorFeedback');
        $api->get('get-feedback', 'App\\Api\\V1\\Controllers\\FeedbackController@getFeedback');

        /*
        * Starting new appointment system
        * */





        $api->get('ratecard-questions', 'App\\Api\\V1\\Controllers\\QuestionController@ratecardquestion');
        $api->get('get-question-by-user/{id}', 'App\\Api\\V1\\Controllers\\QuestionController@index');
        $api->post('answers', 'App\\Api\\V1\\Controllers\\AnswerController@store');
        $api->post('rate-card', 'App\\Api\\V1\\Controllers\\AnswerController@saveRateCardAnswers');
        $api->get('get-matched-therapist', 'App\\Api\\V1\\Controllers\\TherapistController@index');
//        $api->post('save-selected-therapist', 'App\\Api\\V1\\Controllers\\ClientSelectedTherapistController@save_selected_therapist');
//        $api->post('change-selected-therapist', 'App\\Api\\V1\\Controllers\\ClientSelectedTherapistController@change_selected_therapist');
//        $api->get('get-selected-therapist', 'App\\Api\\V1\\Controllers\\ClientSelectedTherapistController@get_selected_therapist');
        $api->get('get-subscription-plans', 'App\\Api\\V1\\Controllers\\PackageController@index');
        $api->post('save-subscription-plans', 'App\\Api\\V1\\Controllers\\PackageController@store');
        $api->get('get-current-subscribed-plan', 'App\\Api\\V1\\Controllers\\PackageController@get_client_subscribed_plan');
        $api->get('get-client-subscribed-plan', 'App\\Api\\V1\\Controllers\\PackageController@get_therapist_subscribed_plan');
        $api->post('save-appointment', 'App\\Api\\V1\\Controllers\\TherapistController@save_appointment');
        $api->post('change-appointment-type', 'App\\Api\\V1\\Controllers\\TherapistController@changeAppointmentType');
        $api->post('cancel-appointment', 'App\\Api\\V1\\Controllers\\TherapistController@cancelAppointment');

        $api->get('cancel-subscription', 'App\\Api\\V1\\Controllers\\TherapistController@cancelSubscription');


        /*
         * 2nd Phase apis. We are starting from the 25/10/20
         * */
        $api->get('dashboard', 'App\\Api\\V1\\Controllers\\DashboardController@index');
//        $api->get('test', 'App\\Api\\V1\\Controllers\\DashboardController@test');


        $api->post('upload-image', 'App\\Api\\V1\\Controllers\\UploadController@uploadImage');
        $api->post('upload-file', 'App\\Api\\V1\\Controllers\\DashboardController@uploadFile');


        $api->get('get_all_clients', 'App\\Api\\V1\\Controllers\\ClientRequestController@allClients');
        $api->get('get_client/{id}', 'App\\Api\\V1\\Controllers\\ClientRequestController@getClient');

        $api->get('get_all_potential_clients', 'App\\Api\\V1\\Controllers\\ClientRequestController@allPotentialClients');
        $api->post('set_default_rate_card', 'App\\Api\\V1\\Controllers\\RateCardController@setDefaultRateCard');
        $api->post('delete_rate_card', 'App\\Api\\V1\\Controllers\\RateCardController@deleteRateCard');

        $api->get('get-rate-card-offers', 'App\\Api\\V1\\Controllers\\RateCardController@fetchallOffers');


        $api->post('test-notification', 'App\\Api\\V1\\Controllers\\TherapistController@testNotification');

        $api->post('save-contact-us', 'App\\Api\\V1\\Controllers\\ContactUsController@store');

        $api->get('get-therapist_rating', 'App\\Api\\V1\\Controllers\\TherapistRatingReviewsController@index');
        $api->post('save-therapist_rating', 'App\\Api\\V1\\Controllers\\TherapistRatingReviewsController@store');

//        $api->get('get-clients', 'App\\Api\\V1\\Controllers\\ClientSelectedTherapistController@getClients');
//        $api->post('save-selected-therapist', 'App\\Api\\V1\\Controllers\\ClientSelectedTherapistController@save_selected_therapist');

        $api->get('get-matched-therapist', 'App\\Api\\V1\\Controllers\\TherapistController@index');

        $api->get('get-therapist-appointments', 'App\\Api\\V1\\Controllers\\TherapistController@getTherapistAppointments');
        $api->get('get-client-appointments', 'App\\Api\\V1\\Controllers\\TherapistController@getClientAppointments');

        $api->get('get-notes', 'App\\Api\\V1\\Controllers\\TherapistRatingReviewsController@getClientNotes');
        $api->post('save-note', 'App\\Api\\V1\\Controllers\\TherapistRatingReviewsController@saveClientNotes');

        $api->get('get-all-invoices', 'App\\Api\\V1\\Controllers\\InvoicesController@index');

        $api->post('save-client-mood', 'App\\Api\\V1\\Controllers\\JournalController@saveMood');
        $api->post('update-client-mood', 'App\\Api\\V1\\Controllers\\JournalController@updateMood');
        $api->get('get-all-client-mood', 'App\\Api\\V1\\Controllers\\JournalController@index');

        $api->get('get-faq', 'App\\Api\\V1\\Controllers\\FaqController@index');

        $api->post('change-card', 'App\\Api\\V1\\Controllers\\PaymentController@changeCard');
        $api->get('get-all-card', 'App\\Api\\V1\\Controllers\\PaymentController@getAllCard');
        $api->post('change-plan', 'App\\Api\\V1\\Controllers\\PaymentController@changePlan');
        $api->post('set-default-card', 'App\\Api\\V1\\Controllers\\PaymentController@setDefaultCard');
        $api->post('delete-default-card', 'App\\Api\\V1\\Controllers\\PaymentController@deleteCard');

        $api->post('save-call-logs', 'App\\Api\\V1\\Controllers\\CallLogsController@store');
        $api->get('get-call-logs', 'App\\Api\\V1\\Controllers\\CallLogsController@index');

        $api->post('share-feedback-with-therapist', 'App\\Api\\V1\\Controllers\\ShareFeedbackAndJournalController@shareFeedback');
        $api->get('get-shared-feedback-with-therapist', 'App\\Api\\V1\\Controllers\\ShareFeedbackAndJournalController@getSharedFeedback');

        $api->post('share-journal-with-therapist', 'App\\Api\\V1\\Controllers\\ShareFeedbackAndJournalController@shareJournals');
        $api->post('unshare-journal-and-feedback', 'App\\Api\\V1\\Controllers\\ShareFeedbackAndJournalController@unshare');
        $api->get('get-shared-journal-with-therapist', 'App\\Api\\V1\\Controllers\\ShareFeedbackAndJournalController@getSharedJournals');

        $api->get('users', [
            'as' => 'users.index',
            'uses' => 'App\\Api\\V1\\Controllers\\UserController@index',
        ]);

        $api->get('users/{user}', [
            'as' => 'users.show',
            'uses' => 'App\\Api\\V1\\Controllers\\UserController@show',
        ]);
        /*** Advisor Api*/
        $api->post('upload-video', 'App\\Api\\V1\\Controllers\\VideoController@store');
        $api->post('manage-status', 'App\\Api\\V1\\Controllers\\VideoController@manageStatus');
        $api->post('edit-title', 'App\\Api\\V1\\Controllers\\VideoController@editTitle');

        $api->get('my-publish-videos', 'App\\Api\\V1\\Controllers\\VideoController@myPublishVideos');
        $api->get('my-private-videos', 'App\\Api\\V1\\Controllers\\VideoController@myPrivateVideos');

        $api->get('my-publish-quotes', 'App\\Api\\V1\\Controllers\\QuoteController@myPublishQuotes');
        $api->get('my-private-quotes', 'App\\Api\\V1\\Controllers\\QuoteController@myPrivateQuotes');
        $api->post('quote-store', 'App\\Api\\V1\\Controllers\\QuoteController@store');
        $api->post('quote-edit', 'App\\Api\\V1\\Controllers\\QuoteController@editQuote');
        $api->get('my-quotes/{status?}', 'App\\Api\\V1\\Controllers\\QuoteController@myQuotes');

        /*** Client Api*/
        $api->get('my_favourite_video', 'App\\Api\\V1\\Controllers\\VideoController@myFavouriteVideo');
        $api->get('my_watch_later_video', 'App\\Api\\V1\\Controllers\\VideoController@myWatchLaterVideo');
        $api->get('my_favourite_quote', 'App\\Api\\V1\\Controllers\\QuoteController@myFavouriteQuote');

        $api->post('favourite', 'App\\Api\\V1\\Controllers\\DashboardController@favourite');
        $api->post('video-view', 'App\\Api\\V1\\Controllers\\VideoController@videoView');




        $api->get('all-notes', 'App\\Api\\V1\\Controllers\\NoteController@index');
        $api->post('new-note', 'App\\Api\\V1\\Controllers\\NoteController@store');
        $api->post('update-note', 'App\\Api\\V1\\Controllers\\NoteController@update');
        $api->post('delete-note', 'App\\Api\\V1\\Controllers\\NoteController@destroy');

        $api->get('my-notification','App\\Api\\V1\\Controllers\\NotificationController@index');
        $api->post('delete-notification','App\\Api\\V1\\Controllers\\NotificationController@destroy');

        $api->post('change-password', 'App\\Api\\V1\\Controllers\\ResetPasswordController@changePassword');
        $api->post('term-and-condition', 'App\\Api\\V1\\Controllers\\UserController@termAndCondition');



    });

    $api->group([],function ($api){

    });

    $api->post('register-user', [
        'as' => 'register',
        'uses' => 'App\\Api\\V1\\Controllers\\RegisterController@registerUser',
    ]);


});
