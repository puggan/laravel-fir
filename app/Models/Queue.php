<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Queue
 * @package App\Models
 * @property int Player_id
 * @property Carbon Start_Time
 * @method static Queue find(int $id)
 */
class Queue extends Model
{

    /**
     * Pawn constructor.
     *
     * @param array $attributes
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function __construct(array $attributes = [])
    {
        $this->table = 'Queue';
        $this->primaryKey = 'Player_id';
        $this->timestamps = false;
        $this->fillable = [
            'Player_id',
        ];

        parent::__construct($attributes);
    }
}
