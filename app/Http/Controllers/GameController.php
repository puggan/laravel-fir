<?php
    /**
     * Created by PhpStorm.
     * User: puggan
     * Date: 2018-10-05
     * Time: 18:54
     */

    namespace App\Http\Controllers;

    use App\Models\Game;

    class GameController extends Controller
    {
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
