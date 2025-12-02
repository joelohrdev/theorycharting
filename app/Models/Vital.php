<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BpMethod;
use App\Enums\BpSource;
use App\Enums\Heart;
use App\Enums\Oxygen;
use App\Enums\PainDescriptors;
use App\Enums\PainScale;
use App\Enums\PatientPosition;
use App\Enums\Temp;
use Carbon\CarbonImmutable;
use Database\Factories\VitalFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read int $id
 * @property-read int $patient_id
 * @property-read int $user_id
 * @property-read int|null $temperature
 * @property-read Temp|null $temperature_source
 * @property-read int|null $heart_rate
 * @property-read Heart|null $heart_rate_source
 * @property-read int|null $resp
 * @property-read int|null $systolic
 * @property-read int|null $diastolic
 * @property-read BpSource|null $bp_source
 * @property-read BpMethod|null $bp_method
 * @property-read PatientPosition|null $patient_position
 * @property-read string|null $abdominal_girth
 * @property-read PainScale|null $pain_scale
 * @property-read string|null $pain_score
 * @property-read int|null $pain_goal
 * @property-read string|null $pain_location
 * @property-read array<PainDescriptors>|null $pain_descriptors
 * @property-read int|null $sp02
 * @property-read Oxygen|null $oxygen_device
 * @property-read int|null $o2
 * @property-read int|null $glucose
 * @property-read CarbonImmutable $created_at
 * @property-read CarbonImmutable $updated_at
 */
final class Vital extends Model
{
    /** @use HasFactory<VitalFactory> */
    use HasFactory, SoftDeletes;

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
            'id' => 'integer',
            'patient_id' => 'integer',
            'user_id' => 'integer',
            'temperature' => 'integer',
            'temperature_source' => Temp::class,
            'heart_rate' => 'integer',
            'heart_rate_source' => Heart::class,
            'resp' => 'integer',
            'systolic' => 'integer',
            'diastolic' => 'integer',
            'bp_source' => BpSource::class,
            'bp_method' => BpMethod::class,
            'patient_position' => PatientPosition::class,
            'abdominal_girth' => 'string',
            'pain_scale' => PainScale::class,
            'pain_score' => 'string',
            'pain_goal' => 'integer',
            'pain_location' => 'string',
            'pain_descriptors' => 'array',
            'sp02' => 'integer',
            'oxygen_device' => Oxygen::class,
            'o2' => 'integer',
            'glucose' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
