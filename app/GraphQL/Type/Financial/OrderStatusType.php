<?php

namespace App\GraphQL\Type\Financial;

use App\GraphQL\Type\BaseType;
use GraphQL\Type\Definition\Type;
use App\Models\Financial\OrderStatus;

class OrderStatusType extends BaseType
{
    protected $attributes = [
        'name' => 'OrderStatusType',
        'description' => 'A type',
        'model' => OrderStatus::class
    ];

    public function get_fields()
    {
        return $this->infoField() + [
            'is_mine' => $this->isMineField(),
            'creator' => $this->creator('order_status'),
            'color' => [
                'type' => Type::string()
            ],
            'audits' => $this->audits('order_status'),
            'is_active' => $this->acceptableField('order_status')
        ];
    }
}