<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\IntakeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Intake extends Model
{
    /** @use HasFactory<IntakeFactory> */
    use HasFactory;

    use SoftDeletes;

    public function casts(): array
    {
        return [
            'id' => 'integer',
            'patient_id' => 'integer',
            'user_id' => 'integer',
            'appetite' => 'array',
            'appetite_comment' => 'string',
            'unable_to_eat_drink' => 'array',
            'unable_to_eat_drink_comment' => 'string',
            'percentage_eaten' => 'integer',
            'liquids' => 'integer',
            'urine' => 'integer',
            'unmeasured_urine_occurrence' => 'integer',
            'urine_characteristics' => 'array',
            'urine_characteristics_comment' => 'string',
            'stool' => 'integer',
            'stool_amount' => 'array',
            'stool_amount_comment' => 'string',
            'unmeasured_stool_occurrence' => 'integer',
            'stool_characteristics' => 'array',
            'stool_characteristics_comment' => 'string',
            'unmeasured_emesis_occurrence' => 'integer',
            'emesis_characteristics' => 'array',
            'emesis_characteristics_comment' => 'string',
            'emesis_amount' => 'array',
            'emesis_amount_comment' => 'string',
            'deleted_by' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

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
}
