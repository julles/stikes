<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Requests\UserRequest;
use App\Repositories\MasterDataRepository;

class UserManagementController extends Controller
{
    protected $dosen;
    protected $masterDataRepository;

    public function __construct(Dosen $dosen, MasterDataRepository $masterDataRepository)
    {
        $this->model = $dosen;
        $this->__route = "user-management";
        $this->service = (new UserService())->setRoute("user-management");
        $this->masterDataRepository = $masterDataRepository;
        view()->share("__route", $this->__route);
        view()->share("__menu", "User Management");
    }

    public function getData(Request $request)
    {
        return $this->service->getData($request);
    }

    public function getIndex()
    {
        return view("user-management.index");
    }

    public function getCreate()
    {
        return view("user-management.form", [
            "model" => $this->model,
            "titleAction" => "Create",
            "roles" => $this->masterDataRepository->roles(),
        ]);
    }

    public function postCreate(UserRequest $request)
    {
        $this->service->create($request);
        toast('Data telah disimpan', 'success');
        return redirect($this->__route);
    }

    public function getUpdate($id)
    {
        return view("user-management.form", [
            "model" => $this->model->findOrFail($id),
            "titleAction" => "Edit",
            "roles" => $this->masterDataRepository->roles(),
        ]);
    }

    public function postUpdate(UserRequest $request, $id)
    {
        $this->service->update($request, $id);
        toast('Data telah diupdate', 'success');
        return redirect($this->__route);
    }

    public function getDelete($id)
    {
        $model = $this->model->findOrFail($id);
        $this->service->delete($model);
        toast('Data telah dihapus', 'success');
        return redirect($this->__route);
    }
}
