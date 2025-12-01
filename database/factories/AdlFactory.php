<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Activity;
use App\Enums\AmbulationResponse;
use App\Enums\AntiEmbolismDevice;
use App\Enums\AntiEmbolismStatus;
use App\Enums\Bathing;
use App\Enums\Deodorant;
use App\Enums\Hair;
use App\Enums\HandsArms;
use App\Enums\HandsFeet;
use App\Enums\HeadBedElevated;
use App\Enums\LevelOfAssist;
use App\Enums\LevelOfRepositioning;
use App\Enums\LevelOfTransfer;
use App\Enums\LinenChange;
use App\Enums\Mobility;
use App\Enums\MobilityAssistDevices;
use App\Enums\NailCare;
use App\Enums\OralCare;
use App\Enums\PositioningFrequency;
use App\Enums\RangeOfMotion;
use App\Enums\Repositioned;
use App\Enums\Shave;
use App\Enums\Skin;
use App\Enums\TransportMethod;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Adl>
 */
final class AdlFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'user_id' => User::factory(),
            'bathing' => [fake()->randomElement(Bathing::cases())->value],
            'bathing_level_of_assist' => [fake()->randomElement(LevelOfAssist::cases())->value],
            'oral_care' => [fake()->randomElement(OralCare::cases())->value],
            'oral_care_level_of_assist' => [fake()->randomElement(LevelOfAssist::cases())->value],
            'linen_change' => [fake()->randomElement(LinenChange::cases())->value],
            'hair' => [fake()->randomElement(Hair::cases())->value],
            'shave' => [fake()->randomElement(Shave::cases())->value],
            'deodorant' => [fake()->randomElement(Deodorant::cases())->value],
            'nail_care' => [fake()->randomElement(NailCare::cases())->value],
            'skin' => fake()->randomElements(
                array_map(fn ($case) => $case->value, Skin::cases()),
                fake()->numberBetween(1, 2)
            ),
            'activity' => [fake()->randomElement(Activity::cases())->value],
            'mobility' => [fake()->randomElement(Mobility::cases())->value],
            'level_of_transfer_assist' => [fake()->randomElement(LevelOfTransfer::cases())->value],
            'assistive_device' => [fake()->randomElement(MobilityAssistDevices::cases())->value],
            'ambulation_response' => [fake()->randomElement(AmbulationResponse::cases())->value],
            'level_of_repositioning_assistance' => [fake()->randomElement(LevelOfRepositioning::cases())->value],
            'repositioned' => [fake()->randomElement(Repositioned::cases())->value],
            'positioning_frequency' => [fake()->randomElement(PositioningFrequency::cases())->value],
            'head_of_bed_elevated' => [fake()->randomElement(HeadBedElevated::cases())->value],
            'heels_feet' => [fake()->randomElement(HandsFeet::cases())->value],
            'hands_arms' => [fake()->randomElement(HandsArms::cases())->value],
            'range_of_motion' => [fake()->randomElement(RangeOfMotion::cases())->value],
            'transport_method' => [fake()->randomElement(TransportMethod::cases())->value],
            'anti_embolism_device' => [fake()->randomElement(AntiEmbolismDevice::cases())->value],
            'anti_embolism_status' => [fake()->randomElement(AntiEmbolismStatus::cases())->value],
        ];
    }
}
