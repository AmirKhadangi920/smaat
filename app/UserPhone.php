<?php

namespace App;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\HasTenant;

class UserPhone extends Model implements AuditableContract
{
    use Filterable, SoftDeletes, Auditable, HasTenant;

    /****************************************
     **             Attributes
     ***************************************/

    protected static $jalali_time = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'phone_number'
    ];
    
    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'type',
        'phone_number'
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
     * Get the user that owned this phone number
     */
    public function user()
    {
        return $this->belongsTo(App\User::class);
    }
}