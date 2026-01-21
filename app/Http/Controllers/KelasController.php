<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;


class KelasController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        return view("data-kelas.index");
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
