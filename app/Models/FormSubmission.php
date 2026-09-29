<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormSubmission extends Model
{
    protected $fillable = [
        'form_type',
        'page_id',
        'data',
        'ip_address',
        'user_agent',
        'is_read',
    ];

    protected $casts = [
        'data'    => 'array',
        'is_read' => 'boolean',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
