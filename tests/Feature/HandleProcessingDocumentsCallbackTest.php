<?php

use App\Actions\HandleProcessingDocumentsCallback;
use App\Models\User;
use App\Services\DocumentManager\DocumentManagerServiceInterface;
use Illuminate\Testing\Fluent\AssertableJson;

beforeEach(function () {
    $this->user = User::factory()->create();
    
    // Sample documents data for testing
    $this->documents = [
        [
            'uri' => 'document1.pdf',
            'title' => 'Document 1'
        ],
        [
            'uri' => 'document2.pdf',
            'title' => 'Document 2'
        ]
    ];
    
    // Create a mock for the DocumentManagerService
    $this->documentManagerMock = Mockery::mock(DocumentManagerServiceInterface::class);
    app()->instance(DocumentManagerServiceInterface::class, $this->documentManagerMock);
});

it('should store documents successfully', function () {
    // Set up the mock expectation
    $this->documentManagerMock->shouldReceive('storeDocuments')
        ->once()
        ->with(
            Mockery::on(function ($docs) {
                return count($docs) === 2 && 
                       $docs[0]['uri'] === 'document1.pdf';
            }), 
            $this->user->id
        )
        ->andReturn(true);

    // Run the action
    $result = HandleProcessingDocumentsCallback::run(
        documents: $this->documents,
        userId: $this->user->id
    );
    
    // Verify the result
    expect($result)->toBeTrue();
});

it('should handle empty documents', function () {
    // Run the action with empty documents
    $result = HandleProcessingDocumentsCallback::run(
        documents: [],
        userId: $this->user->id
    );
    
    // Should return false when documents are empty
    expect($result)->toBeFalse();
});

it('should return false when user ID is empty', function () {
    // Run the action with empty user ID
    $result = HandleProcessingDocumentsCallback::run(
        documents: $this->documents,
        userId: ''
    );
    
    // Should return false when user ID is empty
    expect($result)->toBeFalse();
});

it('should handle failed document storage', function () {
    // Set up the mock to return false (failed storage)
    $this->documentManagerMock->shouldReceive('storeDocuments')
        ->once()
        ->andReturn(false);

    // Run the action
    $result = HandleProcessingDocumentsCallback::run(
        documents: $this->documents,
        userId: $this->user->id
    );
    
    // Verify the result
    expect($result)->toBeFalse();
});

it('callback route should store documents', function () {
    // Set up the mock for the API route test
    $this->documentManagerMock->shouldReceive('storeDocuments')
        ->once()
        ->andReturn(true);
        
    // Mock request with appropriate headers and data
    $response = $this->withHeaders([
        'Private-Token' => config('services.document_api.token', 'secret-token'),
    ])->postJson('/api/documents/callback', [
        'user_id' => $this->user->id,
        'documents' => $this->documents
    ]);

    // Assert successful response
    $response->assertStatus(200)
        ->assertJson(fn (AssertableJson $json) => 
            $json->has('message')
                 ->where('message', 'Documents processed successfully')
        );
});

it('callback route should require authentication', function () {
    // Send request without authentication token
    $response = $this->postJson('/api/documents/callback', [
        'user_id' => $this->user->id,
        'documents' => $this->documents
    ]);

    // Assert unauthorized response
    $response->assertStatus(401);
});
