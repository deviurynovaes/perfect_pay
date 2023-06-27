<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'value',
        'due_date',
        'billing_type',
        'description',
        'remote_ip',
        'installmentCount',
        'installmentValue',
        'card_id',
        'card_holder_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function card() {
        return $this->hasOne(CardInfo::class, 'id', 'card_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cardHolder() {
        return $this->hasOne(CardHolderInfo::class, 'id', 'card_holder_id');
    }
}
