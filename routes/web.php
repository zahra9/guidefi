<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
| Middleware options can be located in `app/Http/Kernel.php`
|
 */
// Homepage Route
//Route::get('/', 'WelcomeController@welcome')->name('welcome');

// Authentication Routes










Route::get('test-discovery',function() {

    $discoveryCalls = \App\DiscoverCallLogs::with('client.person')->with('advisor.person')->
    where('email_notify',0)->
    selectRaw('TIMEDIFF(end_time, start_time) as total_minutes,client_id,advisor_id,id')->get();

    $groupSameEntity = $discoveryCalls ->groupBy('client_id','advisor_id');
    foreach ($groupSameEntity as $discoveryCall){
        $sum = strtotime('00:00:00');
        $sum2 = 0;
        foreach ($discoveryCall as $v){
            $sum1= strtotime($v->total_minutes)-$sum;
            $sum2 = $sum2+$sum1;
            \App\DiscoverCallLogs::whereId($v->id)->update(['email_notify'=>1]);
        }
        $sum3 = $sum + $sum2;

        // EMAIL TRIGGER after client confirmation of advisor selection
        $textAdvisor = "Your discovery call is done. Now you can send an offer to client(".$discoveryCall[0]->client->name.")<br>
                For technical difficulties, please email<br><br>";
        $textClient = "Discovery call is done with advisor(".$discoveryCall[0]->advisor->name. "). Total Numbers of mintues are: ".date("H:i:s",$sum3)."<br>
                For technical difficulties, please email<br><br>";

        \App\Repositories\EmailManager::sendMailQueue('Discovery Call Completed',$textClient , $discoveryCall[0]->client->name, $discoveryCall[0]->client->email);
        \App\Repositories\EmailManager::sendMailQueue('Discovery Call Completed', $textAdvisor, $discoveryCall[0]->advisor->name, $discoveryCall[0]->advisor->email);

        // NOTIFICATION TRIGGER after client confirmation of advisor selection
        $fcmController = new \App\Api\V1\Helpers\FcmController(env('FCM_SERVER_KEY'));
        $fcmController->pushNotification('Discovery Call Completed', $textAdvisor, 'DiscoveryCall', $discoveryCall[0]->advisor_id);
        $fcmController->pushNotification('Discovery Call Completed', $textClient, 'DiscoveryCall', $discoveryCall[0]->client_id);

    }
});

Auth::routes();

Route::get('test-pdf',function(){
    set_time_limit(0);
            $data = collect([
                'total_users'=>10,
                'paid_users'=>10,
                'unpaid_user'=>10,
                'total_clients'=>10,
                'total_advisors'=>10,
                'today_profit'=>10,
                'this_week_profit'=>10,
                'this_month_profit'=>10,
                'this_year_profit'=>10,
                'this_year_revenue'=>10,
                'this_month_revenue'=>10,
                'competed_discovery_call'=> 10,
                'waiting_discovery_call'=>10,
            ]);
    return view('pdf_view',compact('data'));

});
// Public Resource Route
//Route::get('material.min.css.template', 'ThemesManagementController@template');

//Route::get('regenerate-role', function (){
//    $users = \App\User::get();
//
//    foreach ($users as $user){
//        if(!is_null($user->therapist)){
//            $user->attachRole(5);
//        }else{
//            $user->attachRole(3);
//        }
//    }
//});

