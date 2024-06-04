<?php

namespace App\Repositories;

use App\Models\Patient;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use App\Jobs\ProcessPatient;

class PatientRepository implements PatientRepositoryInterface
{
    public function create(array $data)
    {
        $patient = Patient::create($data);

        $birthdate = Carbon::parse($patient->birthdate);
        $now = Carbon::now();
        $ageInDays = $birthdate->diffInDays($now);
        $ageInMonths = $birthdate->diffInMonths($now);
        $ageInYears = $birthdate->diffInYears($now);

        if ($ageInDays < 30) {
            $patient->age = (int)$ageInDays;
            $patient->age_type = 'день';
        } elseif ($ageInDays < 365) {
            $patient->age = (int)$ageInMonths;
            $patient->age_type = 'месяц';
        } else {
            $patient->age = (int)$ageInYears;
            $patient->age_type = 'год';
        }

        $patient->save();

        Cache::put('patient_' . $patient->id, $patient, 300);

        ProcessPatient::dispatch($patient);

        return $patient;
    }


    public function getAll()
    {
        $patients = Patient::all()->map(function ($patient) {

            $cacheKey = 'patient_' . $patient->id;

            if (Cache::has($cacheKey)) {
                return $this->generateResponse(Cache::get($cacheKey));
            }

            return $this->generateResponse($patient);
        });

        return $patients;
    }


    private function generateResponse($patient)
    {
        $birthdate = Carbon::parse($patient->birthdate);

        return [
            'name' => $patient->first_name . ' ' . $patient->last_name,
            'birthdate' => $birthdate->format('d.m.Y'),
            'age' => $patient->age . ' ' . $patient->age_type,
        ];
    }

}
