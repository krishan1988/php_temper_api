<?php declare(strict_types = 1);

namespace Business\Data\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{

    public $timestamps = false;

    const TABLE = 'users';

    protected $table = self::TABLE;
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
    ];

}