<?php

namespace App\Services\DocumentManager;

use App\Models\User;

interface DocumentManagerServiceInterface
{
    /**
     * Scan folders for documents asynchronously
     *
     * @param User $user
     * @return bool
     */
    public function scanFolders(User $user): bool;

    /**
     * Store documents for a user
     *
     * @param array $documents
     * @param string $userId
     * @return bool
     */
    public function storeDocuments(array $documents, string $userId): bool;
}
