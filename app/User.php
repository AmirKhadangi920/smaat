<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\GenerateRandomID;
use Spatie\Permission\Traits\HasRoles;
use Laratrust\Traits\LaratrustUserTrait;
use App\Models\{
    Article,
    Product\Product,
    Opinion\Comment,
    Places\City,
    Financial\Factor,
    Opinion\QuestionAndAnswer,
    Opinion\Review,
 Promocode\Promocode,
 Financial\FactorItem,
 Discount\Discount



};



class User extends Authenticatable implements AuditableContract
{
    use LaratrustUserTrait;
    // use Notifiable, SoftDeletes, GenerateRandomID, Auditable, HasRoles;
    use Notifiable, SoftDeletes, GenerateRandomID, Auditable;

    /****************************************
     **             Attributes
     ***************************************/

    /**
     * The attributes specifies that table has char type id
     *
     * @var boolean
     */
    public $incrementing = false;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phones',
        'email',
        'password',
        'avatar',
        'address',
        'postal_code',
        'national_code',
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'phones'        => 'array',
        'social_links'  => 'array',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    
    /****************************************
     **         Scopes & Mutators
     ***************************************/
    
    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }


    /****************************************
     **             Relations
     ***************************************/
    
    /**
     * Get all the articles that the user wrote them
     */
    public function articles ()
    {
        return $this->hasMany(Article::class);
    }

    /**
     * Get all the discounts that the user
     */
    public function discounts ()
    {
        return $this->hasMany(Discount::class);
    }

    /**
     * Get all the promocode that the user 
     */
    public function promocodes ()
    {
        return $this->hasMany(Promocode::class);
    }


    /**
     * Get all of the favorites products
     */
    public function favorites ()
    {
        return $this->belongsToMany(Product::class, 'favorites');
    }

    /**
     * Get all the audits that the user wrote them
     */
    public function comments ()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * each user live in one city
     */
    public function city ()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get all the questionAndAnsers of the users.
     */
    public function users ()
    {
        return $this->hasMany(QuestionAndAnswer::class);
    }

    /**
     * Get all the orders of the users.
     */
    public function orders ()
    {
        return $this->hasMany(Factor::class);
    }

    /**
     * Get all the orders items of the users.
     */
    public function orderItems ()
    {
        return $this->hasMany(FactorItem::class);
    }

    /**
     * Get all the orders of the users.
     */
    public function reviews ()
    {
        return $this->hasMany(Review::class);
    }
}