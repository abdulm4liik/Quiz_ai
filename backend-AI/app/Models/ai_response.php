<?php
namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ai_response extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'response_type',
        'response_data',
        'marks'
    ];

    /**
     * Set the response_data attribute, encoding it as JSON.
     *
     * @param mixed $value
     * @return void
     */
    public function setResponseDataAttribute($value)
    {
        $this->attributes['response_data'] = json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get the response_data attribute, decoding it from JSON.
     *
     * @param string $value
     * @return mixed
     */
    public function getResponseDataAttribute($value)
    {
        return json_decode($value, true);
    }

 
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
