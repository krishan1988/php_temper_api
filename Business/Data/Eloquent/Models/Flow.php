<?php

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
