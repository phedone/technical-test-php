<?php

use App\Actions\ScanFoldersForUser;
use App\Models\User;
use App\Services\DocumentManager\DocumentManagerServiceInterface;

beforeEach(function () {
    $this->user = User::factory()->create([
        'folders' => ['folder1', 'folder2']
    ]);
    
    // Create a mock for the DocumentManagerService
    $this->documentManagerMock = Mockery::mock(DocumentManagerServiceInterface::class);
    app()->instance(DocumentManagerServiceInterface::class, $this->documentManagerMock);
});

it('should run scan successfully', function () {
    // Set up the mock expectation
    $this->documentManagerMock->shouldReceive('scanFolders')
        ->once()
        ->with(Mockery::on(function ($user) {
            return $user->id === $this->user->id &&
                   count($user->folders) === 2;
        }))
        ->andReturn(true);

    // Run the action
    $result = ScanFoldersForUser::run(user: $this->user);
    
    // Verify the result
    expect($result)->toBeTrue();
});

it('should handle failed scan', function () {
    // Set up the mock to return false (failed scan)
    $this->documentManagerMock->shouldReceive('scanFolders')
        ->once()
        ->with(Mockery::on(function ($user) {
            return $user->id === $this->user->id;
        }))
        ->andReturn(false);

    // Run the action
    $result = ScanFoldersForUser::run(user: $this->user);
    
    // Verify the result
    expect($result)->toBeFalse();
});

it('test api route is working', function () {
    // Set up the mock expectation for the API route test
    $this->documentManagerMock->shouldReceive('scanFolders')
        ->once()
        ->andReturn(true);

    $response = $this->post('/api/scan-folders/' . $this->user->id);
    $response->assertStatus(200);
});
