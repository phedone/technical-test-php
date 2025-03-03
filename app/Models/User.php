<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\TraitUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    use HasFactory;
    use TraitUuid;

    protected $fillable = [
        'folders'
    ];

    protected $casts = [
        'folders' => 'array'
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
}
