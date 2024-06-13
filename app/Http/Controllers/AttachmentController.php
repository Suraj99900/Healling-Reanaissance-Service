<?php
namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Exception;

class AttachmentController extends Controller
{


    public function addAttchment(Request $request)
    {
        $oValidator = Validator::make($request->all(), [
            'attachment' => 'required|file|max:200000',
            'attachment_name' => 'required|string|max:25500',
            'video_id' => 'nullable|string',
        ]);

        if ($oValidator->fails()) {
            return response()->json(['error' => $oValidator->errors()], 400);
        }

        try {
            if ($request->hasFile('attachment')) {
                // Store the uploaded video file in the 'public' disk
                $sAttachmentPath = $request->file('attachment')->store('attachment', 'public');
                $sAttachmentUrl = Storage::disk('public')->url($sAttachmentPath);
                // Create a new video record in the database
                $oAttachment = (new Attachment)->addAttchment(
                    $request->input('video_id'),
                    $request->input('attachment_name'),
                    $sAttachmentPath,
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
            $oAttachment = (new Attachment)->deleteVideoById($id);
            if ($oAttachment) {
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

    public function fetchAllAttachmentByVideoId($id)
    {
        try {
            $oAttachment = (new Attachment)->fetchAttchmentByVideoId($id);
            if ($oAttachment) {
                return response()->json([
                    'message' => "Fetch attchment successfully!",
                    'status' => 200,
                ], 200);
            } else {
                return response()->json(['error' => 'Attchment not found or cannot be deleted'], 404);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching the attchment: ' . $e->getMessage()], 500);
        }
    }
}