<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
/**
 * users模型 
 */
class users extends Model
{
    protected $connection = 'sso';
    protected $primaryKey = 'ID';//因为我表里的主键是大写的，所以这里我用大写，否则使用关联模型会出问题

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    /*多对多建立关系*/
    public function roles()
    {
        return $this->belongsToMany(roles::class,'users_roles','userId','roleId');
    }
}
