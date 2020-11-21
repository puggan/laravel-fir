<?php

namespace App\Models;

use App\Relations\HasManyUnion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection as C;

/**
 * Class Person
 * @package App\Models
 * @property int Player_ID
 * @property string User_Name
 * @property C|Game[] games
 * @property C|Game[] p1_games
 * @property C|Game[] p2_games
 * @property Queue in_queue
 *
 * @method static Player find(int $id)
 * @method static Player[] findMany(int[] $ids)
 */
class Player extends Model
{
    /**
     * Player constructor.
     *
     * @param array $attributes
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function __construct(array $attributes = [])
    {
        $this->table = 'Persons';
        $this->primaryKey = 'Player_ID';
        $this->timestamps = false;
        $this->fillable = [
            'User_Name',
        ];

        parent::__construct($attributes);
    }

    /**
     * @return HasManyUnion
     */
    public function games(): HasMany
    {
        $instance = $this->newRelatedInstance(Game::class);

        $foreignKey = [
            $instance->getTable() . '.' . 'Player1_ID',
            $instance->getTable() . '.' . 'Player2_ID',
        ];

        return new HasManyUnion(
            $instance->newQuery(),
            $this,
            $foreignKey,
            $this->getKeyName()
        );
    }

    /**
     * @return HasMany
     */
    public function p1_games(): HasMany
    {
        return $this->hasMany(Game::class, 'Player1_ID');
    }

    /**
     * @return HasMany
     */
    public function p2_games(): HasMany
    {
        return $this->hasMany(Game::class, 'Player2_ID');
    }

    /**
     * @return HasOne
     */
    public function in_queue(): HasOne
    {
        return $this->hasOne(Queue::class, 'Player_ID');
    }

    public function randomMatch(): int
    {
        /** @var Builder $query */
        /** @noinspection PhpUnhandledExceptionInspection */
        $query = Queue::query()->getQuery();
        /** @noinspection PhpUnhandledExceptionInspection */
        $query->leftJoin(
            'Game',
            function ($join) {
                /** @var $join JoinClause */
                $join->on('Game.Player2_ID', '=', 'Queue.Player_ID');
                $join->where('Game.Player1_ID', '=', $this->Player_ID);
                $join->where('Game.Status', 'LIKE', 'Wait%');
            }
        );
        /** @noinspection PhpUnhandledExceptionInspection */
        $query->where('Queue.Player_ID', '<>', $this->Player_ID);
        $query->whereNull('Game.Game_ID');
        $query->orderBy('Queue.Start_Time', 'desc');
        $query->limit(10);
        $ids = $query->pluck('Queue.Player_ID')->toArray();

        if (!$ids) {
            return 0;
        }

        return array_rand(array_combine($ids, $ids));
    }
}
