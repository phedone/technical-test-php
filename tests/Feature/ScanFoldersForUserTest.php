<?php

use App\Actions\ScanFoldersForUser;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('should run scan', function () {
    ScanFoldersForUser::run(user: $this->user);
    expect(true)->toBeTrue();
});

it('test api route is working', function () {
    $response = $this->post('/api/scan-folders/' . $this->user->id);
    $response->assertStatus(200);
});
