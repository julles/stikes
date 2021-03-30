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
        
        $model = PengembangMateri::
            select('pengembang_materi.id_pm', 
                    'semester.nama_semester', 
                    'matakuliah.mk_nama',
                    'text_book.title',
                    'text_book.kategori',
                    'text_book.tahun'
                   )
            // ->whereIn('text_book.status',[0,3])
            ->whereIn('pengembang_materi.id_pm',$pm)
            ->join("semester", "semester.id_semester", "=", "pengembang_materi.id_semester")
            ->join("matakuliah", "matakuliah.id_matakuliah", "=", "pengembang_materi.id_matakuliah")
            ->leftJoin("text_book", "text_book.id_pm", "=", "pengembang_materi.id_pm")
            ->orderBy("pengembang_materi.status", "desc")
            ->orderBy("id_pm", "desc");

        return \Table::of($model)
            ->addColumn('status', function ($model) {
                $check = TextBook::where("id_pm", $model->id_pm)->first();
                $ret = "Belum diinput";
                if ($check) {
                    $ret = statusCaption($check->status);
                }                
                return $ret;
            })
            ->addColumn('action', function ($model) {
                $textBook = TextBook::where("id_pm", $model->id_pm)->first();

                $class = "btn btn-primary btn-sm ";
                $name = !empty(@$textBook->id_text_book) ? "Edit" : "Input";

                if (@$textBook->status == 2) {
                    // $class .= ' disabled';
                    $name = "View";
                    return \Html::link("review-text-book/detail/" . $model->id_pm, $name, ["class" => $class]);
                }

                return \Html::link($this->route . "/detail/" . $model->id_pm, $name, ["class" => $class]);
            })
            ->make();
    }

    public function updateOrCreate(InputTextBookRequest $request, $id)
    {
        $pengembangMateri = PengembangMateri::findOrFail($id);

        $model = $pengembangMateri->text_book()->count() > 0 ? $pengembangMateri->text_book : new TextBook();

        $gbr_cover = $request->file("gbr_cover");
        $fotoName  = $model->gbr_cover;

        if (!empty($gbr_cover)) {
            
            if ($request->delete_foto != $model->gbr_cover) {
                $fotoName = "";
                \Storage::delete(contents_path($model->gbr_cover));
            }

            $fotoName = $id . "-book_cover." . $gbr_cover->getClientOriginalExtension();

            $gbr_cover->storeAs("public/contents", $fotoName);
        }

        $inputs = $request->all();
        $inputs["gbr_cover"] = $fotoName;
        $inputs["id_pm"] = $id;
        $inputs["status"] = 0;
        unset($inputs["delete_gbr_cover"]);
        unset($inputs["semester"]);
        unset($inputs["mata_kuliah"]);

        if ($pengembangMateri->text_book()->count() > 0) {
            $model->update($inputs);
        } else {
            $model->create($inputs);

            // send email
            $user = Auth::user();
            sendEmail($id,'text-book','input',$user->id);
        }
    }
}
