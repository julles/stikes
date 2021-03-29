<?php

namespace App\Http\Controllers;

use App\Models\PengembangMateri;
use App\Models\OrModel;
use App\Models\TextBook;
use App\Models\Topic;
use App\Models\Kuis;
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
        // $topic = Topic::where('id_pm',$id)
        //                 ->groupBy('topic')
        //                 ->orderBy('sesi')
        //                 ->orderBy('topic')
        //                 ->get();
        $topic = Topic::where('id_pm',$id)
                            ->selectRaw('id_topic, sesi, CONCAT(topic," - ",sub_topic) as topic')
                            ->orderBy('sesi')
                            ->orderBy('topic')
                            ->get();

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

    public function question($id)
    {

        $kuis = Kuis::where('id_pm',$id)
                      ->select(
                                'id_kuis',
                                'durasi',
                                'isi_soal',
                                'jawaban',
                                'pilihan_a',
                                'pilihan_b',
                                'pilihan_c',
                                'pilihan_d',
                                'varian_latihan'
                               )
                      ->orderBy('varian_latihan','ASC')
                      ->orderBy('id_kuis','ASC')
                      ->get();

        $html_tabel = '<tr><td colspan="3" class="text-center"><strong>Belum ada soal</strong></td></td>';
        
        $html_variant = '<select class="form-control" id="varian_latihan">';

        $kuisData = [];
        
        if ($kuis->count() > 0) {
            $kuisArr = [];
            foreach ($kuis as $key => $v) {
                $kuisArr[$v['varian_latihan']] = array_values($kuis->where('varian_latihan',$v['varian_latihan'])->toArray());
                $kuisData[$v['id_kuis']] = $v;
            }

            $html_tabel = '';

                foreach ($kuisArr as $keyV => $v) {
                    $html_tabel .= '<tr>';
                        $html_tabel .= '<td class="text-center" rowspan="'.(count($v)+1).'">';
                            $html_tabel .= '<strong>'.$keyV.'</strong>';
                        $html_tabel .= '</td>';
                    $html_tabel .= '</tr>';

                    $no = 1;
                    foreach ($v as $key => $q) {
                        $html_tabel .= '<tr>';
                            $html_tabel .= '<td class="text-center">'.$no++.'</td>';
                            $html_tabel .= '<td>'.$q['isi_soal'].'</td>';
                            $html_tabel .= '<td width="10%" class="text-center">';
                            $html_tabel .= '<span onclick="editQ('.$q['id_kuis'].')" class="btn btn-success mr-2 mb-2"><i class="fa fa-edit"></i></span>';
                            $html_tabel .= '<span class="btn btn-danger mb-2" 
                                            onclick="delQ('.$q['id_kuis'].')"><i class="fa fa-trash"></i></span>';
                            $html_tabel .= '</td>';
                        $html_tabel .= '</tr>';
                    }
                }



                for ($i=1; $i < $q['varian_latihan']; $i++) { 
                    $html_variant .= '<option>'.$i.'</option>';
                }

                $html_variant .= '<option selected>'.($i+1).'</option>';

        }else{
            $html_variant .= '<option selected>1</option>';
        }
        
        $html_variant .= '</select>';

        $ret = [
            'html_tabel_kuis' => $html_tabel,
            'kuis_data' => $kuisData,
            'html_varian_kuis' => $html_variant
        ];
        
        return response()->json($ret);
    }

    public function questionStore(Request $request, $id)
    {
        $request['id_pm'] = $id;
        $save = Kuis::insert($request->all());
        return response()->json($save);
    }
}