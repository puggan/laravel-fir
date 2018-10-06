<?php

    namespace App\Http\Controllers;

    use App\Models\Game;
    use Illuminate\Routing\Router;

    /**
     * Class GameController
     * @package App\Http\Controllers
     */
    class GameController extends Controller
    {
        /**
         * @param Router $router
         */
        public static function routes($router) : void
        {
            $router->get('/game/{id}', 'GameController@view');
        }

        /**
         * @param $id
         *
         * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
         * @throws \LogicException
         */
        public function view($id)
        {
            $game = Game::find($id);
            if(!$game) {
                return response()->view('404', [], 404);
            }

            $pawns = [];
            foreach(range(5, 0, -1) as $y)
            {
                foreach(range(0, 6) as $x)
                {
                    $pawns[$y][$x] = 'none';
                }
            }

            foreach($game->pawns as $pawn)
            {
                $pawns[$pawn->Y][$pawn->X] = strtolower($pawn->Color);
            }

            return view('game', ['game' => $game, 'pawns' => $pawns]);
        }
    }
