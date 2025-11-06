<?php 

namespace App\Services;
use App\Models\Student;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Role;
use Illuminate\Support\Str;


Class StudentServices
{
    public function getStudent($kelas_id)
    {
        $data = Student::where('kelas_id', $kelas_id)->get();
        return $data;
    }

    public function storeData($user_id, $kelas_id)
    {
        
    }

    public function updateData($data, $uuid)
    {
    }

    public function deleteData($uuid)
    {
        
    }

    public function delete_all()
    {
        
    }
}


?>