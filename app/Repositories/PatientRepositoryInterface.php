<?php

namespace App\Repositories;

interface PatientRepositoryInterface
{
    public function create(array $data);

    public function getAll();
}
