<?php

namespace Tests\Fakes;

use App\Models\User;
use App\Services\DocumentManager\DocumentManagerServiceInterface;

class FakeDocumentManagerService implements DocumentManagerServiceInterface
{
    /**
     * Whether storeDocuments should return success
     */
    private bool $storeDocumentsSuccess = true;

    /**
     * The documents that were most recently stored
     */
    private array $storedDocuments = [];

    /**
     * The user ID that documents were stored for
     */
    private ?string $storedUserId = null;

    /**
     * How many times storeDocuments was called
     */
    private int $storeDocumentsCallCount = 0;

    /**
     * Whether scanFolders should return success
     */
    private bool $scanFoldersSuccess = true;

    /**
     * How many times scanFolders was called
     */
    private int $scanFoldersCallCount = 0;

    /**
     * Store documents for a user
     *
     * @param array $documents
     * @param string $userId
     * @return bool
     */
    public function storeDocuments(array $documents, string $userId): bool
    {
        $this->storeDocumentsCallCount++;
        $this->storedDocuments = $documents;
        $this->storedUserId = $userId;
        return $this->storeDocumentsSuccess;
    }

    /**
     * Scan folders for documents asynchronously
     *
     * @param User $user
     * @return bool
     */
    public function scanFolders(User $user): bool
    {
        $this->scanFoldersCallCount++;
        return $this->scanFoldersSuccess;
    }

    /**
     * Set whether storeDocuments should succeed
     *
     * @param bool $success
     * @return self
     */
    public function shouldStoreDocumentsSucceed(bool $success): self
    {
        $this->storeDocumentsSuccess = $success;
        return $this;
    }

    /**
     * Set whether scanFolders should succeed
     *
     * @param bool $success
     * @return self
     */
    public function shouldScanFoldersSucceed(bool $success): self
    {
        $this->scanFoldersSuccess = $success;
        return $this;
    }

    /**
     * Assert storeDocuments was called exactly $times times
     *
     * @param int $times
     * @return self
     */
    public function assertStoreDocumentsCalledTimes(int $times): self
    {
        if ($this->storeDocumentsCallCount !== $times) {
            throw new \PHPUnit\Framework\ExpectationFailedException(
                "Expected storeDocuments to be called $times times, but was called {$this->storeDocumentsCallCount} times"
            );
        }
        return $this;
    }

    /**
     * Assert storeDocuments was called with the expected documents and userId
     *
     * @param array $expectedDocuments
     * @param string $expectedUserId
     * @return self
     */
    public function assertStoreDocumentsCalledWith(array $expectedDocuments, string $expectedUserId): self
    {
        if ($this->storedUserId !== $expectedUserId) {
            throw new \PHPUnit\Framework\ExpectationFailedException(
                "Expected storeDocuments to be called with userId '$expectedUserId', but got '{$this->storedUserId}'"
            );
        }

        if (count($expectedDocuments) !== count($this->storedDocuments)) {
            throw new \PHPUnit\Framework\ExpectationFailedException(
                "Expected storeDocuments to be called with " . count($expectedDocuments) . " documents, but got " . count($this->storedDocuments)
            );
        }

        return $this;
    }

    /**
     * Assert storeDocuments was called with documents matching a specific condition
     *
     * @param callable $condition A function that takes the documents array and returns bool
     * @return self
     */
    public function assertStoreDocumentsCalledWithDocumentsMatching(callable $condition): self
    {
        if (!$condition($this->storedDocuments)) {
            throw new \PHPUnit\Framework\ExpectationFailedException(
                "The stored documents did not match the expected condition"
            );
        }
        return $this;
    }

    /**
     * Assert scanFolders was called exactly $times times
     *
     * @param int $times
     * @return self
     */
    public function assertScanFoldersCalledTimes(int $times): self
    {
        if ($this->scanFoldersCallCount !== $times) {
            throw new \PHPUnit\Framework\ExpectationFailedException(
                "Expected scanFolders to be called $times times, but was called {$this->scanFoldersCallCount} times"
            );
        }
        return $this;
    }
}
