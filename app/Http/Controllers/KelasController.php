<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\KelasServices;
use App\Services\AdminLecturerServices;
use Auth;


class KelasController extends Controller
{
    public function __construct(KelasServices $kelas, AdminLecturerServices $lecturer)
    {
        $this->kelas = $kelas;
        $this->lecturer = $lecturer;
    }

    public function index()
    {
        $role = Auth::user()->role->name;
        $data['kelas'] = $this->kelas->getKelas($role);
        $data['lecturers'] = $this->lecturer->getLecture();
        return view("{$role}.data-kelas.index", compact('data'));
    }

    public function store(Request $request)
    {
        try {
            $this->kelas->storeData($request->all());
            return redirect()->back()->with('success', 'Data kelas berhasil ditambahkan.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Data kelas gagal ditambahkan. Error: ' . $th->getMessage());
        }
    }

    public function update(Request $request, $uuid)
    {
        try {
            //code...
            $this->kelas->updateData($request->all(), $uuid);
            return redirect()->back()->with('success', 'Data kelas berhasil diubah.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('error', 'Data kelas gagal diubah. Error: ' . $th->getMessage());
        }
    }

    public function destroy($uuid)
    {
        try {
            //code...
            $this->kelas->deleteData($uuid);
            return redirect()->back()->with('success', 'Data kelas berhasil dihapus.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('error', 'Data kelas gagal dihapus. Error: ' . $th->getMessage());
        }
    }

    public function destroy_all()
    {
        try {
            //code...
            $role = Auth::user()->role->name;
            $this->kelas->delete_all($role);
            return redirect()->back()->with('success', 'Semua data kelas berhasil dihapus.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('error', 'Semua data kelas gagal dihapus. Error: ' . $th->getMessage());
        }
    }
}
