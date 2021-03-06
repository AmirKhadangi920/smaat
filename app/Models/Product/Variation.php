<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use App\Models\Feature\{
    Size,
    Color,
    Warranty
};
use App\Models\Financial\{ OrderItem, OrderPoint };
use App\Models\Discount\DiscountItem;
use App\Models\Promocode\Promocode;
use App\Helpers\CreatorRelationship;
use App\Helpers\HasTenant;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;

class Variation extends Model implements AuditableContract
{
    use SoftDeletes, HasTenant, Auditable, CreatorRelationship, SoftCascadeTrait;

    /****************************************
     **             Attributes
     ***************************************/
    
    /**
     * The relations that must have soft deleted with with model.
     *
     * @var array
     */
    protected $softCascade = [
        'order_item',
        'discount_items',
    ];

    /**
     * The attributes specifies that table has char type id
     *
     * @var boolean
     */
    public $incrementing = false;

    /**
     * The attributes defines use uuid when creating
     * or auto increment integer
     *
     * @var boolean
     */
    protected static $create_uuid = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'color_id', 
        'warranty_id',
        'size_id',
        'sales_price',
        'purchase_price',
        'inventory',
        'sending_time',
        'is_active'
    ];

    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'color_id', 
        'warranty_id',
        'size_id',
        'sales_price',
        'purchase_price',
        'unit',
        'sending_time',
        'is_active'
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'old_purchase_prices'   => 'array',
        'old_sales_prices'      => 'array',
        'is_active'             => 'boolean'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at',
        'offer_deadline'
    ];


    /****************************************
     **             Relations
     ***************************************/

    /**
     * Relation to Product model
     *
     * @return Product Model
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get all the promocodes that owned the variations & adverb
     */
    public function promocodes()
    {
        return $this->belongsToMany(Promocode::class);
    }

    /**
     * Relation to Color model
     *
     * @return Color Model
     */
    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    /**
     * Relation to size model
     *
     * @return Warranty Model
     */
    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    /**
     * Relation to Warranty model
     *
     * @return Warranty Model
     */
    public function warranty()
    {
        return $this->belongsTo(Warranty::class);
    }

    /**
     * Relation to orderItem model
     * 
     * @return OrderItem Model
     */
    
    public function order_item()
    {
        return $this->hasMany(OrderItem::class, 'variation_id');
    }

    /**
     * Relation to discount item model
     * 
     * @return discountItem Model
     */
    public function discount_items()
    {
        return $this->hasMany(DiscountItem::class);
    }

    /**
     * Get all the price changes of this variation
     */
    public function old_sale_prices()
    {
        return $this->hasMany(PriceChange::class, 'variation_id')->where('type', false);
    }

    /**
     * Relation to discount item model
     * 
     * @return discountItem Model
     */
    public function discount_item()
    {
        return $this->hasOne(DiscountItem::class);
    }

    /**
     * Relation to orderpoint model with orderable
     * 
     * @return OrderPoint Model
     */
    
    public function order_points()
    {
        return $this->morphMany(OrderPoint::class, 'orderable');
    }


    /****************************************
     **         Scopes & Mutators
     ***************************************/
    
    /**
     * Get the product price or it's offer
     * if it has an offer
     *
     * @return integer
     */
    public function getFinalPriceAttribute()
    {
        if ( $offer = $this->getOffer() )
            return $offer;
        
        else
            return $this->sales_price;
    }

    /****************************************
     **               Methods
     ***************************************/

    public function getOffer()
    {
        if ( !($discount = $this->discount_item->discount ?? false) )
            return null;

        if ( $discount->start_at->lt(now()) && $discount->expired_at->gt(now()) )
            return $this->discount_item->offer;
        else
            return null;
    }

    /**
     * return tops product varations
     *
     * @return Collection
     */
    public static function getTops ($limit = 5, $more = false)
    {
        $relations = [ 'product:id,name', 'color:id,name,value' ];
        $feilds = ['id', 'product_id', 'color_id'];
        
        if ($more) {
            $relations[0] = 'product:id,name,photo,category_id,label';
            $relations[] = 'product.category:id,title';
            array_push($feilds, 'price', 'offer', 'offer_deadline', 'stock_inventory');
        }

        $result = static::select($feilds)
            ->with($relations)
            ->withCount('order_item')
            ->orderBy('order_item_count', 'desc')
            ->limit($limit)->get();

        return $result;
    }

    public static function productOffers ($order)
    {
        $result = static::where('offer_deadline', '>', now())->with([
            'product:id,name,photo,category_id,label',
            'product.category:id,title'
        ]);
        switch ( $order )
        {
            case 'the_most':        $result->orderBy(DB::raw('price - offer'), 'desc'); break;
            case 'mostـurgent':     $result->orderBy('offer_deadline', 'asc'); break;
        }
        return $result->take(6)->get();
    }

    public function getDeadlineAttribute( $data )
    {
        return Carbon::parse( $this->offer_deadline );
    }
}
