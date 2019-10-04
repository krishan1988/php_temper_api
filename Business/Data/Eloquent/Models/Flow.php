<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 8/7/2018
 * Time: 9:22 PM
 */

namespace Business\Data\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class Flow extends Model
{
    const TABLE = 'member_onboarding';

    protected $table = self::TABLE;
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'user_id',
    ];
}
