<?php 

namespace App\Services;
use App\Models\Kelas;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Auth;
class KelasServices
{
    public function getKelas($role)
    {
        if($role == 'lecturer') {
            $lecture_id = Auth::user()->lecturer->id;
            $data = Kelas::where('lecturer_id', $lecture_id)->get();
        }else {
            # code...
            $data = Kelas::all();
        }
        return $data;
    }

    public function storeData($data)
    {
        $user = Auth::user();
        $lecturer_id = $user->role->name == 'lecturer' ? $user->lecturer->id : $data['lecturer_id'];
        $kelas = Kelas::create([
            'uuid' => Str::uuid(),
            'lecturer_id' => $lecturer_id,
            'name' => $data['name'],
        ]);

        return $kelas;
    }

    public function updateData($data, $uuid)
    {
        $kelas = Kelas::where('uuid', $uuid)->firstOrFail();

        $role = Auth::user()->role->name;

        $updateData = [
            'name' => $data['name'],
        ];

        if ($role === 'admin' && isset($data['lecturer_id'])) {
            $updateData['lecturer_id'] = $data['lecturer_id'];
        }

        $kelas->update($updateData);

        return $kelas;
    }


    public function deleteData($uuid)
    {
        $kelas = Kelas::where('uuid', $uuid)->first();
        $kelas->delete();
        return $kelas;
    }

    public function delete_all($role)
    {
        if($role == 'lecturer') {
            $lecture_id = Auth::user()->lecturer->id;
            $kelas = DB::table('kelas')->where('lecturer_id', $lecture_id)->delete();
            return $kelas;
        }else {
            $kelas = DB::table('kelas')->delete();
        }
        return $kelas;
    }
}
?>