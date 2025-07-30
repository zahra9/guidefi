<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\CorporateClientSignupCode;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

class CodesExport implements FromArray, WithHeadings

{
    use Exportable;
    /**
    * @return Collection
    */
    public function headings(): array
    {
        return [
            ['Signup Code','Code Used']
        ];
    }
    public function __construct(int $userid)
    {
        $this->userid = $userid;
    }
    public function array(): array

    {
        //dd($this->userid);
        $allcodes=CorporateClientSignupCode::where('corp_client_id',$this->userid)->select('sign_up_code','is_used')->get();
        //dd($allcodes);
        $allcodesarray=array();
        $i=0;//
        foreach ($allcodes as $code) {
            $allcodesarray[$i]['sign_up_code']=$code->sign_up_code;
            $allcodesarray[$i]['is_used']=$code->is_used==1 ? 'Yes':'No';
            $i++;
        }
        return [$allcodesarray];
    }
}
