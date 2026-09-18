<?php

namespace App\Models;

use Database\Factories\ProviderProfileFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'business_name', 'provider_type', 'primary_category_id'])]
class ProviderProfile extends Model
{
    /** @use HasFactory<ProviderProfileFactory> */
    use HasFactory;

    /**
     * Get the user that owns the provider profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
