<?php

namespace App\GraphQL\Props\Feature;

use App\Models\Feature\Size;
use App\Http\Resources\Feature\Size as SizeResource;
use App\ModelFilters\Feature\SizeFilter;
use App\Http\Requests\v1\Feature\SizeRequest;
use App\Http\Resources\Feature\SizeCollection;

trait SizeProps
{
    /**
     * Type of this controller for use in messages
     *
     * @var string
     */
    protected $type = 'size';

    /**
     * The model of this controller
     *
     * @var Model
     */
    protected $model = Size::class;

    /**
     * Resource of this controller respnoses
     *
     * @var [type]
     */
    protected $resource = SizeResource::class;

    /**
     * Resource Collection of this controller respnoses
     *
     * @var [type]
     */
    protected $collection = SizeCollection::class;
    
    /**
     * Filter class of this eloquent model
     *
     * @var ModelFilter
     */
    protected $filter = SizeFilter::class;
    
    /**
     * The request class of model for validation and authorization
     *
     * @var Request
     */
    protected $request = SizeRequest::class;
}