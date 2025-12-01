<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

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
use App\Models\Adl;
use App\Models\Patient;
use Illuminate\Validation\Rule;
use Livewire\Form;

final class PatientAdlForm extends Form
{
    public ?Adl $adl = null;

    public array $bathing = [];

    public string $bathingComment = '';

    public array $bathingLevelOfAssist = [];

    public string $bathingLevelOfAssistComment = '';

    public array $oralCare = [];

    public string $oralCareComment = '';

    public array $oralCareLevelOfAssist = [];

    public string $oralCareLevelOfAssistComment = '';

    public array $linenChange = [];

    public string $linenChangeComment = '';

    public array $hair = [];

    public string $hairComment = '';

    public array $shave = [];

    public string $shaveComment = '';

    public array $deodorant = [];

    public string $deodorantComment = '';

    public array $nailCare = [];

    public string $nailCareComment = '';

    public array $skin = [];

    public string $skinComment = '';

    public string $observations = '';

    public array $activity = [];

    public string $activityComment = '';

    public array $mobility = [];

    public string $mobilityComment = '';

    public array $levelOfTransferAssist = [];

    public string $levelOfTransferAssistComment = '';

    public array $assistiveDevice = [];

    public string $assistiveDeviceComment = '';

    public ?int $distanceAmbulated = null;

    public array $ambulationResponse = [];

    public string $ambulationResponseComment = '';

    public array $levelOfRepositioningAssistance = [];

    public string $levelOfRepositioningAssistanceComment = '';

    public array $repositioned = [];

    public string $repositionedComment = '';

    public array $positioningFrequency = [];

    public string $positioningFrequencyComment = '';

    public array $headOfBedElevated = [];

    public string $headOfBedElevatedComment = '';

    public array $heelsFeet = [];

    public string $heelsFeetComment = '';

    public array $handsArms = [];

    public string $handsArmsComment = '';

    public array $rangeOfMotion = [];

    public string $rangeOfMotionComment = '';

    public array $transportMethod = [];

    public string $transportMethodComment = '';

    public array $antiEmbolismDevice = [];

    public string $antiEmbolismDeviceComment = '';

    public array $antiEmbolismStatus = [];

    public string $antiEmbolismStatusComment = '';

