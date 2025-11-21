<?php

namespace App\Domains\Document\Models;

use App\Domains\Auth\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class Document extends Model
{
    use SoftDeletes;

    protected $table = 'documents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'image',
        'page',
        'category',
        'section',
        'document_name_id',
        'document_name_en',
        'document_type',
        'document_file_id',
        'document_file_en',
        'image',
        'alt_image',
        'alt_image_en',
        'published_at',
        'description_id',
        'description_en',
        'language',
        'author',
        'publisher',
        'release_year',
        'pages',
        'format',
        'sort'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'uploaded_by' => 'integer',
    ];
    public function category()
    {
        return $this->belongsTo(DocumentCategory::class, 'category_id', 'id');
    }

}