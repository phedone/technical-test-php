<?php

namespace App\Services\DocumentManager;

use App\Models\Document;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DocumentManagerService implements DocumentManagerServiceInterface
{
    /**
     * The FastAPI microservice URL
     *
     * @var string
     */
    protected string $apiUrl;

    /**
     * The private token for authentication
     *
     * @var string
     */
    protected string $privateToken;

    /**
     * The callback URL for the FastAPI to send documents back
     *
     * @var string
     */
    protected string $callbackUrl;

    /**
     * DocumentManagerService constructor.
     */
    public function __construct()
    {
        $this->apiUrl = config('services.document_api.url', 'http://localhost:8000');
        $this->privateToken = config('services.document_api.token', 'secret-token');
        $this->callbackUrl = config('app.url') . '/api/documents/callback';
    }

    /**
     * Scan folders for documents asynchronously
     *
     * @param User $user
     * @return bool
     */
    public function scanFolders(User $user): bool
    {
        try {
            $response = Http::withHeaders([
                'Private-Token' => $this->privateToken,
            ])->post("{$this->apiUrl}/scan-folders", [
                'user_id' => $user->id,
                'folders' => $user->folders,
                'callback_url' => $this->callbackUrl,
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Error scanning folders: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Store documents for a user
     *
     * @param array $documents
     * @param string $userId
     * @return bool
     */
    public function storeDocuments(array $documents, string $userId): bool
    {
        try {
            $user = User::find($userId);
            
            if (!$user) {
                Log::error("User not found: {$userId}");
                return false;
            }

            foreach ($documents as $document) {
                Document::updateOrCreate(
                    [
                        'uri' => $document['uri'],
                        'user_id' => $user->id
                    ],
                    [
                        'title' => $document['title'] ?? 'Untitled',
                        'user_id' => $user->id
                    ]
                );
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Error storing documents: ' . $e->getMessage());
            return false;
        }
    }
}
