<?php

namespace App\Http\Controllers;

use App\Services\ConversionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Response;

/**
 * Handles file upload, conversion, and download actions.
 */
class ConversionController extends Controller
{
    /**
     * Process the uploaded file and initiate conversion.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Services\ConversionService $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request, ConversionService $service)
    {
        // Validate the incoming file
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:10240|mimetypes:image/jpeg,image/png,application/pdf,application/zip,application/x-zip-compressed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        try {
            // Store the uploaded file in the local disk under conversions/uploads
            $path = $request->file('file')->store('conversions/uploads');

            // Perform the conversion (simulated by copying the file)
            $convertedFileName = $service->convert($path);

            // Build the download URL
            $downloadUrl = URL::to('/conversion/download/' . $convertedFileName);

            return response()->json([
                'success' => true,
                'download_url' => $downloadUrl,
            ]);
        } catch (\Exception $e) {
            Log::error('Conversion upload failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during upload or conversion.',
            ], 422);
        }
    }

    /**
     * Serve the converted file for download.
     *
     * @param string $file The filename of the converted file.
     * @return \Illuminate\Http\Response
     */
    public function download($file)
    {
        $filePath = storage_path('app/conversions/converted/' . $file);

        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->download($filePath);
    }
}