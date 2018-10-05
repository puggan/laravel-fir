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

    /**
     * Class Person
     * @package App\Models
     * @property int Player_ID
     * @property string User_Name
     * @property Game[] games
     * @property Game[] p1_games
     * @property Game[] p2_games
     *
     * @method static self find(int $id)
     */
    class Player extends Model
    {
        public function __construct(array $attributes = [])
        {
            $this->table = 'Persons';
            $this->primaryKey = 'Player_ID';

            parent::__construct($attributes);
        }

        function games()
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

        function p1_games()
        {
            return $this->hasMany(Game::class, 'Player1_ID');
        }

        function p2_games()
        {
            return $this->hasMany(Game::class, 'Player2_ID');
        }
    }
