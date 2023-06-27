<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CardHolderInfo extends Model
{
    use HasFactory;

    public $table = 'card_holder_info';

    protected $fillable = [
        'name',
        'document',
        'email',
        'zipcode',
        'address_number',
        'address_complement',
        'phone_number',
        'mobile_number',
    ];

    /**
     * @return HasMany
     */
    public function cards(): HasMany
    {
        return $this->hasMany(CardInfo::class, 'card_holder_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'card_holder_id', 'id');
    }
}
