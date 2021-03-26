<?php

namespace App\Http\Controllers;

use App\Models\PengembangMateri;
use App\Models\RpsTemp;
use App\Models\Rps;
use App\Models\TextBook;
use App\Models\Topic;
use App\Models\MetodePenilaian;
use App\Services\RpsService;
use Illuminate\Http\Request;
use App\Http\Requests\RpsRequest;

class RpsController extends Controller
{
    public function __construct(PengembangMateri $pengembangMateri, TextBook $textBook, RpsService $RpsService)
    {
        $this->pengembangMateri = $pengembangMateri;
        $this->__route = "rps";
        $this->service = (new RpsService())->setRoute("rps");
        $this->textBook = $textBook;
        $this->RpsService = $RpsService;

        view()->share("__route", $this->__route);
        view()->share("__menu", "RPS");
    }

    public function getData(Request $request)
    {
        return $this->service->getData($request);
    }

    public function getIndex()
    {
        return view("rps.index");
    }

    public function getDetail($id)
    {
        $textBook = $this->textBook->where('id_pm',$id)->first();
        $rps = Rps::find($id);
        $metodePenilaian = MetodePenilaian::get();

        $metodePenilaianChecked = [];
        $capaianPembelajaran = [];
        $topic = [];
        $totalSubTopic = 0;
        if ($rps) {
            $metodePenilaianChecked = json_decode($rps['metode_penilaian'],true); 
            $capaianPembelajaran = json_decode($rps['capaian_pembelajaran'],true); 
            $topicArr = Topic::where('id_pm',$id)->get();
            $totalSubTopic = $topicArr->count(); 
            
            foreach ($topicArr as $key => $v) {
                $topic[$v['topic']][] = [
                                            'sesi' => $v['sesi'],
                                            'sub_topic' => $v['sub_topic'],
                                            'capaian_pembelajaran' => $v['capaian_pembelajaran'],
                                        ];
            }
                                        // dd($topic);
        }

        return view("rps.form", [
            "model" => $textBook,
            "metodePenilaianChecked" => $metodePenilaianChecked,
            "capaianPembelajaran" => $capaianPembelajaran,
            "metodePenilaian" => $metodePenilaian,
            "totalSubTopic" => $totalSubTopic,
            "topic" => $topic,
            "titleAction" => "Input RPS",
            "rps" => $rps,
        ]);
    }

    public function postDetail(RpsRequest $request, $id)
    {
        $this->RpsService->updateOrcreate($request, $id);
        toast('Data telah diupdate', 'success');

        return redirect($this->__route);
    }
    // public function postDetail(Request $request, $id)
    // {
    //     $textBook = $this->textBook->findOrFail($id);
    //     RpsTemp::updateOrcreate([
    //         "id_text_book" => $textBook->id_text_book,
    //         "id_pm" => $textBook->id_pm,
    //     ], [
    //         "value" => json_encode($request->all()),
    //     ]);

    //     toast('Data telah diupdate', 'success');
    //     return redirect($this->__route);
    // }

    public function viewPdf($type, $file)
    {
        $pathToFile = Storage::url(contents_path().$type.'/'.$file);
        return Storage::response($pathToFile);
        // return response()->file($pathToFile);
    }
}
