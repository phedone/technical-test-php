<?php

use App\Actions\ScanFoldersForUser;
use Illuminate\Support\Facades\Route;

Route::post('/scan-folders/{userId}', ScanFoldersForUser::class);
