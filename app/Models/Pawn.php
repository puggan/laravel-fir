<?php
    /**
     * Created by PhpStorm.
     * User: puggan
     * Date: 2018-10-05
     * Time: 18:03
     */

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
     * @method static Pawn find(int $id)
     */
    class Pawn extends Model
    {
        public const COLOR1 = 'Red';
        public const COLOR2 = 'Blue';

        /**
         * Pawn constructor.
         *
         * @param array $attributes
         *
         * @throws \Illuminate\Database\Eloquent\MassAssignmentException
         */
        public function __construct(array $attributes = [])
        {
            $this->table = 'Pawns';
            $this->primaryKey = 'Pawn_ID';
            $this->timestamps = false;
            $this->fillable = [
                'Game_ID',
                'X',
                'Y',
                'Color',
                'Nr',
            ];
            $this->hidden = [
                'Pawn_ID',
            ];

            parent::__construct($attributes);
        }

        /**
         * @return BelongsTo
         */
        public function game() : BelongsTo
        {
            return $this->belongsTo(Game::class, 'Game_ID');
        }

        /**
         * @param Pawn[][] $pawn_grid
         *
         * @return bool
         */
        public function winner($pawn_grid) : bool
        {
            $result = $this->winnerSE($pawn_grid);
            $result |= $this->winnerSW($pawn_grid);
            $result |= $this->winnerE($pawn_grid);
            $result |= $this->winnerS($pawn_grid);
            return $result;
        }

        /**
         * @param Pawn[][] $pawn_grid
         *
         * @return bool
         */
        public function winnerE($pawn_grid) : bool
        {
            if($this->X > 3)
            {
                return FALSE;
            }
            foreach(range(1, 3) as $offset)
            {
                $other_pawn = $pawn_grid[$this->Y][$this->X + $offset];
                if(!$other_pawn)
                {
                    return FALSE;
                }
                if($other_pawn->Color !== $this->Color)
                {
                    return FALSE;
                }
            }
            return TRUE;
        }

        /**
         * @param Pawn[][] $pawn_grid
         *
         * @return bool
         */
        public function winnerSE($pawn_grid) : bool
        {
            if($this->Y < 3)
            {
                return FALSE;
            }
            if($this->X > 3)
            {
                return FALSE;
            }
            foreach(range(1, 3) as $offset)
            {
                $other_pawn = $pawn_grid[$this->Y - $offset][$this->X + $offset];
                if(!$other_pawn)
                {
                    return FALSE;
                }
                if($other_pawn->Color !== $this->Color)
                {
                    return FALSE;
                }
            }
            return TRUE;
        }

        /**
         * @param Pawn[][] $pawn_grid
         *
         * @return bool
         */
        public function winnerS($pawn_grid) : bool
        {
            if($this->Y < 3)
            {
                return FALSE;
            }
            foreach(range(1, 3) as $offset)
            {
                $other_pawn = $pawn_grid[$this->Y - $offset][$this->X];
                if(!$other_pawn)
                {
                    return FALSE;
                }
                if($other_pawn->Color !== $this->Color)
                {
                    return FALSE;
                }
            }
            return TRUE;
        }

        /**
         * @param Pawn[][] $pawn_grid
         *
         * @return bool
         */
        public function winnerSW($pawn_grid) : bool
        {
            if($this->Y < 3)
            {
                return FALSE;
            }
            if($this->X < 3)
            {
                return FALSE;
            }
            foreach(range(1, 3) as $offset)
            {
                $other_pawn = $pawn_grid[$this->Y - $offset][$this->X - $offset];
                if(!$other_pawn)
                {
                    return FALSE;
                }
                if($other_pawn->Color !== $this->Color)
                {
                    return FALSE;
                }
            }
            return TRUE;
        }
    }
