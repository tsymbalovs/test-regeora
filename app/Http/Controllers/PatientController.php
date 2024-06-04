<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PatientRepositoryInterface;

class PatientController extends Controller
{

    protected $patientRepository;

    public function __construct(PatientRepositoryInterface $patientRepository)
    {
        $this->patientRepository = $patientRepository;
    }

    public function createPatient(Request $request)
    {

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthdate' => 'required|date',
        ]);

        $patient = $this->patientRepository->create($validatedData);

        return response()->json($patient, 201);
    }

    public function getPatients()
    {
        $patients = $this->patientRepository->getAll();

        return response()->json($patients);
    }

}
