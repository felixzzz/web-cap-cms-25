<?php
namespace App\Domains\Document\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DownloadDocument extends Model
{
    use HasFactory;
    protected $table = 'download_documents';

    protected $fillable = [
        'document_id',
        'lang',
        'path',
        'session_id',
        'status',
    ];
}
