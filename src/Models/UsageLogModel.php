<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsageLogModel extends Model
{
    /**
     * @var string
     */
    protected $table = 'usage_log';

    /**
     * no created_at or updated_at
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'userId',
        'sessionId',
        'login',
        'last_movement'
    ];

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return (new self())->getTable();
    }

}