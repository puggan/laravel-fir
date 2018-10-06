<?php

    namespace App\Http\Controllers;

    use App\Exceptions\Api\InvalidGame;
    use App\Exceptions\Api\InvalidPlayer;
    use App\Models\Game;
    use App\Models\Player;
    use App\Models\Token;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Http\Request;
    use Illuminate\Routing\Router;

    /**
     * Class ApiController
     * @package App\Http\Controllers
     * @property Request request
     * @property Player player
     */
    class ApiController extends Controller
    {
        /** @var Request $request */
        protected $request;

        /** @var Player $player */
        protected $player;

        public function __construct(Request $request)
        {
            $this->request = $request;

            $token = NULL;
            $token_string = $this->request->post('token');
            if($token_string)
            {
                /** @var Token $token */
                $token = Token::find($token_string);
            }
            if($token)
            {
                $this->player = $token->player;
            }
        }

        /**
         * @return void
         * @throws InvalidPlayer
         */
        public function require_player() : void
        {
            if(!$this->player)
            {
                throw new InvalidPlayer('Invalid player/api-token');
            }
        }

        /**
         * @param Router $router
         *
         * @return void
         */
        public static function routes($router) : void
        {
            $router->post('/whoami/game', 'ApiController@game_whoami');
            $router->post('/whoami', 'ApiController@whoami');
            $router->post('/player/add', 'ApiController@add_player');
            $router->post('/player/auth', 'ApiController@auth_player');
            $router->post('/game/add', 'ApiController@add_game');
            $router->get('/player/{player_id}', 'ApiController@get_player');
            $router->get('/game/{game_id}/grid', 'ApiController@get_grid');
            $router->get('/game/{game_id}/pawns', 'ApiController@get_pawns');
            $router->get('/game/{game_id}', 'ApiController@get_game');
            $router->get('/games/{player_id}', 'ApiController@get_games');
            $router->post('/play/{game_id}/{x}/{y}', 'ApiController@play');
            $router->get('/', 'ApiController@docs');
        }

        public function docs()
        {
            return view('apidocs');
        }

        /**
         * @return Game|null
         * @throws InvalidPlayer
         * @throws \Illuminate\Database\Eloquent\MassAssignmentException
         * @throws \InvalidArgumentException
         */
        public function game_whoami() : Game
        {
            $this->require_player();
            $player_id = $this->player->Player_ID;

            $query = Game::query();
            $query->where(
                function ($query) use ($player_id) {
                    /** @var Builder $query */
                    $query->where('Player1_ID', '=', $player_id);
                    $query->where('status', '=', Game::WAITING_FOR_PLAYER1);
                    $query->orWhere('Player2_ID', '=', $player_id);
                    $query->where('status', '=', Game::WAITING_FOR_PLAYER2);
                }
            );

            /** @var Game $game */
            /** @noinspection OneTimeUseVariablesInspection */
            $game = $query->first();

            return $game;
        }

        /**
         * @return Player
         * @throws InvalidPlayer
         */
        public function whoami() : Player
        {
            $this->require_player();

            return $this->player;
        }

        /**
         * @return array
         * @throws InvalidPlayer
         * @throws \Illuminate\Database\Eloquent\MassAssignmentException
         * @throws \InvalidArgumentException
         */
        public function add_player() : array
        {
            $username = $this->request->post('username');
            if(!$username)
            {
                throw new InvalidPlayer('Invalid username');
            }

            $query = Player::query();
            $query->where('User_Name', '=', $username);
            /** @var Player $player */
            $player = $query->first();

            if(!$player) {
                $player = new Player([
                    'User_Name' => $username,
                ]);
                $player->save();
            }

            $token = new Token(['Player_ID' => $player->Player_ID]);
            $token->save();
            $result = $token->toArray();
            $result['Player'] = $player;
            return $result;
        }

        /**
         * @return array
         * @throws InvalidPlayer
         * @throws \Illuminate\Database\Eloquent\MassAssignmentException
         * @throws \InvalidArgumentException
         */
        public function auth_player() : array
        {
            $username = $this->request->post('username');
            if(!$username)
            {
                throw new InvalidPlayer('Invalid username');
            }

            $query = Player::query();
            $query->where('User_Name', '=', $username);
            /** @var Player $player */
            $player = $query->first();

            if(!$player)
            {
                throw new InvalidPlayer('Non-existing username');
            }

            $token = new Token(['Player_ID' => $player->Player_ID]);
            $token->save();
            $result = $token->toArray();
            $result['Player'] = $player;
            return $result;
        }

        /**
         * @param int $player_id
         *
         * @return Player
         * @throws InvalidPlayer
         */
        public function get_player($player_id) : Player
        {
            $player = Player::find($player_id);
            if(!$player) {
                throw new InvalidPlayer('player not found');
            }

            return $player;
        }

        /**
         * @return Game
         * @throws InvalidPlayer
         * @throws \Illuminate\Database\Eloquent\MassAssignmentException
         * @throws \InvalidArgumentException
         */
        public function add_game() : Game
        {
            $this->require_player();

            $other_username = $this->request->post('opponent');
            $query = Player::query();
            $query->where('User_Name', '=', $other_username);
            /** @var Player $other_player */
            $other_player = $query->first();

            if(!$other_player) {
                throw new InvalidPlayer('Opponent not found');
            }

            $query = Game::query();
            $query->whereIn(
                'Status',
                [Game::WAITING_FOR_PLAYER1, Game::WAITING_FOR_PLAYER2]
            );

            $player_ids = [
                $this->player->Player_ID,
                $other_player->Player_ID,
            ];

            $query->whereIn(
                'Player1_ID',
                $player_ids
            );

            $query->whereIn(
                'Player2_ID',
                $player_ids
            );

            $query->whereColumn('Player1_ID', '<>', 'Player2_ID');

            /** @var Game $game */
            $game = $query->first();

            if($game)
            {
                return $game;
            }

            $game = new Game(
                [
                    'Player1_ID' => $this->player->Player_ID,
                    'Player2_ID' => $other_player->Player_ID,
                    'Status' => Game::WAITING_FOR_PLAYER1,
                ]
            );
            $game->save();
            return $game;
        }

        /**
         * @param $game_id
         *
         * @return \int[][]
         * @throws InvalidGame
         */
        public function get_grid($game_id) : array
        {
            $game = Game::find($game_id);

            if(!$game) {
                throw new InvalidGame('Invalid Game_ID');
            }

            return $game->get_grid();
        }

        /**
         * @param $game_id
         *
         * @return \App\Models\Pawn[]
         * @throws InvalidGame
         */
        public function get_pawns($game_id) : array
        {
            $game = Game::find($game_id);

            if(!$game) {
                throw new InvalidGame('Invalid Game_ID');
            }

            return $game->pawns->toArray();
        }

        /**
         * @param $game_id
         *
         * @return Game
         * @throws InvalidGame
         */
        public function get_game($game_id) : Game
        {
            $game = Game::find($game_id);

            if(!$game) {
                throw new InvalidGame('Invalid Game_ID');
            }

            return $game;
        }

        /**
         * @param $player_id
         *
         * @return Game[]
         * @throws InvalidPlayer
         */
        public function get_games($player_id) : array
        {
            $player = Player::find($player_id);
            if(!$player) {
                throw new InvalidPlayer('player not found');
            }

            return $player->games;
        }

        /**
         * @param $game_id
         * @param $x
         * @param $y
         *
         * @return string
         * @throws InvalidGame
         * @throws InvalidPlayer
         * @throws \App\Exceptions\Api\InvalidMove
         * @throws \Illuminate\Database\Eloquent\MassAssignmentException
         */
        public function play($game_id, $x, $y) : string
        {
            $this->require_player();

            $game = Game::find($game_id);

            if(!$game) {
                throw new InvalidGame('Invalid Game_ID');
            }

            switch($game->Status)
            {
                case Game::WAITING_FOR_PLAYER1:
                    if($game->Player1_ID !== $this->player->Player_ID) {
                        throw new InvalidPlayer('Not your turn');
                    }
                    $game->play($x, $y);
                    break;

                case Game::WAITING_FOR_PLAYER2:
                    if($game->Player2_ID !== $this->player->Player_ID) {
                        throw new InvalidPlayer('Not your turn');
                    }
                    $game->play($x, $y);
                    break;

                default:
                    throw new InvalidGame('Non-playable Game');
            }

            return 'OK';
        }
    }
