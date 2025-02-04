<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name',
        'file_hash',
        'reference_date',
        'status'
    ];

    public function uploads()
    {
        return $this->hasMany(Upload::class, 'upload_history_id', 'id');
    }
}
