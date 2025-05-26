<?php
function main(array $args) : array
{
    // Retrieve the video path and output folder from the POST request
    $videoPath = $args['videoPath'] ?? null;
    $outputFolder = $args['outputFolder'] ?? null;

    if (!$videoPath || !$outputFolder) {
        return [
            "body" => "Missing required parameters: videoPath and outputFolder."
        ];
    }

    // Ensure the output folder exists
    if (!file_exists($outputFolder)) {
        if (!mkdir($outputFolder, 0775, true)) {
            return [
                "body" => "Failed to create output folder: $outputFolder"
            ];
        }
    }

    // Define the output HLS file and segment pattern
    $outputFolder = rtrim($outputFolder, DIRECTORY_SEPARATOR);
    $hlsFile = "$outputFolder" . DIRECTORY_SEPARATOR . "index.m3u8";
    $segmentPattern = "$outputFolder" . DIRECTORY_SEPARATOR . "segment%03d.ts";

    // Build the FFmpeg command
    $command = sprintf(
        'ffmpeg -i "%s" -codec:v libx264 -codec:a aac -hls_time 10 -hls_playlist_type vod -hls_segment_filename "%s" -start_number 0 "%s" 2>&1',
        $videoPath,
        $segmentPattern,
        $hlsFile
    );

    // Execute the command and capture output and return status
    exec($command, $output, $returnVar);

    if ($returnVar !== 0) {
        // Conversion failed; return error details.
        return [
            "body" => "FFmpeg conversion failed.",
            "error" => implode("\n", $output)
        ];
    }

    // If successful, return the HLS file path.
    return [
        "body" => "Video converted successfully! HLS file: $hlsFile"
    ];
}
