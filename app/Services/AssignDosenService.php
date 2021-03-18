<?php

namespace App\Services;

use App\Models\PmAssign;
use Illuminate\Support\Str;
use App\Singleton\Component;
use Illuminate\Http\Request;
use App\Models\PengembangMateri;
use Exception;
use Illuminate\Support\Facades\DB;

class AssignDosenService
{
    private $route;

    public function __construct()
    {
        $this->route = "";
    }

    public function setRoute($route = ""): AssignDosenService
    {
        $this->route = $route;

        return $this;
    }

    public function getData(Request $request)
    {
        $model = PengembangMateri::select('id_pm', 'nama_semester', 'mk_nama')
            ->join("semester", "semester.id_semester", "=", "pengembang_materi.id_semester")
            ->join("matakuliah", "matakuliah.id_matakuliah", "=", "pengembang_materi.id_matakuliah")
            ->orderBy("id_pm", "desc");

        return \Table::of($model)
            ->addColumn('action', function ($model) {

                $edit = Component::build()
                    ->url($this->route . "/update/" . $model->id_pm)
                    ->type("edit")
                    ->link();

                $delete = Component::build()
                    ->url($this->route . "/delete/" . $model->id_pm)
                    ->type("delete")
                    ->link();

                return $edit . " " . $delete;
            })
            ->make();
    }

    public function create(Request $request)
    {
        try {
            DB::beginTransaction();

            $pengembangMateriInputs = [
                "id_semester" => $request->id_semester,
                "id_matakuliah" => $request->id_matakuliah,
                "create_date" => date("Y-m-d H:i:s"),
                "create_user" => auth()->user()->id,
            ];

            $pemberiMateri = PengembangMateri::create($pengembangMateriInputs);

            $pmAssignInputs = [
                "id_pm" => $pemberiMateri->id_pm,
                "sme_id" => $request->sme_id,
                "reviewer_id" => $request->reviewer_id,
                "approval_id" => $request->approval_id,
                "create_date" => date("Y-m-d H:i:s"),
                "create_user" => auth()->user()->id,
            ];

            PmAssign::create($pmAssignInputs);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            dd($e->getMessage());
        }
    }

    public function update(Request $request, int $id)
    {
        try {
            DB::beginTransaction();
            $pengembangMateri = PengembangMateri::findOrFail($id);

            $pengembangMateriInputs = [
                "id_semester" => $request->id_semester,
                "id_matakuliah" => $request->id_matakuliah,
                "create_date" => date("Y-m-d H:i:s"),
                "create_user" => auth()->user()->id,
            ];

            $pengembangMateri->update($pengembangMateriInputs);

            $pmAssignInputs = [
                "id_pm" => $pengembangMateri->id_pm,
                "sme_id" => $request->sme_id,
                "reviewer_id" => $request->reviewer_id,
                "approval_id" => $request->approval_id,
                "create_date" => date("Y-m-d H:i:s"),
                "create_user" => auth()->user()->id,
            ];

            $pengembangMateri->pm_assign()->update($pmAssignInputs);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            dd($e->getMessage());
        }
    }

    public function delete($model)
    {
        DB::beginTransaction();

        try {
            $model->pm_assign()->delete();
            $model->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getMessage());
        }
    }
}
