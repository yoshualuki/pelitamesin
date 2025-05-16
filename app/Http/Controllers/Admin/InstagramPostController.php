<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class InstagramPostController extends Controller
{
    private $ayrshareApiKey = 'B311C7B6-A1444873-83219ED1-9EBC7850';
    private $ayrshareEndpoint = 'https://api.ayrshare.com/api/post';

    public function create()
    {
        return view('admin.instagram.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'description' => 'required|string|max:2200',
        ]);

        try {
            // Upload image to temporary storage
            $imagePath = $request->file('image')->store('public/instagram/temp');
            $publicImageUrl = Storage::url($imagePath);

            // For production, you should upload to a CDN or permanent storage
            // This example uses the temporary URL for demonstration
            $fullImageUrl = asset($publicImageUrl);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->ayrshareApiKey,
                'Content-Type' => 'application/json',
            ])->post($this->ayrshareEndpoint, [
                'post' => $request->description,
                'mediaUrls' => [$fullImageUrl],
                'platforms' => ['instagram']
            ]);

            // Clean up temporary file
            Storage::delete($imagePath);

            if ($response->successful()) {
                $responseData = $response->json();

                // Ensure the response matches the expected format
                $formattedResponse = [
                    'status' => $responseData['status'] ?? 'success',
                    'errors' => $responseData['errors'] ?? [],
                    'postIds' => $responseData['postIds'] ?? [[
                        'status' => 'success',
                        'id' => $responseData['id'] ?? 'N/A',
                        'postUrl' => $responseData['postUrl'] ?? '#',
                        'usedQuota' => $responseData['usedQuota'] ?? 1,
                        'platform' => 'instagram'
                    ]],
                    'id' => $responseData['id'] ?? uniqid(),
                    'refId' => $responseData['refId'] ?? md5(uniqid()),
                    'post' => $request->description,
                    'validate' => $responseData['validate'] ?? true
                ];

                return response()->json($formattedResponse);
            }

            return response()->json([
                'status' => 'error',
                'errors' => ['Failed to post to Instagram'],
                'post' => $request->description,
                'validate' => false
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'errors' => [$e->getMessage()],
                'post' => $request->description,
                'validate' => false
            ], 500);
        }
    }
}
