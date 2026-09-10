<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class MakeSpacesPublic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spaces:make-public {folder=hls : The directory to process (hls, thumbnails, attachments)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set public visibility on all objects in DigitalOcean Spaces bucket';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Increase memory limit for scanning large buckets
        ini_set('memory_limit', '512M');

        $folder = $this->argument('folder');
        $this->info("🔍 Streaming objects from DigitalOcean Spaces for folder '{$folder}'...");

        try {
            $disk = Storage::disk('spaces');
            
            // Use streaming generator listing to prevent memory exhaustion
            $contents = $disk->listContents($folder, true);

            $updated = 0;
            $failed = 0;

            Log::info("spaces:make-public started for folder '{$folder}'.");

            foreach ($contents as $object) {
                if ($object->isFile()) {
                    $path = $object->path();
                    try {
                        Log::info("Setting visibility for {$path} to public");
                        $disk->setVisibility($path, 'public');
                        $updated++;

                        if ($updated % 200 === 0) {
                            $this->info("  - Processed {$updated} files...");
                        }
                    } catch (\Exception $e) {
                        $failed++;
                        Log::error("Failed to set public ACL for {$path}: " . $e->getMessage());
                    }
                }
            }

            $this->info("✅ Successfully set public visibility on {$updated} files in '{$folder}' (Failed: {$failed}).");
            Log::info("spaces:make-public completed for '{$folder}'. Total updated: {$updated}.");

        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            Log::error("Error in spaces:make-public: " . $e->getMessage());
        }
    }
}
