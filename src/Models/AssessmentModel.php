<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentModel extends Model
{
    /**
     * @var string
     */
    protected $table = 'assessments';

    /**
     * no created_at or updated_at
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'areaId'
    ];

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return (new self())->getTable();
    }

    /**
     * Many to One relationship with Area
     * @return object
     */
    public function area()
    {
        return $this->belongsTo(AreaModel::class, 'areaId');
    }
}