<?php

use App\Actions\ScanFoldersForUser;
use App\Models\User;
use App\Services\DocumentManager\DocumentManagerServiceInterface;
use Tests\Fakes\FakeDocumentManagerService;

beforeEach(function () {
    $this->user = User::factory()->create([
        'folders' => ['folder1', 'folder2']
    ]);
    
    // Create a fake for the DocumentManagerService
    $this->documentManager = new FakeDocumentManagerService();
    app()->instance(DocumentManagerServiceInterface::class, $this->documentManager);
});

it('should run scan successfully', function () {
    // Configure the fake to return success
    $this->documentManager->shouldScanFoldersSucceed(true);

    // Run the action
    $result = ScanFoldersForUser::run(user: $this->user);
    
    // Verify the result
    expect($result)->toBeTrue();
    
    // Verify the fake was called correctly
    $this->documentManager->assertScanFoldersCalledTimes(1);
});

it('should handle failed scan', function () {
    // Configure the fake to return failure
    $this->documentManager->shouldScanFoldersSucceed(false);

    // Run the action
    $result = ScanFoldersForUser::run(user: $this->user);
    
    // Verify the result
    expect($result)->toBeFalse();
    
    // Verify the fake was called
    $this->documentManager->assertScanFoldersCalledTimes(1);
});

it('test api route is working', function () {
    // Configure the fake to return success
    $this->documentManager->shouldScanFoldersSucceed(true);

    $response = $this->post('/api/scan-folders/' . $this->user->id);
    $response->assertStatus(200);
    
    // Verify the fake was called
    $this->documentManager->assertScanFoldersCalledTimes(1);
});
