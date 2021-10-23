<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserViewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'success' => true,
            'data' => [
                'name' => $this->name[$request->get('lang', 'ar')],
                'phone' => $this->phone,
                'email' => $this->email,
                'articles' => $this->whenLoaded('articles', ArticleResource::collection($this->articles))
            ]
        ];
    }
}
