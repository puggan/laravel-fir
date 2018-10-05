<?php
    /**
     * Created by PhpStorm.
     * User: puggan
     * Date: 2018-10-05
     * Time: 18:03
     */

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    /**
     * Class Pawns
     * @package App\Models
     * @property int Pawn_ID
     * @property int Game_ID
     * @property int X
     * @property int Y
     * @property string Color
     * @property int NR
     * @property Game game
     *
     * @method static self find(int $id)
     */
    class Pawn extends Model
    {
        public function __construct(array $attributes = [])
        {
            $this->table = 'Pawns';
            $this->primaryKey = 'Pawn_ID';

            parent::__construct($attributes);
        }

        public function game()
        {
            return $this->belongsTo(Game::class, 'Game_ID');
        }
    }
