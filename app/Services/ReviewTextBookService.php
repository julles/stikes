<?php

namespace App\services;

use App\Http\Requests\ReviewTextBookRequest;
use App\Singleton\Component;
use Illuminate\Http\Request;
use App\Models\PengembangMateri;
use App\Models\PmAssign;
use App\Models\TextBook;
use Auth;

class ReviewTextBookService
{
    private $route;

    public function __construct()
    {
        $this->route = "";
    }

    public function setRoute($route = ""): ReviewTextBookService
    {
        $this->route = $route;

        return $this;
    }

    public function getData(Request $request)
    {
        $user = Auth::user();
        $pm = pmAssign::where('reviewer_id',$user->id)
                        ->orWhere('approval_id',$user->id)
                        ->pluck('id_pm');
        
        $model = PengembangMateri::
            select('pengembang_materi.id_pm', 
                    'semester.nama_semester', 
                    'matakuliah.mk_nama',
                    'text_book.title',
                    'text_book.kategori',
                    'text_book.tahun'
                   )
            ->whereIn('text_book.status',[0,1,2])
            ->whereIn('pengembang_materi.id_pm',$pm)
            ->join("semester", "semester.id_semester", "=", "pengembang_materi.id_semester")
            ->join("matakuliah", "matakuliah.id_matakuliah", "=", "pengembang_materi.id_matakuliah")
            ->join("text_book", "text_book.id_pm", "=", "pengembang_materi.id_pm")
            ->orderBy("pengembang_materi.status", "asc")
            ->orderBy("id_pm", "desc");

        return \Table::of($model)
            ->addColumn('status', function ($model) {
                $stat = ['Waiting Approval','Reviewed','Approved','Reject'];
                $check = TextBook::where("id_pm", $model->id_pm)->first();
                $ret = "Belum diinput";
                if ($check) {
                    $ret = $stat[$check->status];
                }                
                return $ret;
            })
            ->addColumn('action', function ($model) {
                return \Html::link($this->route . "/detail/" . $model->id_pm, 'View', ["class" => "btn btn-primary btn-sm"]);
            })
            ->make();
    }

    public function ReviewOrApproval(ReviewTextBookRequest $request, $id)
    {
        $user = Auth::user();
        $pengembangMateri = PengembangMateri::with('pm_assign')->findOrFail($id);

        $model = $pengembangMateri->text_book()->first();

        // check status dosen
        if ($pengembangMateri->pm_assign->reviewer_id == $user->id) {

            $inputs = [
                        'reviewer_commen' => $request->reviewer_commen,
                        'reviewer_date' => date("Y-m-d H:i:s"),
                        'reviewer_user' => $user->id
            ];

            if ($request->status == 1) {
                $inputs['status'] = 1;
            }else{
                $inputs['status'] = 3;
            }

        }elseif($pengembangMateri->pm_assign->approval_id == $user->id){

            $inputs = [
                        'approv_commen' => $request->approv_commen,
                        'approv_date' => date("Y-m-d H:i:s"),
                        'approv_user' => $user->id
            ];

            if ($request->status == 1) {
                $inputs['status'] = 2;
            }else{
                $inputs['status'] = 3;
            }
        }

        $model->update($inputs);
    }

    public function userStatus($id)
    {
        $user = Auth::user();
        $pengembangMateri = PengembangMateri::findOrFail($id);

        $status = false;
        if ($pengembangMateri->pm_assign->reviewer_id == $user->id) {
            $status = 'reviewer';
        }elseif($pengembangMateri->pm_assign->approval_id == $user->id){
            $status = 'approv';
        }

        return $status;
    }

    public function updateOrCreate(ReviewTextBookRequest $request, $id)
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
