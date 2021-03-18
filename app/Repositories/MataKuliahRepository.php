<?php

namespace App\Repositories;

use App\Models\MataKuliah;

class MataKuliahRepository
{
    private $model;

    public function __construct()
    {
        $this->model = new MataKuliah();
    }

    public function listsBox(): array
    {
        return $this->model->select("mk_nama", "id_matakuliah")
            ->pluck("mk_nama", "id_matakuliah")
            ->toArray();
    }
}
