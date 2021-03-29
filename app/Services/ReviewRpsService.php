<?php

namespace App\Services;

use App\Models\PmAssign;
use Illuminate\Support\Str;
use App\Singleton\Component;
use Illuminate\Http\Request;
use App\Models\PengembangMateri;
use App\Models\Rps;
use App\Models\Topic;
use App\Models\TextBook;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\RpsRequest;
use Auth;

class ReviewRpsService
{
    private $route;

    public function __construct()
    {
        $this->route = "";
    }

    public function setRoute($route = ""): ReviewRpsService
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
            ->where('text_book.status',2)
            ->whereIn('pengembang_materi.id_pm',$pm)
            ->join("semester", "semester.id_semester", "=", "pengembang_materi.id_semester")
            ->join("matakuliah", "matakuliah.id_matakuliah", "=", "pengembang_materi.id_matakuliah")
            ->leftJoin("text_book", "text_book.id_pm", "=", "pengembang_materi.id_pm")
            ->orderBy("pengembang_materi.status", "desc")
            ->orderBy("id_pm", "desc");

        return \Table::of($model)
            ->addColumn('status', function ($model) {
                $check = Rps::where("id", $model->id_pm)->first();
                $ret = "RPS Belum diinput";
                if ($check) {
                    $ret = statusCaption($check->status);
                }                
                return $ret;
            })
            ->addColumn('action', function ($model) {
                $check = Rps::where("id", $model->id_pm)->first();
                $name = !empty(@$check) ? "View" : "Input";
                
                return \Html::link($this->route . "/detail/" . $model->id_pm, $name, ["class" => "btn btn-primary"]);
            })
            ->make();
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

    public function ReviewOrApproval(RpsRequest $request, $id)
    {
        $user = Auth::user();
        $pengembangMateri = PengembangMateri::with('pm_assign')->findOrFail($id);
        $model = $pengembangMateri->rps()->first();

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

    public function updateOrcreate(RpsRequest $request, $id)
    {

        $check = Rps::find($id);

        $date = DATE('Y-m-d H:i:s');

        if ($check) {
            // if update    

            $data = Rps::find($id);
            $data->strategi_pembelajaran = $request['strategi_pembelajaran'];
            $data->deskripsi_mata_kuliah = $request['deskripsi_mata_kuliah'];
            $data->media_pembelajaran = $request['media_pembelajaran'];
            $data->capaian_pembelajaran = json_encode($request['capaian_pembelajaran'],true);
            $data->metode_penilaian = json_encode($request['metode_penilaian'],true);
            $data->status = 0;
            $data->updated_at = $date;

            // upload file

            $peta_kompetensi = $request->file("peta_kompetensi");
            
            if (!empty($peta_kompetensi)) {

                $fileName = $id.'-peta_kompetensi' . "." . $peta_kompetensi->getClientOriginalExtension();

                $peta_kompetensi->storeAs("public/contents/peta_kompetensi/", $fileName);
                $data->peta_kompetensi = $fileName;
            }

            $rubrik_penilaian = $request->file("rubrik_penilaian");
            
            if (!empty($rubrik_penilaian)) {

                $fileName = $id.'-rubrik_penilaian' . "." . $rubrik_penilaian->getClientOriginalExtension();

                $rubrik_penilaian->storeAs("public/contents/rubrik_penilaian/", $fileName);
                $data->rubrik_penilaian = $fileName;
            }

            $data->save();

            // save topic

                // delete topic pm
                Topic::where('id_pm',$id)->delete();

                // insert topic
                $payload = [];
                $i = 0;
                foreach ($request['topic'] as $key => $v) {

                    $subTopic = $v['sub_topik'];
                    unset($v['sub_topik']);
                    foreach ($subTopic as $subTopickey => $subTopicVal) {
                        $payload[$i] = $v;
                        $payload[$i]['sub_topic'] = $subTopicVal;
                        $payload[$i]['id_pm'] = $id;
                        $payload[$i]['status'] = 0;                       
                       $i++; 
                    }

                }

                Topic::insert($payload);

                return true;
        }else{
            // if create

            // save RPS

            $payload = [
                'id' => $id,
                'strategi_pembelajaran' => $request['strategi_pembelajaran'],
                'deskripsi_mata_kuliah' => $request['deskripsi_mata_kuliah'],
                'media_pembelajaran' => $request['media_pembelajaran'],
                'capaian_pembelajaran' => json_encode($request['capaian_pembelajaran'],true),
                'metode_penilaian' => json_encode($request['metode_penilaian'],true),
                'status' => 0,
                'created_at' => $date,
                'updated_at' => $date
            ];

            // upload file

            $peta_kompetensi = $request->file("peta_kompetensi");
            
            if (!empty($peta_kompetensi)) {

                $fileName = $id.'-peta_kompetensi' . "." . $peta_kompetensi->getClientOriginalExtension();

                $peta_kompetensi->storeAs("public/contents/peta_kompetensi/", $fileName);
                $payload['peta_kompetensi'] =  $fileName;
            }

            $rubrik_penilaian = $request->file("rubrik_penilaian");
            
            if (!empty($rubrik_penilaian)) {

                $fileName = $id.'-rubrik_penilaian' . "." . $rubrik_penilaian->getClientOriginalExtension();

                $rubrik_penilaian->storeAs("public/contents/rubrik_penilaian/", $fileName);
                $payload['rubrik_penilaian'] =  $fileName;
            }

            $save = Rps::insert($payload);

            // insert topic
            $payload = [];
            $i = 0;
            foreach ($request['topic'] as $key => $v) {

                $subTopic = $v['sub_topik'];
                unset($v['sub_topik']);
                foreach ($subTopic as $subTopickey => $subTopicVal) {
                    $payload[$i] = $v;
                    $payload[$i]['sub_topic'] = $subTopicVal;
                    $payload[$i]['id_pm'] = $id;
                    $payload[$i]['status'] = 0;                       
                   $i++; 
                }

            }

            Topic::insert($payload);

            return true;
        }

    }
}