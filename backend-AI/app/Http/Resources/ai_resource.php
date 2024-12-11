<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\UsersResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ai_resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id, 
            'title' => $this->title,
            'response_type' => $this->response_type,
            'response_data' => $this->response_data, 
            'marks' => $this->marks,
            'created_at' => $this->created_at,
        ];
    }
}
