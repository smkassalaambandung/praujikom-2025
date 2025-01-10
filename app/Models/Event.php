<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'event_date',
        'location',
        'user_id',
    ];

    protected $appends = ['dibuat_oleh'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDibuatOlehAttribute()
    {
        $user = User::find($this->user_id);
        return $user ? $user->name : 'Unknown';
    }

}
