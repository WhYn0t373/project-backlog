<?php

namespace App\Jobs;

use App\Models\Conversion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Background job that simulates a file conversion process.
 *
 * The job updates the conversion status and progress,
 * copies the original file to a new location with a suffix,
 * and handles failures gracefully.
 */
class ProcessConversion implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The conversion ID associated with this job.
     *
     * @var int
     */
    protected $conversionId;

    /**
     * Path of the original uploaded file (relative to the public disk).
     *
     * @var string
     */
    protected $originalPath;

    /**
     * Create a new job instance.
     *
     * @param int $conversionId
     * @param string $originalPath
     */
    public function __construct(int $conversionId, string $originalPath)
    {
        $this->conversionId = $conversionId;
        $this->originalPath = $originalPath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $conversion = Conversion::find($this->conversionId);

        if (!$conversion) {
            Log::error("Conversion job failed: Conversion ID {$this->conversionId} not found.");
            return;
        }

        try {
            // Mark as processing
            $conversion->status = 'processing';
            $conversion->progress = 0;
            $conversion->save();

            // Simulate conversion with incremental progress updates
            foreach ([25, 50, 75, 100] as $percent) {
                sleep(1); // Simulate work
                $conversion->progress = $percent;
                $conversion->save();
            }

            // Determine the destination path
            $originalFilename = basename($this->originalPath);
            $convertedFilename = pathinfo($originalFilename, PATHINFO_FILENAME) . '_converted.' . pathinfo($originalFilename, PATHINFO_EXTENSION);
            $convertedPath = 'conversions/' . $convertedFilename;

            // Ensure the target directory exists
            Storage::disk('public')->makeDirectory('conversions');

            // Copy the file to simulate conversion
            Storage::disk('public')->copy($this->originalPath, $convertedPath);

            // Update conversion record with the new path and status
            $conversion->converted_path = $convertedPath;
            $conversion->status = 'completed';
            $conversion->progress = 100;
            $conversion->save();

            Log::info("Conversion job completed: {$this->conversionId}");
        } catch (\Exception $e) {
            Log::error("Conversion job failed: {$e->getMessage()}", [
                'exception' => $e,
                'conversion_id' => $this->conversionId,
            ]);

            // Mark the conversion as failed
            $conversion->status = 'failed';
            $conversion->save();
        }
    }
}