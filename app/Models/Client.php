<?php

namespace App\Models;

use Database\Factories\ClientFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Client extends Model
{
    /** @use HasFactory<ClientFactory> */
    use Notifiable, HasFactory;

    protected $fillable = [
        'name',
        'email',
    ];

    public function animals(): HasMany
    {
        return $this->hasMany(Animal::class);
    }
}
