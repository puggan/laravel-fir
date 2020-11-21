<?php

namespace App\Models;

use App\Exceptions\Api\InvalidGame;
use App\Exceptions\Api\InvalidMove;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection as C;

/**
 * Class Game
 * @package App\Models
 * @property int Game_ID
 * @property int Player1_ID
 * @property int Player2_ID
 * @property string Status
 * @property Carbon Start_Time
 * @property Carbon Changed_Time
 * @property C|Pawn[] pawns
 * @property Player player1
 * @property Player player2
 *
 * @method static Game find(int $id)
 */
class Game extends Model
{
    public const INVALID = '';
    public const WAITING_FOR_PLAYER1 = 'waiting for player 1';
    public const WAITING_FOR_PLAYER2 = 'waiting for player 2';
    public const WON_BY_PLAYER1 = 'won by player 1';
    public const WON_BY_PLAYER2 = 'won by player 2';
    public const TIE = 'tie';

    /**
     * Game constructor.
     *
     * @param array $attributes
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function __construct(array $attributes = [])
    {
        $this->table = 'Game';
        $this->primaryKey = 'Game_ID';
        $this->timestamps = false;
        $this->fillable = [
            'Player1_ID',
            'Player2_ID',
            'Status',
        ];

        parent::__construct($attributes);
    }

    /**
     * @return HasMany
     */
    public function pawns(): HasMany
    {
        return $this->hasMany(Pawn::class, 'Game_ID');
    }

    /**
     * @return BelongsTo
     */
    public function player1(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'Player1_ID');
    }

    /**
     * @return BelongsTo
     */
    public function player2(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'Player2_ID');
    }

    /**
     * @param int|mixed $default
     *
     * @return int[][]|mixed[][]
     */
    public static function empty_grid($default = 0): array
    {
        $grid = [];

        foreach (range(0, 5) as $y) {
            foreach (range(0, 6) as $x) {
                $grid[$y][$x] = $default;
            }
        }

        return $grid;
    }

    /**
     * @return int[][]
     */
    public function get_grid(): array
    {
        $grid = self::empty_grid();

        foreach ($this->pawns as $pawn) {
            if ($pawn->Color === Pawn::COLOR1) {
                $grid[$pawn->Y][$pawn->X] = 1;
                continue;
            }
            $grid[$pawn->Y][$pawn->X] = 2;
        }

        return $grid;
    }

    /**
     * @return Pawn[][]
     */
    public function get_pawn_grid(): array
    {
        /** @var Pawn[][] $grid */
        $grid = self::empty_grid(null);

        foreach ($this->pawns as $pawn) {
            $grid[$pawn->Y][$pawn->X] = $pawn;
        }

        return $grid;
    }

    /**
     * @param int $x
     * @param int $y
     *
     * @throws InvalidMove
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     * @throws \InvalidArgumentException
     */
    public function play($x, $y): void
    {
        if ($x < 0 || $x > 6 || $y < 0 || $y > 5) {
            throw new InvalidMove('coordinate outside range');
        }

        $grid = $this->get_grid();
        if ($grid[$y][$x]) {
            throw new InvalidMove('coordinate taken');
        }

        if ($y && !$grid[$y - 1][$x]) {
            throw new InvalidMove('coordinate bellow not taken');
        }

        $pawn = new Pawn(
            [
                'Game_ID' => $this->Game_ID,
                'X' => $x,
                'Y' => $y,
                'Nr' => 1 + $this->pawns()->count(),
            ]
        );

        switch ($this->Status) {
            case self::WAITING_FOR_PLAYER1:
                $pawn->Color = Pawn::COLOR1;
                $pawn->save();
                $this->update_status(true);
                return;

            case self::WAITING_FOR_PLAYER2:
                $pawn->Color = Pawn::COLOR2;
                $pawn->save();
                $this->update_status(true);
                return;
        }

        throw new InvalidMove('Non-playable Game');
    }

    /**
     * @throws InvalidGame
     * @throws \InvalidArgumentException
     */
    public function validate_pawns(): void
    {
        $next_free = [0, 0, 0, 0, 0, 0, 0];
        $query = $this->pawns()->getQuery();
        $query->orderBy('NR');
        foreach ($query->get() as $pawn) {
            /** @var Pawn $pawn */
            $expected_color = ($pawn->NR & 1) ? Pawn::COLOR1 : Pawn::COLOR2;
            if ($pawn->Color !== $expected_color) {
                $this->Status = '';
                throw new InvalidGame('Pawns played in wrong order.');
            }
            if ($next_free[$pawn->X] !== $pawn->Y) {
                $this->Status = '';
                throw new InvalidGame('Pawns played in wring hight.');
            }
            $next_free[$pawn->X] = $pawn->Y + 1;
        }
        $this->update_status(false);
    }

    /**
     * @param bool $update_time set Changed_Time?
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    public function update_status($update_time = false): void
    {
        $winner = null;
        $this->load('pawns');
        $pawn_grid = $this->get_pawn_grid();
        foreach ($this->pawns as $pawn) {
            $pawn->game = $this;
            $winner = $pawn->winner($pawn_grid);
            if (!$winner) {
                continue;
            }
            if ($pawn->Color === Pawn::COLOR1) {
                $this->Status = self::WON_BY_PLAYER1;
                $this->save();
                return;
            }
            if ($pawn->Color === Pawn::COLOR2) {
                $this->Status = self::WON_BY_PLAYER2;
                $this->save();
                return;
            }
        }
        $count = \count($this->pawns);
        if ($count === 42) {
            $this->Status = self::TIE;
            $this->save();
            return;
        }
        if ($count & 1) {
            $this->Status = self::WAITING_FOR_PLAYER2;
            $this->save();
            return;
        }
        $this->Status = self::WAITING_FOR_PLAYER1;
        if ($update_time) {
            $this->Changed_Time = new Carbon();
        }
        $this->save();
    }

    /**
     * @param C|Game[] $games
     *
     * @return mixed[][]
     * @throws InvalidGame
     * @throws \InvalidArgumentException
     */
    public static function add_player_names($games): array
    {
        static $players = [];

        $missing_players = [];
        $game_list = [];
        foreach ($games as $game) {
            // Validate
            $game->validate_pawns();

            if (empty($players[$game->Player1_ID])) {
                $missing_players[$game->Player1_ID] = $game->Player1_ID;
            }
            if (empty($players[$game->Player2_ID])) {
                $missing_players[$game->Player2_ID] = $game->Player2_ID;
            }
            $game_list[] = $game->attributesToArray();
        }

        if ($missing_players) {
            foreach (Player::findMany($missing_players) as $player) {
                $players[$player->Player_ID] = $player->User_Name;
            }
        }

        foreach ($game_list as &$game) {
            $game['Player1'] = $players[$game['Player1_ID']];
            $game['Player2'] = $players[$game['Player2_ID']];
        }

        return $game_list;
    }
}