// Public Routes
Route::group(['middleware' => 'web'], function () {



    Route::get('/update_advisor_status/{id}', ['as' => 'update_advisor_status', 'uses' => 'AdvisorRequestsController@updateAdvisorStatus']);
    Route::get('/update_client_status/{id}', ['as' => 'update_client_status', 'uses' => 'ClientRequestsController@updateClientStatus']);
    Route::get('/client-list', ['as' => 'client-list', 'uses' => 'ClientRequestsController@ClientList']);
    Route::get('/client-detail/{id?}', ['as' => 'client-detail', 'uses' => 'ClientRequestsController@ClientDetail']);
    Route::get('/client-requests', ['as' => 'client-requests', 'uses' => 'ClientRequestsController@index']);
    Route::get('/payment-logs', ['as' => 'payment-logs', 'uses' => 'StripePaymentLogController@paymentLogs']);

    ////////////////////////////
    // Naveed created routes////
    ////////////////////////////

    Route::get('/setting-list', ['as' => 'setting-list', 'uses' => 'FactorSettingController@index']);
    Route::post('/setting/store', ['as' => 'setting-store', 'uses' => 'FactorSettingController@store']);
    Route::get('/setting/edit/{id}', ['as' => 'setting-edit', 'uses' => 'FactorSettingController@edit']);
    Route::post('/setting/update/{id}', ['as' => 'setting-update', 'uses' => 'FactorSettingController@update']);
    Route::get('setting/delete/{id}', ['as' => 'setting-destroy', 'uses' => 'FactorSettingController@destroy']);

    Route::post('/home/export', 'FactorSettingController@export')->name('admin.home.export');
    Route::get('/advisor-details/{id}', ['as' => 'advisor-detail', 'uses' => 'AdvisorRequestsController@detail']);
    Route::get('/client-details/{id}', ['as' => 'client-detail', 'uses' => 'ClientRequestsController@detail']);

    Route::get('/call-list', ['as' => 'call-logs', 'uses' => 'CallLogController@index']);

    ////////////////////////////
    // Blog customized routes////
    ////////////////////////////

    Route::post('/post-store', ['as' => 'post-store', 'uses' => 'PostController@store']);
    Route::post('/blog-images-store', ['as' => 'blog-images-store', 'uses' => 'PostController@blogImageStore']);
//    Route::patch('/edit_post/{blogPostId}',['as' => 'blog-update', 'uses' =>  'PostController@update']);
    Route::patch('/update-post/{blogPostId}',['as' => 'update-post', 'uses' => 'PostController@update']);

    Route::group(['prefix' => 'blog_admin'], static function () {

    Route::get('/', 'PostController@index')->name('blogetc.admin.index');
    Route::get('/{blogPostSlug}', 'PostController@show')->name('blogetc.single');
    Route::get('/add_post', 'PostController@create')->name('blogetc.admin.create_post');
    Route::get('/edit_post/{blogPostId}', 'PostController@edit')->name('blogetc.admin.edit_post');
    Route::get('/{blogPostSlug}', 'PostController@show')->name('blogetc.single');
    Route::delete('/delete_post/{blogPostId}', 'PostController@destroy')->name('blogetc.admin.destroy_post');

    Route::group(['prefix' => 'comments'], static function () {
        Route::get('/', 'ManageCommentsController@index')->name('blogetc.admin.comments.index');
        Route::patch('/{commentId}', 'ManageCommentsController@approve')->name('blogetc.admin.comments.approve');
        Route::delete('/{commentId}', 'ManageCommentsController@destroy')->name('blogetc.admin.comments.delete');
    });

        Route::group(['prefix' => 'categories'], static function () {
            Route::get('/', 'ManageCategoriesController@index')->name('blogetc.admin.categories.index');
            Route::get('/add_category', 'ManageCategoriesController@create')->name('blogetc.admin.categories.create_category');
            Route::post('/add_category', 'ManageCategoriesController@store')->name('blogetc.admin.categories.store_category');
            Route::get('/edit_category/{categoryId}', 'ManageCategoriesController@edit')->name('blogetc.admin.categories.edit_category');
            Route::patch('/edit_category/{categoryId}', 'ManageCategoriesController@update')->name('blogetc.admin.categories.update_category');
            Route::delete('/delete_category/{categoryId}', 'ManageCategoriesController@destroy')->name('blogetc.admin.categories.destroy_category');
        });

        /////////////////////////////////////////////
        //images upload (view) blog routes //
        ///////////////////////////////////////////
        Route::group(['prefix' => 'image_uploads'], static function () {
            Route::get('/', 'ManageUploadsController@index')->name('blogetc.admin.images.all');
            Route::get('/upload', 'ManageUploadsController@create')->name('blogetc.admin.images.upload');
            Route::post('/upload', 'ManageUploadsController@store')->name('blogetc.admin.images.store');

            Route::get('/post/{postId}/delete-images', 'ManageUploadsController@deletePostImage')->name('blogetc.admin.images.delete-post-image');
            Route::delete('/post/{postId}/delete-images', 'ManageUploadsController@deletePostImageConfirmed')->name('blogetc.admin.images.delete-post-image-confirmed');
        });
    });

    //////////////////////////////
    //Naveed created routes end//
    ////////////////////////////



    Route::get('about-us',  'AboutUsController@about')->name('about-us');


    Route::get('faq',  'AboutUsController@faq')->name('faq');
    Route::get('community',  'AboutUsController@community')->name('community');
    Route::get('our-commitment',  'AboutUsController@ourCommitment')->name('our-commitment');




    // Activation Routes
    Route::get('/advisor-list', ['as' => 'advisor-list', 'uses' => 'AdvisorRequestsController@AdvisorList']);
    Route::get('/advisor-list-detail/{id?}', ['as' => 'advisor-list', 'uses' => 'AdvisorRequestsController@advisorListDetail']);
    Route::get('/advisor-requests', ['as' => 'advisor-requests', 'uses' => 'AdvisorRequestsController@index']);

    Route::get('/activate', ['as' => 'activate', 'uses' => 'Auth\ActivateController@initial']);

    Route::get('/activate/{token}', ['as' => 'authenticated.activate', 'uses' => 'Auth\ActivateController@activate']);
    Route::get('/activation', ['as'       => 'authenticated.activation-resend', 'uses'       => 'Auth\ActivateController@resend']);
    Route::get('/exceeded', ['as'         => 'exceeded', 'uses'         => 'Auth\ActivateController@exceeded']);

    // Socialite Register Routes
    Route::get('/social/redirect/{provider}', ['as' => 'social.redirect', 'uses' => 'Auth\SocialController@getSocialRedirect']);
    Route::get('/social/handle/{provider}', ['as'   => 'social.handle', 'uses'   => 'Aupassword.requestth\SocialController@getSocialHandle']);

    // Route to for user to reactivate their user deleted account.
    Route::get('/re-activate/{token}', ['as' => 'user.reactivate', 'uses' => 'RestoreUserController@userReActivate']);
    Route::resource('therapistrequest', 'TherapistrequestController');
    Route::get('/resetpassword', ['as' => 'resetpassword', 'uses' => 'Auth\ResetPasswordController@index']);
    Route::get('/resetpasswordapi/{token}', ['as' => 'resetpasswordapi', 'uses' => 'Auth\ResetPasswordController@resetpasswordapi']);
//    Route::post('/savepasswordapi', ['as' => 'resetpasswordapi', 'uses' => 'Auth\ResetPasswordController@savepasswordapi']);
    Route::get('/resetlink/{token}', ['as' => 'resetlink', 'uses' => 'Auth\ResetPasswordController@resetlink']);
    Route::get('/activateclient/{id}', ['as' => 'showapplinks', 'uses' => 'Auth\RegisterController@activateclient']);
    Route::get('/showclientapplink', ['as' => 'showapplinks', 'uses' => 'Auth\RegisterController@showclientapplink']);
    Route::get('/showapplinks', ['as' => 'showapplinks', 'uses' => 'Auth\ResetPasswordController@showapplinks']);
    Route::post('/emailverfication', ['as' => 'emailverfication', 'uses' => 'Auth\ResetPasswordController@emailverification']);
    Route::post('/savepassword', ['as' => 'savepassword', 'uses' => 'Auth\ResetPasswordController@savepassword']);
    Route::post('/savepasswordapi', ['as' => 'savepassword', 'uses' => 'Auth\ResetPasswordController@savepasswordapi']);
    Route::get('newuser/{id}', ['uses' => 'RestoreUserController@newuserrequest']);
    Route::post('/saveemployee/', ['uses' => 'RestoreUserController@saveemployee']);
    Route::post('/saveemployeewithoutdomian/', ['uses' => 'RestoreUserController@saveEmployeeWithoutDomain']);


    Route::post('upload-user-image', 'UserController@updateUserImage');
    Route::post('update-bio', 'UserController@updateProfile');


    ////////////////////////////
    // Noman created routes////
    ////////////////////////////
    Route::post('/connect/user', ['as' => 'connect-user', 'uses' => 'FactorSettingController@connactUser']);
    Route::get('/signup-request', ['as' => 'signup-request', 'uses' => 'FactorSettingController@signUpRequest']);
    Route::get('/feedback-list', ['as' => 'signup-request', 'uses' => 'FactorSettingController@feedbackList']);
    Route::resource('/notifications', 'NotificationController');

    Route::get('term-conditions', 'TermConditionController@index');
    Route::get('user-agreement-advisor', 'TermConditionController@userAgreementAdvisor');
    Route::get('user-agreement-client', 'TermConditionController@userAgreementClient');

    Route::get('questions-to-ask', 'TermConditionController@questionsToAsk');

});

