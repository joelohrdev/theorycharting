<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\PatientFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property-read string $uuid
 * @property-read int $user_id
 * @property-read string $name
 * @property-read string $gender
 * @property-read CarbonImmutable $birth_date
 * @property-read string $mrn
 * @property-read string $room
 * @property-read CarbonImmutable $admission_date
 * @property-read string $attending_md
 * @property-read string $diagnosis
 * @property-read string $diet_order
 * @property-read string $activity_level
 * @property-read string $procedure
 * @property-read string $status
 * @property-read string $isolation
 * @property-read string $unit
 * @property-read CarbonImmutable $created_at
 * @property-read CarbonImmutable $updated_at
 */
final class Patient extends Model
{
    /** @use HasFactory<PatientFactory> */
    use HasFactory;

    /** @var array<int, string> */
    protected $guarded = [];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<Vital, $this>
     */
    public function vitals(): HasMany
    {
        return $this->hasMany(Vital::class);
    }

    /**
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'id' => 'integer',
            'uuid' => 'string',
            'user_id' => 'integer',
            'name' => 'string',
            'gender' => 'string',
            'birth_date' => 'date',
            'mrn' => 'string',
            'room' => 'string',
            'admission_date' => 'datetime',
            'attending_md' => 'string',
            'diagnosis' => 'string',
            'diet_order' => 'string',
            'activity_level' => 'string',
            'procedure' => 'string',
            'status' => 'string',
            'isolation' => 'string',
            'unit' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
