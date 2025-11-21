<?php

namespace App\Domains\Post\Models;

use Illuminate\Database\Eloquent\Model;
use App\Domains\PostCategory\Models\Category;

class ContactUs extends Model {
    protected $table = 'contact_us';
    protected $fillable = [
        'type',
        'firstname',
        'lastname',
        'country',
        'topic_id',
        'email',
        'message'
    ];
    public function topic() {
        return $this->belongsTo(Category::class, 'topic_id','id');
    }
}
