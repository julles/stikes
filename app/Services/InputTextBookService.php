<?php

namespace App\services;

use App\Http\Requests\InputTextBookRequest;
use App\Singleton\Component;
use Illuminate\Http\Request;
use App\Models\PengembangMateri;
use App\Models\PmAssign;
use App\Models\TextBook;
use Auth;

class InputTextBookService
{
    private $route;

    public function __construct()
    {
        $this->route = "";
    }

    public function setRoute($route = ""): InputTextBookService
    {
        $this->route = $route;

        return $this;
    }

    public function getData(Request $request)
    {
        $user = Auth::user();
        $pm = pmAssign::where('sme_id',$user->id)->pluck('id_pm');

        $model = PengembangMateri::select('id_pm', 'nama_semester', 'mk_nama')
            ->whereIn('id_pm',$pm)
            ->join("semester", "semester.id_semester", "=", "pengembang_materi.id_semester")
            ->join("matakuliah", "matakuliah.id_matakuliah", "=", "pengembang_materi.id_matakuliah")
            ->orderBy("id_pm", "desc");

        return \Table::of($model)
            ->addColumn('action', function ($model) {
                $textBook = TextBook::where("id_pm", $model->id_pm)->first();

                $name = !empty(@$textBook->id_text_book) ? "Edit" : "Input";

                return \Html::link($this->route . "/detail/" . $model->id_pm, $name, ["class" => "btn btn-primary btn-sm"]);
            })
            ->make();
    }

    public function updateOrCreate(InputTextBookRequest $request, $id)
    {
        $pengembangMateri = PengembangMateri::findOrFail($id);

        $model = $pengembangMateri->text_book()->count() > 0 ? $pengembangMateri->text_book : new TextBook();

        $gbr_cover = $request->file("gbr_cover");
        $fotoName  = $model->gbr_cover;

        if ($request->delete_foto != $model->gbr_cover) {
            $fotoName = "";
            \Storage::delete(contents_path($model->gbr_cover));
        }

        if (!empty($gbr_cover)) {

            $fotoName = \Str::random(5) . "." . $gbr_cover->getClientOriginalExtension();

            $gbr_cover->storeAs("public/contents", $fotoName);
        }

        $inputs = $request->all();
        $inputs["gbr_cover"] = $fotoName;
        $inputs["id_pm"] = $id;
        unset($inputs["delete_gbr_cover"]);
        unset($inputs["semester"]);
        unset($inputs["mata_kuliah"]);

        if ($pengembangMateri->text_book()->count() > 0) {
            $model->update($inputs);
        } else {
            $model->create($inputs);
        }
    }
}
