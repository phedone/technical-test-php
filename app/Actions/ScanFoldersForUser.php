<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Lorisleiva\Actions\Concerns\AsAction;

class ScanFoldersForUser
{
    use AsAction;

    public function handle(User $user)
    {

    }

}
