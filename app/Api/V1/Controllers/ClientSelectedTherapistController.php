<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Helpers\FcmController;
use App\Api\V1\Serializers\NoDataArraySerializer;
use App\Api\V1\Transformers\SelectedTherapistTransformer;
use App\Cards;
use App\clientFeedbackFromTherapist;
use App\ClientJournal;
use App\ClientSelectedAdvisor;
use App\ClientSelectedTherapist;
use App\Http\Controllers\Controller;
use App\Mail\TherapistSubscribedMailPaymentFailed;
use App\Mail\TherapistSubscribedMailPaymentSuccess;
use App\Payment;
use App\Therapist;
use App\User;
use App\Package;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use Stripe\Card;

class ClientSelectedTherapistController extends Controller
{


}
