<?php
    /**
     * Created by PhpStorm.
     * User: puggan
     * Date: 2018-10-05
     * Time: 18:03
     */

    namespace App\Models;

    use App\Relations\HasManyUnion;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Support\Collection as C;

    /**
     * Class Person
     * @package App\Models
     * @property int Player_ID
     * @property string User_Name
     * @property C|Game[] games
     * @property C|Game[] p1_games
     * @property C|Game[] p2_games
     *
     * @method static Player find(int $id)
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
        public function games() : HasMany
        {
            $instance = $this->newRelatedInstance(Game::class);

            $foreignKey = [
                $instance->getTable().'.'. 'Player1_ID',
                $instance->getTable().'.'. 'Player2_ID',
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
        public function p1_games() : HasMany
        {
            return $this->hasMany(Game::class, 'Player1_ID');
        }

        /**
         * @return HasMany
         */
        public function p2_games() : HasMany
        {
            return $this->hasMany(Game::class, 'Player2_ID');
        }
    }