    public function setAdl(Adl $adl): void
    {
        $this->adl = $adl;
        $this->bathing = $adl->bathing ?? [];
        $this->bathingComment = $adl->bathing_comment ?? '';
        $this->bathingLevelOfAssist = $adl->bathing_level_of_assist ?? [];
        $this->bathingLevelOfAssistComment = $adl->bathing_level_of_assist_comment ?? '';
        $this->oralCare = $adl->oral_care ?? [];
        $this->oralCareComment = $adl->oral_care_comment ?? '';
        $this->oralCareLevelOfAssist = $adl->oral_care_level_of_assist ?? [];
        $this->oralCareLevelOfAssistComment = $adl->oral_care_level_of_assist_comment ?? '';
        $this->linenChange = $adl->linen_change ?? [];
        $this->linenChangeComment = $adl->linen_change_comment ?? '';
        $this->hair = $adl->hair ?? [];
        $this->hairComment = $adl->hair_comment ?? '';
        $this->shave = $adl->shave ?? [];
        $this->shaveComment = $adl->shave_comment ?? '';
        $this->deodorant = $adl->deodorant ?? [];
        $this->deodorantComment = $adl->deodorant_comment ?? '';
        $this->nailCare = $adl->nail_care ?? [];
        $this->nailCareComment = $adl->nail_care_comment ?? '';
        $this->skin = $adl->skin ?? [];
        $this->skinComment = $adl->skin_comment ?? '';
        $this->observations = $adl->observations ?? '';
        $this->activity = $adl->activity ?? [];
        $this->activityComment = $adl->activity_comment ?? '';
        $this->mobility = $adl->mobility ?? [];
        $this->mobilityComment = $adl->mobility_comment ?? '';
        $this->levelOfTransferAssist = $adl->level_of_transfer_assist ?? [];
        $this->levelOfTransferAssistComment = $adl->level_of_transfer_assist_comment ?? '';
        $this->assistiveDevice = $adl->assistive_device ?? [];
        $this->assistiveDeviceComment = $adl->assistive_device_comment ?? '';
        $this->distanceAmbulated = $adl->distance_ambulated ?? null;
        $this->ambulationResponse = $adl->ambulation_response ?? [];
        $this->ambulationResponseComment = $adl->ambulation_response_comment ?? '';
        $this->levelOfRepositioningAssistance = $adl->level_of_repositioning_assistance ?? [];
        $this->levelOfRepositioningAssistanceComment = $adl->level_of_repositioning_assistance_comment ?? '';
        $this->repositioned = $adl->repositioned ?? [];
        $this->repositionedComment = $adl->repositioned_comment ?? '';
        $this->positioningFrequency = $adl->positioning_frequency ?? [];
        $this->positioningFrequencyComment = $adl->positioning_frequency_comment ?? '';
        $this->headOfBedElevated = $adl->head_of_bed_elevated ?? [];
        $this->headOfBedElevatedComment = $adl->head_of_bed_elevated_comment ?? '';
        $this->heelsFeet = $adl->heels_feet ?? [];
        $this->heelsFeetComment = $adl->heels_feet_comment ?? '';
        $this->handsArms = $adl->hands_arms ?? [];
        $this->handsArmsComment = $adl->hands_arms_comment ?? '';
        $this->rangeOfMotion = $adl->range_of_motion ?? [];
        $this->rangeOfMotionComment = $adl->range_of_motion_comment ?? '';
        $this->transportMethod = $adl->transport_method ?? [];
        $this->transportMethodComment = $adl->transport_method_comment ?? '';
        $this->antiEmbolismDevice = $adl->anti_embolism_device ?? [];
        $this->antiEmbolismDeviceComment = $adl->anti_embolism_device_comment ?? '';
        $this->antiEmbolismStatus = $adl->anti_embolism_status ?? [];
        $this->antiEmbolismStatusComment = $adl->anti_embolism_status_comment ?? '';
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'bathing' => ['nullable', 'array'],
            'bathing.*' => ['string', Rule::enum(Bathing::class)],
            'bathingComment' => ['nullable', 'string', 'max:1000'],
            'bathingLevelOfAssist' => ['nullable', 'array'],
            'bathingLevelOfAssist.*' => ['string', Rule::enum(LevelOfAssist::class)],
            'bathingLevelOfAssistComment' => ['nullable', 'string', 'max:1000'],
            'oralCare' => ['nullable', 'array'],
            'oralCare.*' => ['string', Rule::enum(OralCare::class)],
            'oralCareComment' => ['nullable', 'string', 'max:1000'],
            'oralCareLevelOfAssist' => ['nullable', 'array'],
            'oralCareLevelOfAssist.*' => ['string', Rule::enum(LevelOfAssist::class)],
            'oralCareLevelOfAssistComment' => ['nullable', 'string', 'max:1000'],
            'linenChange' => ['nullable', 'array'],
            'linenChange.*' => ['string', Rule::enum(LinenChange::class)],
            'linenChangeComment' => ['nullable', 'string', 'max:1000'],
            'hair' => ['nullable', 'array'],
            'hair.*' => ['string', Rule::enum(Hair::class)],
            'hairComment' => ['nullable', 'string', 'max:1000'],
            'shave' => ['nullable', 'array'],
            'shave.*' => ['string', Rule::enum(Shave::class)],
            'shaveComment' => ['nullable', 'string', 'max:1000'],
            'deodorant' => ['nullable', 'array'],
            'deodorant.*' => ['string', Rule::enum(Deodorant::class)],
            'deodorantComment' => ['nullable', 'string', 'max:1000'],
            'nailCare' => ['nullable', 'array'],
            'nailCare.*' => ['string', Rule::enum(NailCare::class)],
            'nailCareComment' => ['nullable', 'string', 'max:1000'],
            'skin' => ['nullable', 'array'],
            'skin.*' => ['string', Rule::enum(Skin::class)],
            'skinComment' => ['nullable', 'string', 'max:1000'],
            'observations' => ['nullable', 'string', 'max:5000'],
            'activity' => ['nullable', 'array'],
            'activity.*' => ['string', Rule::enum(Activity::class)],
            'activityComment' => ['nullable', 'string', 'max:1000'],
            'mobility' => ['nullable', 'array'],
            'mobility.*' => ['string', Rule::enum(Mobility::class)],
            'mobilityComment' => ['nullable', 'string', 'max:1000'],
            'levelOfTransferAssist' => ['nullable', 'array'],
            'levelOfTransferAssist.*' => ['string', Rule::enum(LevelOfTransfer::class)],
            'levelOfTransferAssistComment' => ['nullable', 'string', 'max:1000'],
            'assistiveDevice' => ['nullable', 'array'],
            'assistiveDevice.*' => ['string', Rule::enum(MobilityAssistDevices::class)],
            'assistiveDeviceComment' => ['nullable', 'string', 'max:1000'],
            'distanceAmbulated' => ['nullable', 'integer', 'min:0'],
            'ambulationResponse' => ['nullable', 'array'],
            'ambulationResponse.*' => ['string', Rule::enum(AmbulationResponse::class)],
            'ambulationResponseComment' => ['nullable', 'string', 'max:1000'],
            'levelOfRepositioningAssistance' => ['nullable', 'array'],
            'levelOfRepositioningAssistance.*' => ['string', Rule::enum(LevelOfRepositioning::class)],
            'levelOfRepositioningAssistanceComment' => ['nullable', 'string', 'max:1000'],
            'repositioned' => ['nullable', 'array'],
            'repositioned.*' => ['string', Rule::enum(Repositioned::class)],
            'repositionedComment' => ['nullable', 'string', 'max:1000'],
            'positioningFrequency' => ['nullable', 'array'],
            'positioningFrequency.*' => ['string', Rule::enum(PositioningFrequency::class)],
            'positioningFrequencyComment' => ['nullable', 'string', 'max:1000'],
            'headOfBedElevated' => ['nullable', 'array'],
            'headOfBedElevated.*' => ['string', Rule::enum(HeadBedElevated::class)],
            'headOfBedElevatedComment' => ['nullable', 'string', 'max:1000'],
            'heelsFeet' => ['nullable', 'array'],
            'heelsFeet.*' => ['string', Rule::enum(HandsFeet::class)],
            'heelsFeetComment' => ['nullable', 'string', 'max:1000'],
            'handsArms' => ['nullable', 'array'],
            'handsArms.*' => ['string', Rule::enum(HandsArms::class)],
            'handsArmsComment' => ['nullable', 'string', 'max:1000'],
            'rangeOfMotion' => ['nullable', 'array'],
            'rangeOfMotion.*' => ['string', Rule::enum(RangeOfMotion::class)],
            'rangeOfMotionComment' => ['nullable', 'string', 'max:1000'],
            'transportMethod' => ['nullable', 'array'],
            'transportMethod.*' => ['string', Rule::enum(TransportMethod::class)],
            'transportMethodComment' => ['nullable', 'string', 'max:1000'],
            'antiEmbolismDevice' => ['nullable', 'array'],
            'antiEmbolismDevice.*' => ['string', Rule::enum(AntiEmbolismDevice::class)],
            'antiEmbolismDeviceComment' => ['nullable', 'string', 'max:1000'],
            'antiEmbolismStatus' => ['nullable', 'array'],
            'antiEmbolismStatus.*' => ['string', Rule::enum(AntiEmbolismStatus::class)],
            'antiEmbolismStatusComment' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function create(Patient $patient): Adl
    {
        $this->validate();

        return Adl::create([
            'patient_id' => $patient->id,
            'user_id' => auth()->id(),
            'bathing' => $this->bathing ?: [],
            'bathing_comment' => $this->bathingComment ?: null,
            'bathing_level_of_assist' => $this->bathingLevelOfAssist ?: [],
            'bathing_level_of_assist_comment' => $this->bathingLevelOfAssistComment ?: null,
            'oral_care' => $this->oralCare ?: [],
            'oral_care_comment' => $this->oralCareComment ?: null,
            'oral_care_level_of_assist' => $this->oralCareLevelOfAssist ?: [],
            'oral_care_level_of_assist_comment' => $this->oralCareLevelOfAssistComment ?: null,
            'linen_change' => $this->linenChange ?: [],
            'linen_change_comment' => $this->linenChangeComment ?: null,
            'hair' => $this->hair ?: [],
            'hair_comment' => $this->hairComment ?: null,
            'shave' => $this->shave ?: [],
            'shave_comment' => $this->shaveComment ?: null,
            'deodorant' => $this->deodorant ?: [],
            'deodorant_comment' => $this->deodorantComment ?: null,
            'nail_care' => $this->nailCare ?: [],
            'nail_care_comment' => $this->nailCareComment ?: null,
            'skin' => $this->skin ?: [],
            'skin_comment' => $this->skinComment ?: null,
            'observations' => $this->observations,
            'activity' => $this->activity ?: [],
            'activity_comment' => $this->activityComment ?: null,
            'mobility' => $this->mobility ?: [],
            'mobility_comment' => $this->mobilityComment ?: null,
            'level_of_transfer_assist' => $this->levelOfTransferAssist ?: [],
            'level_of_transfer_assist_comment' => $this->levelOfTransferAssistComment ?: null,
            'assistive_device' => $this->assistiveDevice ?: [],
            'assistive_device_comment' => $this->assistiveDeviceComment ?: null,
            'distance_ambulated' => $this->distanceAmbulated ?: null,
            'ambulation_response' => $this->ambulationResponse ?: [],
            'ambulation_response_comment' => $this->ambulationResponseComment ?: null,
            'level_of_repositioning_assistance' => $this->levelOfRepositioningAssistance ?: [],
            'level_of_repositioning_assistance_comment' => $this->levelOfRepositioningAssistanceComment ?: null,
            'repositioned' => $this->repositioned ?: [],
            'repositioned_comment' => $this->repositionedComment ?: null,
            'positioning_frequency' => $this->positioningFrequency ?: [],
            'positioning_frequency_comment' => $this->positioningFrequencyComment ?: null,
            'head_of_bed_elevated' => $this->headOfBedElevated ?: [],
            'head_of_bed_elevated_comment' => $this->headOfBedElevatedComment ?: null,
            'heels_feet' => $this->heelsFeet ?: [],
            'heels_feet_comment' => $this->heelsFeetComment ?: null,
            'hands_arms' => $this->handsArms ?: [],
            'hands_arms_comment' => $this->handsArmsComment ?: null,
            'range_of_motion' => $this->rangeOfMotion ?: [],
            'range_of_motion_comment' => $this->rangeOfMotionComment ?: null,
            'transport_method' => $this->transportMethod ?: [],
            'transport_method_comment' => $this->transportMethodComment ?: null,
            'anti_embolism_device' => $this->antiEmbolismDevice ?: [],
            'anti_embolism_device_comment' => $this->antiEmbolismDeviceComment ?: null,
            'anti_embolism_status' => $this->antiEmbolismStatus ?: [],
            'anti_embolism_status_comment' => $this->antiEmbolismStatusComment ?: null,
        ]);
    }
}
