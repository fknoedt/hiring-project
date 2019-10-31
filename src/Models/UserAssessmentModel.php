<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAssessmentModel extends Model
{
    /**
     * @var string
     */
    protected $table = 'user_assessment';

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
        'assessmentId',
        'created_or_updated_at'
    ];

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return (new self())->getTable();
    }

    /**
     * One to One relationship with User
     * @return object
     */
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'userId');
    }

    /**
     * One to One relationship with Assessment
     * @return object
     */
    public function assessment()
    {
        return $this->belongsTo(AssessmentModel::class, 'assessmentId');
    }
}