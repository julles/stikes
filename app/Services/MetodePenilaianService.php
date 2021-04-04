<?php

namespace App\Services;

use App\Models\MetodePenilaian;
use App\Singleton\Component;
use Illuminate\Http\Request;

class MetodePenilaianService
{
    private $route;

    public function __construct()
    {
        $this->route = "";
    }

    public function setRoute($route = ""): MetodePenilaianService
    {
        $this->route = $route;

        return $this;
    }

    public function getData(Request $request)
    {
        $model = MetodePenilaian::selectRaw('id,component,CONCAT(weight,"%") as weight_percent,CONCAT(weight_praktikum,"%") as weight_praktikum_percent')
            ->orderBy("weight", "asc");

        return \Table::of($model)
            ->addColumn('action', function ($model) {

                $edit = Component::build()
                    ->url($this->route . "/update/" . $model->id)
                    ->type("edit")
                    ->link();

                $delete = Component::build()
                    ->url($this->route . "/delete/" . $model->id)
                    ->type("delete")
                    ->link();

                return $edit . " " . $delete;
            })
            ->make();
    }

    public function create(Request $request)
    {
        MetodePenilaian::create($request->all());
    }

    public function update(Request $request, int $id)
    {
        $model = MetodePenilaian::findOrFail($id);

        $model->update($request->all());
    }

    public function delete($model)
    {
        try {
            $model->delete();
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
