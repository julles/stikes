<?php

namespace App\Http\Controllers;

use App\Models\PengembangMateri;
use App\Models\TextBook;
use App\Services\RpsService;
use Illuminate\Http\Request;

class RpsController extends Controller
{
    public function __construct(PengembangMateri $pengembangMateri, TextBook $textBook)
    {
        $this->pengembangMateri = $pengembangMateri;
        $this->__route = "rps";
        $this->service = (new RpsService())->setRoute("rps");
        $this->textBook = $textBook;

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
        $textBook = $this->textBook->findOrFail($id);

        return view("rps.form", [
            "model" => $textBook,
            "titleAction" => "Input RPS"
        ]);
    }
}
