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
        $lesson->delete();
        return $lesson;
    }

    public static function generateQuestions(Lesson $lesson)
    {
        
    }
}