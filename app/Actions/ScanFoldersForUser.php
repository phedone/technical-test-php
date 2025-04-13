<?php

namespace App\Actions;

use App\Models\User;
use App\Services\DocumentManager\DocumentManagerServiceInterface;
use Illuminate\Support\Facades\Http;
use Lorisleiva\Actions\Concerns\AsAction;

class ScanFoldersForUser
{
    use AsAction;

    /**
     * @var DocumentManagerServiceInterface
     */
    protected DocumentManagerServiceInterface $documentManager;

    /**
     * ScanFoldersForUser constructor.
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
     * @param User $user
     * @return bool
     */
    public function handle(User $user): bool
    {
        return $this->documentManager->scanFolders($user);
    }

}
