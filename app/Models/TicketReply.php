<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketReply extends Model
{
    protected $fillable = [
        'ticket_id',
        'replier_role',
        'replier_name',
        'message',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
