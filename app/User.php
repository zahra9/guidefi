<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use App\Traits\Method\UserMethod;
use Illuminate\Notifications\Notifiable;
use App\Traits\Attribute\UserAttribute;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use UserMethod,
        HasRoleAndPermission,
        Notifiable,
        HasApiTokens,
        UserAttribute;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'activated',
        'token',
        'image_url',
        'chat_id',
        'chat_email',
        'is_chat_enabled',
        'is_questionare_completed',
        'login_after_register'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'activated',
        'token',
    ];
    use SoftDeletes; //add this line
    public function canManageBlogEtcPosts(){
        return $this->id === 1;
    }
    protected $dates = [
        'deleted_at',
    ];

    /**
     * Automatically creates hash for the user password.
     *
     * @param  string  $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function answers()
    {
        return $this->hasMany('App\Answers', 'user_id');
    }

    public function person()
    {
        // This table has been removed.
        return $this->hasOne('App\Person');
    }

    public function clientRequest()
    {
        // This table has been removed.
        return $this->hasOne('App\Clientrequest');
    }
    public function advisorClient()
    {
        // This table has been removed.
        return $this->hasOne('App\Models\AdvisorClientType');
    }

    /*
     * This is a Request for the advisor
     * */
    public function advisorrequest()
    {
        // This table has been removed.
        return $this->hasOne('App\Advisorrequest');
    }

    public function bpanswers()
    {
        return $this->hasMany('App\BpAnswers', 'business_profile_id');
    }

    public function therapist()
    {
        return $this->hasOne('App\Therapist','user_id');
    }
    public function corporateclient()
    {
        return $this->hasOne('App\CorporateClient','user_id');
    }
    /**
     * Build Social Relationships.
     *
     * @var array
     */
    public function social()
    {
        return $this->hasMany('App\Models\Social');
    }

    /**
     * Client Appointment Relationships.
     *
     * @var array
     */
    public function clientAppointment()
    {
        return $this->belongsTo('App\AdvisorClientAppointment','client_id');
    }


    /**
     * Advisor Appointment Relationships.
     *
     * @var array
     */
    public function advisorAppointment()
    {
        return $this->belongsTo('App\AdvisorClientAppointment','advisor_id');
    }


    public function profile()
    {
        return $this->hasOne('App\Models\Profile');
    }


    public function AdvisorClientAppointment()
    {
        return $this->hasMany('App\AdvisorClientAppointment','advisor_id');
    }

    // User Profile Setup - SHould move these to a trait or interface...

    public function profiles()
    {
        return $this->belongsToMany('App\Models\Profile')->withTimestamps();
    }

    public function hasProfile($name)
    {
        foreach ($this->profiles as $profile) {
            if ($profile->name == $name) {
                return true;
            }
        }

        return false;
    }

    public function assignProfile($profile)
    {
        return $this->profiles()->attach($profile);
    }

    public function removeProfile($profile)
    {
        return $this->profiles()->detach($profile);
    }

    public function selectedTherapists()
    {
        return $this->hasMany('App\ClientSelectedTherapist');
    }

    public function subscribedPlans()
    {
        return $this->belongsToMany('App\Package','client_subscription_plan')->withPivot('active')->withTimestamps();
    }

    public function clientJournals()
    {
        return $this->hasMany('App\ClientJournal','client_id');
    }

    public function cards()
    {
        return $this->hasMany('App\Cards','client_id');
    }

    public function payments()
    {
        return $this->hasMany('App\Payment','user_id');
    }

    public function clientCallLogs()
    {
        return $this->hasMany('App\DiscoverCallLogs','client_id');
    }


    public function advisorCallLogs()
    {
        return $this->hasMany('App\DiscoverCallLogs','advisor_id');
    }
    public function invoicedetails()
    {
        return $this->hasMany('App\CorporateClientInvoiceDetail','user_id');
    }

    public function shareFeedback()
    {
        return $this->belongsToMany('App\clientFeedbackFromTherapist','clients_shared_feedback_and_journals_with_therapist','client_id','feedback_id')->withPivot('created_at');
    }

    public function shareJournals()
    {
        return $this->belongsToMany('App\ClientJournal','clients_shared_feedback_and_journals_with_therapist','client_id','journal_id')->withPivot('created_at');
    }
    public function corporateclientprepaidpayments()
    {
        return $this->hasMany('App\CorporateClientPrepaidPayment','user_id');
    }

    public function notes()
    {
        return $this->hasMany('App\Note','client_id');
    }

    public function feedbackdealfinishs()
    {
        return $this->hasMany('App\FeedbackDealFinish');
    }

    public function feedbackdealfinishesClient()
    {
        return $this->hasMany('App\FeedbackDealFinish','client_id');
    }
    public function feedbackdealfinishesAdvisor()
    {
        return $this->hasMany('App\FeedbackDealFinish','advisor_id');
    }
    //relation with user
    public function notifications()
    {
        return $this->hasMany('App\Models\Notification','sender_id');
    }

    public function stripePaymentLogs()
    {
        return $this->hasMany('App\StripePaymentLog','advisor_id');
    }

}
