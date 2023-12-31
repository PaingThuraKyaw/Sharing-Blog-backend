<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "description" => $this->description,
            "image" => asset('/storage/'. $this->image->image),
            "owner" => optional($this->user)->name ?? 'unknown' ,
            "created_post" =>  Carbon::parse($this->created_at)->diffForHumans() ,
            "created_at" => date_format(date_create($this->created_at), "Y-m-d" ),
            "updated_at" => date_format(date_create($this->updated_at), "Y-m-d" ),
            "user_id" => $this->user_id
        ];
    }
}
