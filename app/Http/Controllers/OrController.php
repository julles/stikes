<?php

namespace App\Http\Controllers;

use App\Models\PengembangMateri;
use App\Models\OrModel;
use App\Models\TextBook;
use App\Models\Topic;
use App\Models\MetodePenilaian;
use App\Services\OrService;
use Illuminate\Http\Request;
use App\Http\Requests\OrRequest;

class OrController extends Controller
{
    public function __construct(PengembangMateri $pengembangMateri, TextBook $textBook, OrService $OrService)
    {
        $this->pengembangMateri = $pengembangMateri;
        $this->__route = "or";
        $this->service = (new OrService())->setRoute("or");
        $this->textBook = $textBook;
        $this->OrService = $OrService;

        view()->share("__route", $this->__route);
        view()->share("__menu", "OR");
    }

    public function getData(Request $request)
    {
        return $this->service->getData($request);
    }

    public function getIndex()
    {
        return view("or.index");
    }

    public function getDetail($id)
    {

        $pm = PengembangMateri::
            select('pengembang_materi.id_pm', 
                    'semester.nama_semester', 
                    'matakuliah.mk_kode',
                    'matakuliah.mk_nama'
                   )
            ->where('pengembang_materi.id_pm',$id)
            ->join("semester", "semester.id_semester", "=", "pengembang_materi.id_semester")
            ->join("matakuliah", "matakuliah.id_matakuliah", "=", "pengembang_materi.id_matakuliah")
            ->first();

        $titleAction = $pm->nama_semester." • ".$pm->mk_kode." • ".$pm->mk_nama;
        $textBook = $this->textBook->where('id_pm',$id)->first();
        $or = OrModel::find($id);
        $metodePenilaian = MetodePenilaian::get();

        $metodePenilaianChecked = [];
        $capaianPembelajaran = [];
        $topic = [];
        $totalSubTopic = 0;
        $topic = Topic::where('id_pm',$id)->groupBy('topic')->get();
        
        return view("or.form", [
            "model" => $textBook,
            "metodePenilaianChecked" => $metodePenilaianChecked,
            "capaianPembelajaran" => $capaianPembelajaran,
            "metodePenilaian" => $metodePenilaian,
            "totalSubTopic" => $totalSubTopic,
            "topic" => $topic,
            "titleAction" => $titleAction,
            "or" => $or,
            "review_stat" => false,
        ]);
    }

    public function postDetail(OrRequest $request, $id)
    {
        $this->OrService->updateOrcreate($request, $id);
        toast('Data telah diupdate', 'success');

        return redirect($this->__route);
    }

    public function viewPdf($type, $file)
    {
        $pathToFile = Storage::url(contents_path().$type.'/'.$file);
        return Storage::response($pathToFile);
    }

    public function testEmail(Request $request)
    {

        sendEmail(18,'text-book','input',40);

        return 'email berhasil dikirim silahkan cek';
    }
}
