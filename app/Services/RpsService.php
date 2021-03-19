<?php

namespace App\Services;

use App\Models\PmAssign;
use Illuminate\Support\Str;
use App\Singleton\Component;
use Illuminate\Http\Request;
use App\Models\PengembangMateri;
use App\Models\RpsTemp;
use Exception;
use Illuminate\Support\Facades\DB;

class RpsService
{
    private $route;

    public function __construct()
    {
        $this->route = "";
    }

    public function setRoute($route = ""): RpsService
    {
        $this->route = $route;

        return $this;
    }

    public function getData(Request $request)
    {
        $model = PengembangMateri::select('pengembang_materi.id_pm as id_pm', 'nama_semester', 'mk_nama', 'title', 'id_text_book')
            ->join("semester", "semester.id_semester", "=", "pengembang_materi.id_semester")
            ->join("matakuliah", "matakuliah.id_matakuliah", "=", "pengembang_materi.id_matakuliah")
            ->join("text_book", 'text_book.id_pm', '=', 'pengembang_materi.id_pm')
            ->orderBy("id_pm", "desc");

        return \Table::of($model)
            ->addColumn('status', function ($model) {
                $check = RpsTemp::where('id_text_book', $model->id_text_book)->count();

                return $check > 0 ? "Sudah Terisi" : "Belum Terisi";
            })
            ->addColumn('action', function ($model) {

                return \Html::link($this->route . "/detail/" . $model->id_text_book, 'Input', ["class" => "btn btn-primary btn-sm"]);
            })
            ->make();
    }
}
