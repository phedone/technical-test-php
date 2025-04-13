<?php

namespace App\Actions;

use App\Services\DocumentManager\DocumentManagerServiceInterface;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsController;

class HandleProcessingDocumentsCallback
{
    use AsAction;
    use AsController;

    /**
     * @var DocumentManagerServiceInterface
     */
    protected DocumentManagerServiceInterface $documentManager;

    /**
     * HandleProcessingDocumentsCallback constructor.
     *
     * @param DocumentManagerServiceInterface $documentManager
     */
    public function __construct(DocumentManagerServiceInterface $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    /**
     * Handle the action.
     *
     * @param array $documents
     * @param string|null $userId
     * @return bool
     */
    public function handle(array $documents, ?string $userId = null): bool
    {
        if (empty($userId) || empty($documents)) {
            return false;
        }

        return $this->documentManager->storeDocuments($documents, $userId);
    }

    /**
     * Handle the controller request.
     *
     * @param Request $request
     * @return mixed
     */
    public function asController(Request $request)
    {
        // Validate the request has the required authentication header
        if ($request->header('Private-Token') !== config('services.document_api.token')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Validate the request data
        $validated = $request->validate([
            'user_id' => 'required|string|exists:users,id',
            'documents' => 'required|array',
            'documents.*.uri' => 'required|string',
            'documents.*.title' => 'sometimes|string',
        ]);

        $result = $this->handle(
            documents: $validated['documents'],
            userId: $validated['user_id']
        );

        if ($result) {
            return response()->json(['message' => 'Documents processed successfully']);
        }

        return response()->json(['error' => 'Failed to process documents'], 500);
    }
}
