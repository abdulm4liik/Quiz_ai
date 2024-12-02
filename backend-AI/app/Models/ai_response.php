<?php
namespace App\Models;

use App\Models\User;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ai_response extends Model
{


    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'response_type',
        'response_data',
        'marks'
    ];


    protected $casts = [
        'response_data' => 'array',  
        'marks' => 'array',  
    ];

 
 
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
