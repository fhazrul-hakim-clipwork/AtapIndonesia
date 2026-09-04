<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'user_email',
        'user_name',
        'subject',
        'message',
        'status',
    ];

    public function replies()
    {
        return $this->hasMany(TicketReply::class);
    }
}