// Registered and Activated User Routes
Route::group(['middleware' => ['auth', 'activated']], function () {
    // Homepage Route
    Route::get('/', ['as' => 'public.home', 'uses' => 'UserController@index']);

    // Activation Routes
    Route::get('/activation-required', ['uses' => 'Auth\ActivateController@activationRequired'])->name('activation-required');
    Route::get('/logout', ['uses'  => 'Auth\LoginController@logout'])->name('logout');

    //  Homepage Route - Redirect based on user role is in controller.
    Route::get('/home', ['as' => 'public.home', 'uses' => 'UserController@index']);

    // Show users profile - viewable by other users.
    Route::get('profile/{username}', [
        'as'   => '{username}',
        'uses' => 'ProfilesController@show',
    ]);

    // Route to show user avatar
    Route::get('images/profile/{id}/avatar/{image}', [
        'uses' => 'ProfilesController@userProfileAvatar',
    ]);

    // Route for user profile background image
    Route::get('images/profile/{id}/background/{image}', [
        'uses'         => 'ProfilesController@userProfileBackgroundImage',
    ]);
});

// Registered, activated, and is current user routes.
Route::group(['middleware' => ['auth', 'activated', 'currentUser']], function () {
    // User Profile and Account Routes
    Route::resource(
        'profile',
        'ProfilesController',
        [
            'only' => [
                'account',
                'show',
                'edit',
                'update',
                'create',
            ],
        ]
    );
    Route::put('profile/{username}/updateUserAccount', [
        'as'   => '{username}',
        'uses' => 'ProfilesController@updateUserAccount',
    ]);
    Route::put('profile/{username}/updateUserPassword', [
        'as'   => '{username}',
        'uses' => 'ProfilesController@updateUserPassword',
    ]);
    Route::delete('profile/{username}/deleteUserAccount', [
        'as'   => '{username}',
        'uses' => 'ProfilesController@deleteUserAccount',
    ]);

    // Route for user profile background image
    Route::get('account', [
        'as'       => '{username}',
        'uses'     => 'ProfilesController@account',
    ]);

    // Update User Profile Ajax Route
    Route::post('profile/{username}/updateAjax', [
        'as'   => '{username}',
        'uses' => 'ProfilesController@update',
    ]);

    // Route to upload user avatar.
    Route::post('avatar/upload', ['as' => 'avatar.upload', 'uses' => 'ProfilesController@upload']);

    // Route to uplaod user background image
    Route::post('background/upload', ['as' => 'background.upload', 'uses' => 'ProfilesController@uploadBackground']);

    // User Tasks Routes
    Route::resource('/tasks', 'TasksController');
    Route::get('payment', 'StripePaymentController@stripe');
});

