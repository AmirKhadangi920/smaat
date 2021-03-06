<?php

namespace App\GraphQL\Type\Group;

use App\Models\Group\Category;
use App\GraphQL\Type\BaseType;
use GraphQL\Type\Definition\Type;

class CategoryType extends BaseType
{
    protected $attributes = [
        'name' => 'category',
        'description' => 'A type',
        'model' => Category::class
    ];

    public function get_fields()
    {
        return [
            'is_mine' => $this->isMineField(),
            'creator' => $this->creator('category'),
            'scoring_fields' => [
                'type' => Type::listOf( \GraphQL::type('scoring_field') )
            ],
            'slug' => [
                'type' => Type::string(),
                'selectable' => false
            ],
            'title' => [
                'type' => Type::string(),
                'selectable' => false
            ],
            'description' => [
                'type' => Type::string(),
                'selectable' => false
            ],
            'icon' => [
                'type' => Type::string(),
            ],
            'parent' => $this->relationItemField('category'),
            'logo' => $this->imageField(),
            'childs' => $this->relationListField('category'),
            'brands' => $this->relationListField('brand'),
            'colors' => $this->relationListField('color'),
            'sizes' => $this->relationListField('size'),
            'warranties' => $this->relationListField('warranty'),
            'spec' => [
                'type'  => \GraphQL::type('spec'),
                'query' => $this->getRelationQuery('spec', 'is_active', 'read-spec'),
                'resolve' => function($data) {
                    return $data->spec->count() ? $data->spec->first() : null;
                }
            ],
            'audits' => $this->audits('category'),
            'is_active' => $this->acceptableField('category')
        ];
    }
}