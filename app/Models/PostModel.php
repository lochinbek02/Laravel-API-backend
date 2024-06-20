<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostModel extends Model
{
    use HasFactory;
    protected $fillable=['user_id','min_content','content','photo_path'];
    protected $table = 'post_models';
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
