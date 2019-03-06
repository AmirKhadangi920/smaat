<?php

namespace App\Models\Group;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\Tags\HasTags;
use App\Models\Feature\{
    Brand,
    Color,
    Size,
    Warranty,
    Unit
};
use App\Models\Spec\Spec;
use App\Models\Product\Product;
use App\Traits\MultiLevel;

class Category extends Model implements AuditableContract
{
    use SoftDeletes, Sluggable, Auditable, HasTags, MultiLevel;

    /****************************************
     **             Attributes
     ***************************************/
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'title',
        'description',
        'depth',
        'logo',
        'scoring_feilds'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'depth'             => 'integer',
        'scoring_feilds'    => 'array',
        'logo'              => 'array'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'parent_id',
        'deleted_at',
        'created_at',
        'updated_at',
        'scoring_feilds'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];


    /****************************************
     **             Relations
     ***************************************/

    /**
     * Get all the brands that owned by the category
     */
    public function brands ()
    {
        return $this->hasMany(Brand::class);
    }

    /**
     * Get all the units that owned by the category
     */
    public function units ()
    {
        return $this->hasMany(Unit::class);
    }

    /**
     * Get all the colors that owned by the category
     */
    public function colors ()
    {
        return $this->hasMany(Color::class);
    }

    /**
     * Get all the sizes that owned by the category
     */
    public function sizes ()
    {
        return $this->hasMany(Size::class);
    }

    /**
     * Get all the warranties that owned by the category
     */
    public function warranties ()
    {
        return $this->hasMany(Warranty::class);
    }

    /**
     * Get all the products that owned by the category
     */
    public function products ()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Relation to orderpoint model with orderable
     * 
     * @return OrderPoint Model
     */
    
    public function order_points ()
    {
        return $this->morphMany(OrderPoint::class, 'orderable');
    }

    /**
     * Get all the specification table that owned by the category
     */
    public function specs ()
    {
        return $this->hasMany(Spec::class);
    }

    /**
     * Get all the child categories that owned by the category
     */
    public function childs ()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    
    /**
     * return parent category that can have many childs
     */
    public function parent ()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }


    /****************************************
     **              Methods
     ***************************************/

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}