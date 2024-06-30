<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query;
use Illuminate\Support\Facades\DB;

class AppPost extends Model
{
    use SoftDeletes;

    protected $table = "app_post";

    protected $fillable = [
        'id',
        'body',
        'added_on',
        'status',
        'deleted'
    ];

    // Create a new post
    public static function addPost($htmlContent)
    {
        try {
            // Extract images from the HTML content
            $images = self::extractImages($htmlContent);

            // Upload images and get the URLs
            $uploadedUrls = [];
            foreach ($images as $imageSrc) {
                $uploadedUrls[] = self::uploadImage($imageSrc);
            }

            // Replace local paths with URLs in the HTML content
            $htmlContent = self::replaceImagePaths($htmlContent, $images, $uploadedUrls);

            // Save the HTML content to the database
            $oPostData = self::create([
                'body' => $htmlContent,
                'added_on' => now(),
                'status' => 1,
                'deleted' => 0,
            ]);

            return $oPostData;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function replaceImagePaths($htmlContent, $images, $uploadedUrls) {
        foreach ($images as $index => $imageSrc) {
            $htmlContent = str_replace($imageSrc, $uploadedUrls[$index], $htmlContent);
        }
    
        return $htmlContent;
    }

    public static function uploadImage($imageSrc) {
        // Check if image is base64 encoded
        if (strpos($imageSrc, 'data:image') !== false) {
            list($type, $data) = explode(';', $imageSrc);
            list(, $data) = explode(',', $data);
            $data = base64_decode($data);
            $extension = explode('/', $type)[1];
        } else {
            // If imageSrc is a local path
            $data = file_get_contents($imageSrc);
            $extension = pathinfo($imageSrc, PATHINFO_EXTENSION);
        }
    
        $fileName = uniqid() . '.' . $extension;
        $filePath = 'uploads/' . $fileName;
    
        // Save the file to the desired location (e.g., public/uploads)
        file_put_contents(public_path($filePath), $data);
    
        return url($filePath); // Return the URL of the uploaded image
    }

    public static function extractImages($htmlContent) {
        $dom = new \DOMDocument();
        @$dom->loadHTML($htmlContent);
    
        $images = [];
        foreach ($dom->getElementsByTagName('img') as $img) {
            $src = $img->getAttribute('src');
            if (strpos($src, 'data:image') !== false || strpos($src, '/data/') !== false) {
                $images[] = $src;
            }
        }
    
        return $images;
    }
    
    
    


    // Fetch a single post by id
    public static function fetchPost($id)
    {
        try {
            $oPostData = self::where('id', $id)->where('status', 1)->where('deleted', 0)->first();
            return $oPostData;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    // Fetch all posts
    public static function fetchAllPosts()
    {
        try {
            $aPostData = self::where('status', 1)->where('deleted', 0)->get();
            return $aPostData;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    // Update a post by id
    public static function updatePost($id, $htmlContent)
    {
        try {
            // Extract images from the HTML content
            $images = self::extractImages($htmlContent);

            // Upload images and get the URLs
            $uploadedUrls = [];
            foreach ($images as $imageSrc) {
                $uploadedUrls[] = self::uploadImage($imageSrc);
            }

            // Replace local paths with URLs in the HTML content
            $htmlContent = self::replaceImagePaths($htmlContent, $images, $uploadedUrls);

            // Update the post in the database
            $oPostData = self::where('id', $id)->where('status', 1)->where('deleted', 0)->firstOrFail();
            $oPostData->update([
                'body' => $htmlContent,
                'updated_at' => now(),
            ]);

            return $oPostData;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    // Delete a post by id (soft delete)
    public static function deletePost($id)
    {
        try {
            $oPostData = self::where('id', $id)->where('status', 1)->where('deleted', 0)->first();
            if ($oPostData) {
                $oPostData->update([
                    'deleted' => 1,
                ]);
            }
            return $oPostData;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
