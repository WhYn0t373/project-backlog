<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Service responsible for converting uploaded files.
 *
 * In this mock implementation, conversion is simulated by copying the file to a
 * dedicated "converted" directory. Replace this logic with actual conversion
 * code as needed.
 */
class ConversionService
{
    /**
     * Convert the input file and return the new filename.
     *
     * @param string $inputPath Relative path to the uploaded file (e.g., 'conversions/uploads/file.jpg')
     * @return string The name of the converted file.
     *
     * @throws \Exception If the conversion fails.
     */
    public function convert(string $inputPath): string
    {
        $originalName = basename($inputPath);
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $newName = (string) Str::uuid() . '.' . $extension;
        $convertedPath = 'conversions/converted/' . $newName;

        try {
            // Ensure the destination directory exists
            if (!Storage::disk('local')->exists('conversions/converted')) {
                Storage::disk('local')->makeDirectory('conversions/converted');
            }

            // Copy the file to the converted directory
            Storage::disk('local')->copy($inputPath, $convertedPath);
        } catch (\Exception $e) {
            Log::error('File conversion failed', ['error' => $e->getMessage()]);
            throw $e;
        }

        return $newName;
    }
}