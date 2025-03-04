<?php

use App\Actions\HandleProcessingDocumentsCallback;

it('should run callback', function () {
    HandleProcessingDocumentsCallback::run(documents: []);
    expect(true)->toBeTrue();
});
