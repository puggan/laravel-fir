@php
    /** @var \App\Models\Game $game */
    /** @var string[][] $pawns */
@endphp
        <!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Game {{ $game->Game_ID }}: {{ $game->player1->User_Name  }} vs {{ $game->player2->User_Name }}</title>
        <style>
            .gameplan {
                display: inline-block;
                border: solid gray 5px;
                background: gray;
            }

            .pawn {
                display: inline-block;
                margin: 5px;
                height: 20px;
                width: 20px;
                border-radius: 10px;
                background: white;
            }

            .pawn.pawn-red {
                background: red;
            }

            .pawn.pawn-blue {
                background: blue;
            }
        </style>
    </head>
    <body>
        <h1>Game {{ $game->Game_ID }}: {{ $game->player1->User_Name  }} vs {{ $game->player2->User_Name }}</h1>
        <div class='gameplan'>
            @foreach($pawns as $row)
                <div class='row'>
                    @foreach($row as $pawn_color)
                        <span class="pawn pawn-{{ $pawn_color }}"></span>
                    @endforeach
                </div>
            @endforeach
        </div>
    </body>
</html>
