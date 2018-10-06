<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;

    /**
     * Class Token
     * @package App\Models
     *
     * @property string Token char(32)
     * @property int Player_ID
     * @property Player player
     *
     * @method static Token find(int $id)
     */
    class Token extends Model
    {
        /**
         * Token constructor.
         *
         * @param array $attributes
         *
         * @throws \Illuminate\Database\Eloquent\MassAssignmentException
         */
        public function __construct(array $attributes = [])
        {
            $this->table = 'Api_Token';
            $this->primaryKey = 'Token';
            $this->timestamps = FALSE;
            $this->incrementing = FALSE;
            $this->fillable = [
                'Token',
                'Player_ID',
            ];

            parent::__construct($attributes);

            if(!$this->Token)
            {
                try
                {
                    $this->Token = base64_encode(random_bytes(24));
                }
                catch(\Exception $e)
                {
                    $this->Token = base64_encode(
                        $d = (new \DateTime())->format('y-m-d H:i:s u')
                    );
                }
            }
        }

        /**
         * @return BelongsTo
         */
        public function player() : BelongsTo
        {
            return $this->belongsTo(Player::class, 'Player_ID');
        }

        /**
         * @param $player_id
         *
         * @throws \Illuminate\Database\Eloquent\MassAssignmentException
         * @throws \InvalidArgumentException
         */
        public static function clean($player_id)
        {
            $query = Token::query();
            $query->where('Player_ID', '=', $player_id);
            $query->delete();
        }
    }
