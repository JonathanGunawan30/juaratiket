<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketPhoto extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
      'photo',
      'ticket_id'
    ];

    public function tickets(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

}
