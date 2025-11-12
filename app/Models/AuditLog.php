<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = ['import_id', 'record_id', 'column', 'old_value', 'new_value'];
}
