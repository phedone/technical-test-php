<?php

use App\Actions\HandleProcessingDocumentsCallback;
use App\Actions\ScanFoldersForUser;
use Illuminate\Support\Facades\Route;

Route::post('/scan-folders/{userId}', ScanFoldersForUser::class);
Route::post('/documents/callback', HandleProcessingDocumentsCallback::class);
