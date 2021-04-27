<?php

namespace App\Http\Controllers;

use App\Models\PengembangMateri;
use App\Models\TextBook;
use App\Models\Semester;
use App\Models\MataKuliah;
use App\Models\Rps;
use App\Models\OrFileModel;
use App\Models\Topic;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Storage;
use DB;

class ReportController extends Controller
{
    public function __construct(PengembangMateri $pengembangMateri, TextBook $textBook, ReportService $ReportService)
    {
        $this->pengembangMateri = $pengembangMateri;
        $this->__route = "report";
        $this->service = (new ReportService())->setRoute("report");
        $this->textBook = $textBook;
        $this->ReportService = $ReportService;

        view()->share("__route", $this->__route);
        view()->share("__menu", "Report");
    }

    public function kemajuanPerkembanganData(Request $request)
    {
        return $this->service->getDataKP($request);
    }

    public function statusSilabusData(Request $request)
    {
        return $this->service->getDataSS($request);
    }

    public function kemajuanPerkembangan()
    {
        return view('report.kemajuan_perkembangan.index');
    }

    public function kemajuanPerkembanganDetail($id_semester, $id_matakuliah)
    {
        $semester = Semester::find($id_semester)['nama_semester'];
        $mataKuliah = MataKuliah::find($id_matakuliah)['mk_nama'];

        $title = $semester.' • '.$mataKuliah;
        
        return view('report.kemajuan_perkembangan.detail')
                    ->with('title',$title);
    }

    public function statusSilabus()
    {
        return view('report.status_silabus.index');
    }

    public function statusSilabusDetail($id_semester, $id_matakuliah)
    {
        $semester = Semester::find($id_semester)['nama_semester'];
        $mataKuliah = MataKuliah::find($id_matakuliah)['mk_nama'];

        $title = $semester.' • '.$mataKuliah;

        //report
        $pm_id = PengembangMateri::where('id_semester',$id_semester)
                                  ->where('id_matakuliah',$id_matakuliah)
                                  ->pluck('id_pm');

        $mediaPembelajaranAV = Rps::whereIn('id',$pm_id)
                    ->select(
                             'id',
                             'media_pembelajaran'
                            )
                    ->where('media_pembelajaran','!=','')
                    ->pluck('id','id');

        $CPAV = Rps::whereIn('id',$pm_id)
                    ->select(
                             'id',
                             'capaian_pembelajaran'
                            )
                    ->where('capaian_pembelajaran','!=','')
                    ->pluck('id','id');

        $topic = Topic::whereIn('topic.id_pm',$pm_id)
                        ->leftJoin("text_book", "text_book.id_pm", "=", "topic.id_pm")
                        ->select('topic.*','text_book.title as text_book')
                        ->groupBy('topic')
                        ->orderBy('sesi')
                        ->get();

        $subTopicAv = Topic::whereIn('id_pm',$pm_id)
                        ->where('sub_topic','!=','')
                        ->selectRaw('id_topic')
                        ->get()
                        ->pluck('id_topic','id_topic')
                        ->toArray();

        $orFileAV = OrFileModel::whereIn('id_pm',$pm_id)
                        ->get()
                        ->pluck('topic_id','topic_id')
                        ->toArray();

        $report = [];
        foreach ($topic as $key => $v) {
            $report[$key]['id_topic'] = $v['id_topic'];
            $report[$key]['sesi'] = $v['sesi'];
            $report[$key]['text_book'] = $v['text_book'];
            $report[$key]['topic'] = $v['topic'];
            $report[$key]['sub_topic'] = (isset($subTopicAv[(int)$v['id_topic']]) ? 1 : 0);
            $report[$key]['media_keterangan'] = (isset($orFileAV[(int)$v['id_topic']]) ? 1 : 0);
            $report[$key]['media_pembelajaran'] = (isset($mediaPembelajaranAV[(int)$v['id_pm']]) ? 1 : 0);
            $report[$key]['cp'] = (isset($CPAV[(int)$v['id_pm']]) ? 1 : 0);
            
        }

        return view('report.status_silabus.detail')
                    ->with('title',$title)
                    ->with('report',$report);
    }
}