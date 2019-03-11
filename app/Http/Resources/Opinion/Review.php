<?php

namespace App\Http\Resources\Opinion;

use Illuminate\Http\Resources\Json\JsonResource;

class Review extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        
        return [
            'id'                => $this->id,
            'link'              => "/api/v1/review/{$this->id}",
            'ranks'             => $this->ranks,
            'advantages'        => $this->advantages,
            'disadvantages'     => $this->disadvantages,
            'message'           => $this->message,
            'registered_at'     => $this->created_at,
            'product'           => $this->whenLoaded('product', function () {
                return [
                    'id' => $this->product->id,
                    'link' => "/api/v1/product/{$this->product->slug}",
                    'name' => $this->product->name,
                    'photos' => $this->product->photos,
                    'label' => $this->product->label,
                ];
            }),
            'writer'            => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'full_name' => $this->user->full_name,
                    'avatar' => $this->user->avatar
                ];
            }),
        ];
    }
}