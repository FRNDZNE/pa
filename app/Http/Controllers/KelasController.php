<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use Auth;


class KelasController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {   
        if (Auth::user()->role->name == 'admin') {
            $data = Kelas::all();
        }else {
            $data = Kelas::where('lecturer_id', Auth::user()->lecturer->id)->get();
        }
        return view("data-kelas.index",compact('data'));
    }

    public function store(Request $request)
    {
        
    }

    public function update(Request $request, $uuid)
    {
        
    }

    public function destroy($uuid)
    {
        
    }

}
