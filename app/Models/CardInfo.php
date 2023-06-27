<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardInfo extends Model
{
    use HasFactory;

    public $table = 'card_info';

    protected $fillable = [
        'name',
        'number',
        'month',
        'year',
        'cvv',
        'card_holder_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cardHolder() {
        return $this->belongsTo(CardHolderInfo::class, 'id', 'card_holder_id');
    }
}
