<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AreaModel extends Model
{
    /**
     * @var string
     */
    protected $table = 'areas';

    /**
     * no created_at or updated_at
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return (new self())->getTable();
    }

    /**
     * Many to One relationship with Assessments
     * @return array
     */
    public function assessments(): array
    {
        return $this->hasMany(AssessmentModel::class);
    }
}