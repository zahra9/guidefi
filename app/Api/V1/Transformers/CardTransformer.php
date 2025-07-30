<?php

namespace App\Api\V1\Transformers;

use App\Cards;
use League\Fractal\TransformerAbstract;

class CardTransformer extends TransformerAbstract
{
    /**
     * @property string name
     * @property string email
     */
    public function transform(Cards $cards)
    {
        return [
            'id' => $cards->id,
            'card_number' => $cards->card_number,
            'is_default' => $cards->is_default,
            'created_at' => date('Y-m-d', strtotime($cards->created_at)),
        ];
    }
}