// Corporate level routes.

Route::group(['middleware' => ['auth', 'activated', 'role:corporate']], function () {

    Route::resource('clients', 'CorporateUsersManagementController', [
        'names'    => [
            'index'   => 'clients',
            'create'  => 'create',
            'destroy' => 'client.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);
    Route::resource('settings', 'SettingController', [
        'names'    => [
            'index'   => 'clients',
            'create'  => 'create',
            'destroy' => 'client.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);
    Route::post('inviteemployee', ['uses' => 'UserController@inviteemployee']);
    Route::post('inviteusers', ['uses' => 'UserController@inviteusers']);
    Route::post('bulkimport', ['uses' => 'UserController@bulkimport']);

    Route::get('/createcard',  'SettingController@createcard');
    Route::post('savecard',  'SettingController@savecard');
    Route::get('/editcard/{id}',  'SettingController@editcard');
    Route::post('/updatecard',  'SettingController@updatecard');
    Route::post('settings/makedefault',  'SettingController@makedefault');
    Route::post('clients/blockit',  'CorporateUsersManagementController@blockit');
    Route::get('/importcsv',  'CorporateUsersManagementController@importcsv');
    Route::post('savecsvfile',  'CorporateUsersManagementController@savecsvfile');
    Route::post('savecsv',  'CorporateUsersManagementController@savecsv');
    Route::get('/clients/sendemail/{id}',  'CorporateUsersManagementController@sendemail');
    Route::get('userpayment',  'CorporateUsersManagementController@userpayment');
    Route::get('makebilling',  'CorporateUsersManagementController@makebilling');
    Route::post('settings/billingaddress',  'SettingController@billingaddress');
});

// Registered, activated, and is admin routes.
Route::group(['middleware' => ['auth', 'activated', 'role:admin']], function () {
    Route::resource('/users/deleted', 'SoftDeletesController', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ]);
    Route::resource('users', 'UsersManagementController', [
        'names'    => [
            'index'   => 'users',
            'create'  => 'create',
            'destroy' => 'user.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);
    Route::get('/users/confirmuser/{id}',  'UsersManagementController@confirmuser');
    Route::get('/create/stripe/plans/',  'UsersManagementController@createPlans');
    Route::post('invite/users',  'CorporateClientController@inviteusers');

    Route::resource('corporateusers', 'CorporateClientController', [
        'names'    => [
            'index'   => 'corporateusers',
            'create'  => 'create',
            'destroy' => 'corporateuser.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);
    Route::resource('/therapists/deleted', 'ThSoftDeletesController', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ]);
    Route::resource('therapists', 'TherapistController', [
        'names'    => [
            'index'   => 'therapits',
            'create'  => 'create',
            'destroy' => 'therapist.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);
    Route::post('/therapists',  'TherapistController@index');
    Route::post('/users',  'UsersManagementController@index');
    Route::post('/addpayment',  'CorporateClientController@addpayment');
    Route::post('/generatecode',  'CorporateClientController@generatecode');
    Route::get('therapistrequests', 'TherapistrequestController@showlist');
    Route::get('testemail',  'TherapistrequestController@testemail');
    Route::get('therapistrequests/createuser/{id}', 'TherapistrequestController@adduser');
    Route::get('therapistrequests/accepttherapist/{id}', 'TherapistrequestController@accepttherapist');
    Route::get('therapistrequests/rejecttherapist/{id}', 'TherapistrequestController@rejecttherapist');
    Route::get('therapistrequests/showuser/{id}', 'TherapistrequestController@showuser');
    Route::post('appointments/filter', 'AppointmentController@index');
    Route::post('clients/blockit',  'CorporateUsersManagementController@blockit');
    Route::get('/clients/sendemail/{id}',  'CorporateUsersManagementController@sendemail');
    Route::get('/importcsv',  'CorporateUsersManagementController@importcsv');
    Route::get('dowloadcsvcodes/{id}',  'CorporateClientController@dowloadcsvcodes');
    Route::post('savecsvfile',  'CorporateUsersManagementController@savecsv');
    Route::post('adminsavecsv',  'CorporateUsersManagementController@adminsavecsv');
    Route::post('admininviteemployee', ['uses' => 'UserController@inviteemployee']);
    Route::resource('appointments', 'AppointmentController', [
        'names'    => [
            'index'   => 'appointments',
            'create'  => 'create',
            'destroy' => 'appointment.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);
    Route::resource('/packages/deleted', 'PkSoftDeletesController', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ]);
    Route::resource('packages', 'PackageController', [
        'names'    => [
            'index'   => 'therapists',
            'create'  => 'create',
            'destroy' => 'package.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);
    Route::post('invoices/filter', 'StripePaymentLogController@index');

    Route::resource('invoices', 'StripePaymentLogController', [
        'names'    => [
            'index'   => 'therapists',
            'create'  => 'create',
            'destroy' => 'package.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);
    Route::resource('themes', 'ThemesManagementController', [
        'names'    => [
            'index'   => 'themes',
            'destroy' => 'themes.destroy',
        ],
    ]);

    Route::post('blockit',  'UsersManagementController@blockit');
    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
    Route::get('php', 'AdminDetailsController@listPHPInfo');
    Route::get('routes', 'AdminDetailsController@listRoutes');
    Route::resource('discovery-call-logs', 'DiscoveryCallLogsController');
    Route::resource('advisor-client-type', 'AdvisorClientTypeController');
    Route::resource('videos', 'VideoController');
    Route::resource('quotes', 'QuoteController');
    Route::resource('commissions', 'CommissionController');
    Route::get('/update_video_status/{id}', ['as' => 'update_video_status', 'uses' => 'VideoController@updateVideoStatus']);
    Route::post('/update_quote_status/{id}', ['as' => 'update_quote_status', 'uses' => 'QuoteController@updateQuoteStatus']);
    Route::get('/update_commission_status/{id}', ['as' => 'update_commission_status', 'uses' => 'CommissionController@updateCommissionStatus']);
    Route::get('/truncateData', [ 'uses' => 'UserController@truncateData'])->name('truncateData');



    Route::get('view-post/{slug}', 'WelcomeController@viewPost')->name('view-post');

});
