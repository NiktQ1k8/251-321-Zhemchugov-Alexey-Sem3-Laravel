<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name'])]
class Role extends Model
{
    public const READER = 'reader';

    public const MODERATOR = 'moderator';

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
