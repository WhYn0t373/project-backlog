<?php

namespace App\Http\Controllers;

use App\Models\Conversion;
use App\Jobs\ProcessConversion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Handles file conversion workflow: upload, status polling, and download.
 */
class ConversionController extends Controller
{
    /**
     * Handles the file upload and initiates the conversion job.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|max:51200', // 50MB max
        ]);

        try {
            // Store the uploaded file in the public/uploads directory
            $originalPath = $request->file('file')->store('uploads', 'public');

            // Create a conversion record with status 'queued'
            $conversion = Conversion::create([
                'original_path' => $originalPath,
                'status' => 'queued',
                'progress' => 0,
            ]);

            // Dispatch the background job
            ProcessConversion::dispatch($conversion->id, $originalPath);

            return response()->json(['id' => $conversion->id], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Conversion upload failed: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            return response()->json([
                'error' => 'File upload failed.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Returns the current status and progress of a conversion job.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function status(int $id)
    {
        $conversion = Conversion::find($id);

        if (!$conversion) {
            return response()->json([
                'error' => 'Conversion not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => $conversion->status,
            'progress' => $conversion->progress,
        ], Response::HTTP_OK);
    }

    /**
     * Streams the converted file for download.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function download(int $id)
    {
        $conversion = Conversion::find($id);

        if (!$conversion) {
            return abort(Response::HTTP_NOT_FOUND, 'Conversion not found.');
        }

        if ($conversion->status !== 'completed' || empty($conversion->converted_path)) {
            return abort(Response::HTTP_BAD_REQUEST, 'Conversion not completed yet.');
        }

        if (!Storage::disk('public')->exists($conversion->converted_path)) {
            return abort(Response::HTTP_NOT_FOUND, 'Converted file not found.');
        }

        // Generate a user-friendly filename
        $originalName = basename($conversion->original_path);
        $convertedName = pathinfo($originalName, PATHINFO_FILENAME) . '_converted' . '.' . pathinfo($originalName, PATHINFO_EXTENSION);

        return Storage::disk('public')->download(
            $conversion->converted_path,
            $convertedName
        );
    }
}