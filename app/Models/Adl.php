<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\AdlFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Adl extends Model
{
    /** @use HasFactory<AdlFactory> */
    use HasFactory;

    use SoftDeletes;

    /** @var array<int, string> */
    protected $guarded = [];

    /**
     * @return BelongsTo<Patient, $this>
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'bathing' => 'array',
            'bathing_level_of_assist' => 'array',
            'oral_care' => 'array',
            'oral_care_level_of_assist' => 'array',
            'linen_change' => 'array',
            'hair' => 'array',
            'shave' => 'array',
            'deodorant' => 'array',
            'nail_care' => 'array',
            'skin' => 'array',
            'activity' => 'array',
            'mobility' => 'array',
            'level_of_transfer_assist' => 'array',
            'assistive_device' => 'array',
            'ambulation_response' => 'array',
            'level_of_repositioning_assistance' => 'array',
            'repositioned' => 'array',
            'positioning_frequency' => 'array',
            'head_of_bed_elevated' => 'array',
            'heels_feet' => 'array',
            'hands_arms' => 'array',
            'range_of_motion' => 'array',
            'transport_method' => 'array',
            'anti_embolism_device' => 'array',
            'anti_embolism_status' => 'array',
        ];
    }
}
