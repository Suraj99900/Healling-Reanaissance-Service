<?php
namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Exception;

class AttachmentFileController extends Controller
{


    public function addAttchmentData(Request $request)
    {
        $oValidator = Validator::make($request->all(), [
            'attachment' => 'required|max:2000000',
            'attachment_name' => 'required',
            'video_id' => 'nullable',
        ]);

        if ($oValidator->fails()) {
            return response()->json(['error' => $oValidator->errors()], 400);
        }

        try {
            if ($request->hasFile('attachment')) {
                $uploaded = $request->file('attachment');
                // Store the uploaded video file in the 'public' disk
                $path = Storage::disk('spaces')->putFile('attachments', $uploaded, 'public');
                $sAttachmentUrl = Storage::disk('spaces')->url($path);
                // Create a new video record in the database
                $oAttachment = (new Attachment)->addAttchment(
                    $request->input('video_id'),
                    $request->input('attachment_name'),
                    $path,
                    $sAttachmentUrl
                );

                // Return a response with the video details
                return response()->json([
                    'message' => "Attachment uploaded successfully!",
                    'body' => $oAttachment,
                    'status' => 200,
                ], 200);
            }

            return response()->json([
                'error' => 'No file uploaded',
                'message' => "No file uploaded",
                'status' => 400
            ], 400);
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while uploading the attchment: ' . $e->getMessage()], 500);
        }
    }


    public function removedAttchment(Request $request, $id)
    {
        try {
            $oAttachment = (new Attachment)->removedAttchment($id);
            if (is_object($oAttachment) && isset($oAttachment->attachment_path)) {
                // Delete from Spaces
                Storage::disk('spaces')->delete($oAttachment->attachment_path);
                return response()->json([
                    'message' => "attchment deleted successfully!",
                    'status' => 200,
                ], 200);
            } else {
                return response()->json(['error' => 'attchment not found or cannot be deleted'], 404);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while removing the attchment: ' . $e->getMessage()], 500);
        }
    }

    public function fetchAllAttachmentDataByVideoId($videoId)
    {
        try {
            $attachments = (new Attachment)->fetchAttchmentByVideoId($videoId);

            foreach ($attachments as $att) {
                if ($att->attachment_path && Storage::disk('spaces')->exists($att->attachment_path)) {
                    // Copy file from Spaces to local storage if not already present
                    $localPath = 'attachments/' . basename($att->attachment_path);
                    if (!Storage::disk('public')->exists($localPath)) {
                        $fileContent = Storage::disk('spaces')->get($att->attachment_path);
                        Storage::disk('public')->put($localPath, $fileContent);
                    }
                    // Generate local download URL
                    $att->attachment_url = asset('storage/' . $localPath);
                } else {
                    $att->attachment_url = null; // or a default placeholder URL
                }
            }

            return response()->json([
                'message' => 'Attachments fetched successfully!',
                'body' => $attachments,
                'status' => 200,
            ], 200);

        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching attachments: ' . $e->getMessage()], 500);
        }
    }
}