<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
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
            'id' => $this->id,
            'title' => @$this->title[$request->get('lang', 'ar')],
            'content' => @$this->content[$request->get('lang', 'ar')],
            'created_at' => $this->created_at,
        ];
    }
}
