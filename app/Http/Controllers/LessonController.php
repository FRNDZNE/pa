<?php

namespace App\Http\Controllers;

use App\Services\LessonServices;
use App\Http\Requests\LessonRequest;
use App\Models\Lecturer;
use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class LessonController extends Controller
{
    public function __construct(LessonServices $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $role = Auth::user()->role->name;
        $lessons = $this->service->getData($role);
        $lecturer = Lecturer::all();
        return view('lessons.index', compact('lessons', 'lecturer'));
    }

    public function show(Lesson $lesson)
    {
        $existingQuestions = $lesson->materials()
            ->with(['questions.answers'])
            ->get()
            ->flatMap(function ($material) {
                return $material->questions->map(function ($question) use ($material) {
                    return [
                        'question'   => $question,
                        'answers'    => $question->answers,
                        'difficulty' => $material->difficulty,
                    ];
                });
            });

        return view('lessons.detail', compact('lesson', 'existingQuestions'));
    }

    public function store(LessonRequest $request)
    {
        $data = $request->validated();
        $this->service->storeData($data);
        return redirect()->back()->with('success', 'Materi berhasil ditambahkan');
    }

    public function update(LessonRequest $request, Lesson $lesson)
    {
        $data = $request->validated();
        $this->service->updateData($lesson, $data);
        return redirect()->back()->with('success', 'Materi berhasil diupdate');
    }

    public function destroy(Lesson $lesson)
    {
        $this->service->deleteData($lesson);
        return redirect()->back()->with('success', 'Materi berhasil dihapus');
    }

    public function generate_questions(Lesson $lesson, \Illuminate\Http\Request $request)
    {
        $request->validate([
            'total_question'   => 'required|integer|min:1',
            'material_path'    => 'required|array|min:1',
            'material_path.*'  => [
                'required',
                'file',
                'max:10240', // 10 MB
                function ($attribute, $value, $fail) {
                    $allowed = ['pdf', 'txt', 'doc', 'docx'];
                    $ext = strtolower($value->getClientOriginalExtension());
                    if (!in_array($ext, $allowed)) {
                        $fail("File {$attribute} harus berformat: pdf, txt, doc, atau docx.");
                    }
                },
            ],
            'percentage'       => 'required|array|min:1',
            'percentage.*'     => 'required|numeric|min:0|max:100',
            'difficulty'       => 'required|array|min:1',
            'difficulty.*'     => 'required|in:mudah,sedang,sulit',
        ]);

        try {
            $files        = $request->file('material_path');
            $percentages  = $request->input('percentage');
            $difficulties = $request->input('difficulty');
            $totalSoal    = (int) $request->input('total_question');
            $totalPersen  = array_sum($percentages);

            // ── 1. Hitung jumlah soal per baris distribusi ───────────────
            //    Setiap baris = 1 file materi + persentase + kesulitan
            $distributions = [];
            foreach ($files as $index => $file) {
                $persen     = (float) ($percentages[$index] ?? 0);
                $difficulty = $difficulties[$index] ?? 'mudah';
                $jumlah     = (int) round(($persen / $totalPersen) * $totalSoal);

                // Simpan file
                $path = $file->store('materials', 'public');

                // Baca konten (hanya untuk .txt) atau Upload PDF
                $ext     = strtolower($file->getClientOriginalExtension());
                $content = '';
                $fileUri = null;
                $mimeType = $file->getMimeType();

                if ($ext === 'txt') {
                    $content = mb_substr(file_get_contents(storage_path('app/public/' . $path)), 0, 4000);
                } else if ($ext === 'pdf') {
                    // Upload ke Gemini via Service
                    $serviceUpload = new \App\Services\GeminiFileService();
                    // get file.uri from response API
                    $uploadRes = $serviceUpload->uploadFile(storage_path('app/public/' . $path), $mimeType);
                    $fileUri = $uploadRes['file']['uri'] ?? null;
                }

                // Simpan record Material
                $material = \App\Models\Material::create([
                    'lesson_id'     => $lesson->id,
                    'material_path' => $path,
                    'difficulty'    => $difficulty,
                ]);

                if ($jumlah > 0) {
                    $distributions[] = [
                        'material'   => $material,
                        'content'    => $content,
                        'fileUri'    => $fileUri,
                        'mimeType'   => $mimeType,
                        'difficulty' => $difficulty,
                        'jumlah'     => $jumlah,
                    ];
                }
            }

            // ── 2. Susun prompt Gemini ────────────────────────────────────
            $distribusiTeks = '';
            foreach ($distributions as $d) {
                $distribusiTeks .= "- {$d['jumlah']} soal tingkat {$d['difficulty']}\n";
            }

            // Gabungkan semua konten materi (txt)
            $allContent = implode("\n\n---\n\n", array_filter(array_column($distributions, 'content')));

            $konteksMaterial = $allContent
                ? "Berikut isi materi yang diunggah (teks txt):\n\"\"\"\n{$allContent}\n\"\"\"\n\n"
                : "Topik materi: \"{$lesson->title}\". (Atau baca langsung file dokumen yang dilampirkan jika ada).\n\n";

            $prompt = $konteksMaterial
                . "Buatkan total {$totalSoal} soal pilihan ganda dalam Bahasa Indonesia "
                . "dengan distribusi tingkat kesulitan sebagai berikut:\n{$distribusiTeks}\n"
                . "Soal harus merupakan CAMPURAN dari dua tipe:\n"
                . "1. Soal TEORI/KONSEP: pertanyaan tentang pemahaman materi, definisi, atau cara kerja.\n"
                . "2. Soal PROBLEM SOLVING: berikan potongan kode nyata (PHP atau bahasa sesuai materi), "
                . "lalu tanyakan output-nya, nilai return-nya, letak bug-nya, atau cara memperbaikinya. "
                . "Potongan kode harus singkat (3-8 baris) dan relevan dengan materi.\n\n"
                . "Usahakan sekitar 40-50% soal adalah tipe problem solving.\n"
                . "Setiap soal memiliki 4 pilihan jawaban (A, B, C, D).\n"
                . "Tandai setiap soal dengan field:\n"
                . "- \"difficulty\": mudah / sedang / sulit\n"
                . "- \"type\": \"teori\" atau \"problem_solving\"\n"
                . "- Jika soal problem solving, sertakan potongan kode dalam field \"question\" menggunakan format backtick (```php ... ```) agar terbaca jelas.\n\n"
                . "PENTING: Jumlah soal dalam JSON array HARUS TEPAT {$totalSoal} soal, tidak lebih, tidak kurang.\n"
                . "Format output HANYA JSON array, tanpa teks tambahan, tanpa markdown code fence di luar array:\n"
                . "[{\"question\":\"...\",\"options\":[\"A. ...\",\"B. ...\",\"C. ...\",\"D. ...\"],\"answer\":\"A\",\"difficulty\":\"mudah\",\"type\":\"teori\"}]";

            // ── 3. Panggil Gemini API ─────────────────────────────────────
            $apiKey = config('gemini.api_key');
            $model  = 'gemini-2.5-flash';
            $url    = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent";

            $parts = [];
            // Jika ada file dokumen PDF yang berhasil diupload ke File API, masukkan fileUri
            foreach ($distributions as $d) {
                if (!empty($d['fileUri'])) {
                    $parts[] = [
                        'fileData' => [
                            'mimeType' => $d['mimeType'],
                            'fileUri'  => $d['fileUri'],
                        ]
                    ];
                }
            }
            // Tambahkan text prompt di bagian akhir parts
            $parts[] = ['text' => $prompt];

            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'x-goog-api-key' => $apiKey,
                'Content-Type'   => 'application/json',
            ])->timeout(120)->post($url, [
                'contents' => [
                    [
                        'parts' => $parts,
                    ],
                ],
            ]);

            if ($response->failed()) {
                return response()->json([
                    'error' => 'Gemini API error: ' . $response->body(),
                ], $response->status());
            }

            $text = $response->json('candidates.0.content.parts.0.text');

            // Strip markdown code fences jika ada
            $text = preg_replace('/^```json\s*/i', '', trim($text));
            $text = preg_replace('/^```\s*/i',     '', trim($text));
            $text = preg_replace('/```\s*$/i',     '', trim($text));

            $questionsData = json_decode(trim($text), true);

            if (!is_array($questionsData)) {
                return response()->json([
                    'error' => 'Format respons Gemini tidak valid.',
                    'raw'   => $text,
                ], 422);
            }

            // Pastikan jumlah soal tidak melebihi yang diminta
            $questionsData = array_slice($questionsData, 0, $totalSoal);

            // ── 4. Simpan soal ke tabel questions & question_answers ──────
            //    Petakan setiap soal ke material yang sesuai berdasarkan difficulty
            $materialByDifficulty = [];
            foreach ($distributions as $d) {
                $materialByDifficulty[$d['difficulty']] = $d['material'];
            }
            // Default ke material pertama jika difficulty tidak match
            $defaultMaterial = $distributions[0]['material'] ?? null;

            $savedQuestions = [];
            foreach ($questionsData as $q) {
                $diff = $q['difficulty'] ?? array_key_first($materialByDifficulty);
                $mat  = $materialByDifficulty[$diff] ?? $defaultMaterial;

                if (!$mat) continue;

                $question = \App\Models\Question::create([
                    'uuid'          => \Illuminate\Support\Str::uuid(),
                    'material_id'   => $mat->id,
                    'type'          => (isset($q['type']) && $q['type'] === 'problem_solving') ? 'solving' : 'teori',
                    'question_text' => $q['question'] ?? '',
                ]);

                $answers = [];
                foreach ($q['options'] ?? [] as $index => $optionText) {
                    $letters   = ['A', 'B', 'C', 'D'];
                    $letter    = $letters[$index] ?? chr(65 + $index);
                    $isCorrect = isset($q['answer']) && strtoupper(trim($q['answer'])) === $letter;

                    $answers[] = \App\Models\QuestionAnswer::create([
                        'question_id' => $question->id,
                        'answer_text' => $optionText,
                        'is_correct'  => $isCorrect,
                    ]);
                }

                $savedQuestions[] = [
                    'question'   => $question,
                    'answers'    => $answers,
                    'difficulty' => $diff,
                ];
            }

            return response()->json([
                'success'   => true,
                'total'     => count($savedQuestions),
                'questions' => $savedQuestions,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal generate: ' . $e->getMessage(),
            ], 500);
        }
    }
}


