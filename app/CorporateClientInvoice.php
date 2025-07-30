<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CorporateClientInvoice extends Model
{
    protected $guarded = [''];
    use SoftDeletes; //add this line
    protected $table = 'corp_client_invoices';
    protected $fillable = [
        'invoice_number',
        'corp_client_id',
        'invoice_total',
        'card_no',
        'card_expiry',
        'payment_status',
        'payment_type',
        'payment_date'

    ];

    public function invoicedetailes()
    {
        return $this->hasMany('App\CorporateClientInvoiceDetail','invoice_id');
    }
    public function invoicePlans()
    {
        return $this->hasMany('App\CorporateClientInvoiceDetail','invoice_id')
            ->select('count(corp_client_invoices_details.user_sub_plan_id) as plans', 'corp_client_invoices_details.user_sub_plan_id', 'user_id')
            ->groupBy('user_sub_plan_id')
            ->get();
    }

    public function invoiceClient()
    {
        return $this->belongsTo('App\User','corp_client_id');
    }
}



