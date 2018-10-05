<?php
    /**
     * Created by PhpStorm.
     * User: puggan
     * Date: 2018-10-05
     * Time: 18:03
     */

    namespace App\Models;

    use Carbon\Carbon;
    use Illuminate\Database\Eloquent\Model;

    /**
     * Class Game
     * @package App\Models
     * @property int Game_ID
     * @property int Player1_ID
     * @property int Player2_ID
     * @property string Status
     * @property Carbon Start_Time
     * @property Pawn[] pawns
     * @property Player player1
     * @property Player player2
     *
     * @method static self find(int $id)
     */
    class Game extends Model
    {
        public function __construct(array $attributes = [])
        {
            $this->table = 'Game';
            $this->primaryKey = 'Game_ID';

            parent::__construct($attributes);
        }

        function pawns()
        {
            return $this->hasMany(Pawn::class, 'Game_ID');
        }

        function player1()
        {
            return $this->belongsTo(Player::class, 'Player1_ID');
        }

        function player2()
        {
            return $this->belongsTo(Player::class, 'Player2_ID');
        }
    }
