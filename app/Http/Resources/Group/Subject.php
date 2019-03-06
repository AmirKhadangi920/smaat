<?php

namespace App\Http\Resources\Group;

use Illuminate\Http\Resources\Json\JsonResource;

class Subject extends JsonResource
{
    private $breadcrumb;

    public function __construct($subject, $breadcrumb = null)
    {
        parent::__construct($subject);

        $this->breadcrumb = $breadcrumb;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'link'          => "/api/v1/category/{$this->slug}",
            'title'         => $this->title,
            'description'   => $this->description,
            'logo'          => $this->logo,
            'breadcrumb'    => $this->when( ($this->breadcrumb), function () {
                return $this->breadcrumb;
            }),
        ];
    }
}