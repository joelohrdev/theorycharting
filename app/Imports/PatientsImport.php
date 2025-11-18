<?php

declare(strict_types=1);

namespace App\Imports;

use App\Models\Patient;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

final class PatientsImport implements SkipsOnError, ToModel, WithHeadingRow
{
    use SkipsErrors;

    public function __construct(private readonly int $userId) {}

    /**
     * @param  array<string, mixed>  $row
     */
    public function model(array $row): ?Patient
    {
        // Validate the row data
        $validator = Validator::make($row, [
            'name' => ['required', 'max:255'],
            'gender' => ['required', 'max:255'],
            'birth_date' => ['required', 'date'],
            'mrn' => ['required', 'max:255'],
            'room' => ['required', 'max:255'],
            'admission_date' => ['required', 'date'],
            'attending_md' => ['required', 'max:255'],
            'diagnosis' => ['required', 'max:255'],
            'diet_order' => ['required', 'max:255'],
            'activity_level' => ['required', 'max:255'],
            'procedure' => ['required', 'max:255'],
            'status' => ['required', 'max:255'],
            'isolation' => ['required', 'max:255'],
            'unit' => ['required', 'max:255'],
        ]);

        if ($validator->fails()) {
            return null;
        }

        return new Patient([
            'uuid' => Str::uuid()->toString(),
            'user_id' => $this->userId,
            'name' => (string) $row['name'],
            'gender' => (string) $row['gender'],
            'birth_date' => $row['birth_date'],
            'mrn' => (string) $row['mrn'],
            'room' => (string) $row['room'],
            'admission_date' => $row['admission_date'],
            'attending_md' => (string) $row['attending_md'],
            'diagnosis' => (string) $row['diagnosis'],
            'diet_order' => (string) $row['diet_order'],
            'activity_level' => (string) $row['activity_level'],
            'procedure' => (string) $row['procedure'],
            'status' => (string) $row['status'],
            'isolation' => (string) $row['isolation'],
            'unit' => (string) $row['unit'],
        ]);
    }
}
