<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class GeminiFileService
{
    /**
     * Upload a file to Gemini File API.
     * 
     * @param string $filePath Full path to the file OR file contents.
     * @param string $mimeType e.g., 'application/pdf'
     * @return array Response from Gemini API containing file info (e.g. file.uri)
     */
    public function uploadFile(string $filePath, string $mimeType)
    {
        $apiKey = config('gemini.api_key');
        if (!$apiKey) {
            throw new Exception("Gemini API key is not configured.");
        }

        $url = "https://generativelanguage.googleapis.com/upload/v1beta/files";

        $response = Http::withHeaders([
            'x-goog-api-key' => $apiKey,
            'Content-Type'   => $mimeType,
        ])->timeout(120)
          ->withBody(file_get_contents($filePath), $mimeType)
          ->post($url);

        if ($response->failed()) {
            throw new Exception("Gagal mengunggah file ke Gemini: " . $response->body());
        }

        return $response->json();
    }
}