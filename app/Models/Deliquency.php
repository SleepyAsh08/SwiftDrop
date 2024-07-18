<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Deliquency extends Model
{

    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    protected $table = 'deliquency';
    protected $guard_name = 'api';
    protected $guarded = [];

    public function committedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'committed_by');
    }
}
