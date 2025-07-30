<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CorporateClientInvoiceDetail extends Model
{
    protected $guarded = [''];
    use SoftDeletes; //add this line
    protected $table = 'corp_client_invoices_details';
    protected $fillable = [
        'invoice_id',
        'user_id',
        'user_sub_plan_id',
        'amount',
        'payment_status',
        'billing_date',
        'next_billing_date'

    ];
    public function invoice()
    {
        return $this->belongsTo('App\CorporateClientInvoice');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function package()
    {
        return $this->belongsTo('App\User');
    }

}



