<?php 

namespace App\Services;
use App\Models\Lesson;
use Auth;
use Illuminate\Support\Str;

class LessonServices {
    public static function getData($role)
    {
        $role = Auth::user()->role;
        if ($role->name == 'admin') {
            $data = Lesson::all();
        } else {
            $data = Lesson::where('lecturer_id', Auth::user()->lecturer->id)->get();
        }
        return $data;
    }

    public static function storeData($data)
    {
        $data = Lesson::create([
            'uuid' => Str::uuid(),
            'lecturer_id' => $data['lecturer_id'],
            'title' => $data['title'],
        ]);

        return $data;
    }
    
    public static function updateData(Lesson $lesson, $data)
    {
        $lesson->update($data);
        return $lesson;
    }

    public static function deleteData(Lesson $lesson)
    {
        // Delete related materials files
        foreach ($lesson->materials as $material) {
            if ($material->material_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($material->material_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($material->material_path);
            }
        }

        $lesson->delete();
        return $lesson;
    }

    public static function generateQuestions(Lesson $lesson)
    {
        
    }
}