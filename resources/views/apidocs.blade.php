<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>API documentation - Four in row</title>
        <style>
            li.closed > * {
                display: none;
            }
            li > h3, li.closed > h3 {
                display: block;
                cursor: pointer;
            }
            li > h3 > span {
                font-size: smaller;
                color: blue;
            }
            dl > dt {
                margin-top: 1em;
            }
        </style>
    </head>
    <body>
        <h1>API documentation - Four in row</h1>
        <p>An API to play Four in row whit others.</p>
        <h2>API Paths</h2>
        <ul>
            <li>
                <h3>/api</h3>
                <dl>
                    <dt>Request type</dt>
                    <dd>GET</dd>

                    <dt>Response Type</dt>
                    <dd>HTML</dd>
                </dl>
            </li>
            <li>
                <h3>/api/game/<span>[game_id]</span></h3>
                <dl>
                    <dt>Request type</dt>
                    <dd>GET</dd>

                    <dt>Response Type</dt>
                    <dd>JSON - Game</dd>

                    <dt>URL Parameter - game_id</dt>
                    <dd>Unsigned Int</dd>
                </dl>
                <p>Get information about a given game.</p>
            </li>
            <li>
                <h3>/api/game/<span>[game_id]</span>/grid</h3>
                <dl>
                    <dt>Request type</dt>
                    <dd>GET</dd>

                    <dt>Response Type</dt>
                    <dd>JSON - PawnState[][]</dd>

                    <dt>URL Parameter - game_id</dt>
                    <dd>Unsigned Int</dd>
                </dl>
                <p>Get the grid for a given game.</p>
                <p>See also /pawns.</p>
            </li>
            <li>
                <h3>/api/game/<span>[game_id]</span>/pawns</h3>
                <dl>
                    <dt>Request type</dt>
                    <dd>GET</dd>

                    <dt>Response Type</dt>
                    <dd>JSON - Pawn[]</dd>

                    <dt>URL Parameter - game_id</dt>
                    <dd>Unsigned Int</dd>
                </dl>
                <p>Get the pawns for a given game.</p>
                <p>See also /grid.</p>
            </li>
            <li>
                <h3>/api/player/<span>[player_id]</span></h3>
                <dl>
                    <dt>Request type</dt>
                    <dd>GET</dd>

                    <dt>Response Type</dt>
                    <dd>JSON - Player</dd>

                    <dt>URL Parameter - player_id</dt>
                    <dd>Unsigned Int</dd>
                </dl>
                <p>Get info about a player.</p>
            </li>
            <li>
                <h3>/api/games/<span>[player_id]</span></h3>
                <dl>
                    <dt>Request type</dt>
                    <dd>GET</dd>

                    <dt>Response Type</dt>
                    <dd>JSON - Game[]</dd>

                    <dt>URL Parameter - player_id</dt>
                    <dd>Unsigned Int</dd>
                </dl>
            </li>

            <li>
                <h3>/api/player/auth</h3>
                <dl>
                    <dt>Request type</dt>
                    <dd>POST</dd>

                    <dt>Response Type</dt>
                    <dd>JSON - Token</dd>

                    <dt>Post Parameter - username</dt>
                    <dd>String - Username</dd>
                </dl>
                <p>Get a api-token for a given username.</p>
                <p>No security, like passwords, implemented yet.</p>
            </li>
            <li>
                <h3>/api/player/add</h3>
                <dl>
                    <dt>Request type</dt>
                    <dd>POST</dd>

                    <dt>Response Type</dt>
                    <dd>JSON - Token</dd>

                    <dt>Post Parameter - username</dt>
                    <dd>String - Username</dd>
                </dl>
                <p>Add a User and get the get a api-token for that user.</p>
                <p>No security, like passwords, implemented yet.</p>
            </li>

            <li>
                <h3>/api/whoami</h3>
                <dl>
                    <dt>Request type</dt>
                    <dd>POST</dd>

                    <dt>Response Type</dt>
                    <dd>JSON - Player</dd>

                    <dt>Post Parameter - token</dt>
                    <dd>String - Token</dd>
                </dl>
                <p>Check what user is connected to the given token.</p>
            </li>
            <li>
                <h3>/api/whoami/game</h3>
                <dl>
                    <dt>Request type</dt>
                    <dd>POST</dd>

                    <dt>Response Type</dt>
                    <dd>JSON - Game</dd>

                    <dt>Post Parameter - token</dt>
                    <dd>String - Token</dd>
                </dl>
                <p>Fetch a playable game.</p>
                <p>Good in a fethc loop, play game, fetch next game.</p>
            </li>
            <li>
                <h3>/api/game/add</h3>
                <dl>
                    <dt>Request type</dt>
                    <dd>POST</dd>

                    <dt>Response Type</dt>
                    <dd>JSON - Game</dd>

                    <dt>Post Parameter - token</dt>
                    <dd>String - Token</dd>

                    <dt>Post Parameter - opponent</dt>
                    <dd>string</dd>
                </dl>
                <p>Start a new game agains an opponenet.</p>
            </li>
            <li>
                <h3>/api/play/<span>[game_id]</span>/<span>[x]</span>/<span>[y]</span></h3>
                <dl>
                    <dt>Request type</dt>
                    <dd>POST</dd>

                    <dt>Response Type</dt>
                    <dd>JSON - Game</dd>

                    <dt>URL Parameter - game_id</dt>
                    <dd>Unsigned Int</dd>

                    <dt>URL Parameter - x</dt>
                    <dd>Unsigned Int</dd>

                    <dt>URL Parameter - y</dt>
                    <dd>Unsigned Int</dd>

                    <dt>Post Parameter - token</dt>
                    <dd>String - Token</dd>
                </dl>
            </li>
        </ul>
        <h2>Data Types</h2>
        <ul>
            <li>
                <h3>Unsigned Int - X</h3>
                <p>Range: 0 to 6.</p>
                <p>The left most column is 0.</p>
                <p>The right most column is 6.</p>
            </li>
            <li>
                <h3>Unsigned Int - Y</h3>
                <p>Range: 0 to 5.</p>
                <p>The bottom column i 0.</p>
                <p>The top most column i 5.</p>
            </li>
            <li>
                <h3>Unsigned Int - Pawn Number</h3>
                <p>Range: 1 - 42.</p>
                <p>First pawn placed in a game, is Nr 1.</p>
            </li>
            <li>
                <h3>JSON - Game</h3>
                <p>A game between 2 players.</p>
                <dl>
                    <dt>Game_ID</dt>
                    <dd>Unsigned Int</dd>

                    <dt>Player1_ID</dt>
                    <dd>Unsigned Int</dd>

                    <dt>Player2_ID</dt>
                    <dd>Unsigned Int</dd>

                    <dt>Status</dt>
                    <dd>String - Status</dd>

                    <dt>Start_Time</dt>
                    <dd>String - Datetime</dd>

                    <dt>Player1</dt>
                    <dd>String - Username</dd>

                    <dt>Player2</dt>
                    <dd>String - Username</dd>
                </dl>
            </li>
            <li>
                <h3>JSON - Game[]</h3>
                <p>A list of JSON - Game.</p>
            </li>
            <li>
                <h3>JSON - PawnState</h3>
                <p>Unsigned Int.</p>
                <p>0: No pawn in this slot.</p>
                <p>1: Player 1 has an pawn in this slot.</p>
                <p>2: Player 2 has an pawn in this slot.</p>
            </li>
            <li>
                <h3>JSON - PawnState[]</h3>
                <p>An row of JSON - PawnState.</p>
                <p>Indexed for column 0 to 6.</p>
            </li>
            <li>
                <h3>JSON - PawnState[][]</h3>
                <p>All row of JSON - PawnState[].</p>
                <p>Indexed for row 0 to 5.</p>
                <p>use as: pawnstates[y][x].</p>
            </li>
            <li>
                <h3>JSON - Pawn</h3>
                <p>A pawn in the game.</p>
                <dl>
                    <dt>Game_ID</dt>
                    <dd>Unsigned Int</dd>

                    <dt>X</dt>
                    <dd>Unsigned Int - X</dd>

                    <dt>Y</dt>
                    <dd>Unsigned Int - Y</dd>

                    <dt>Color</dt>
                    <dd>String - Color</dd>

                    <dt>NR</dt>
                    <dd>Unsigned Int - Pawn Number</dd>
                </dl>
            </li>
            <li>
                <h3>JSON - Pawn[]</h3>
                <p>An list of JSON - Pawn.</p>
                <p>Indexed in played order, 0 to 41.</p>
            </li>
            <li>
                <h3>JSON - Player</h3>
                <dl>
                    <dt>Player_ID</dt>
                    <dd>Unsigned Int</dd>

                    <dt>User_Name</dt>
                    <dd>String - Username</dd>
                </dl>
            </li>
            <li>
                <h3>JSON - Token</h3>
                <dl>
                    <dt>Token</dt>
                    <dd>String - Token</dd>

                    <dt>Player_ID</dt>
                    <dd>Unsigned Int</dd>

                    <dt>Player</dt>
                    <dd>JSON - Player</dd>
                </dl>
            </li>
            <li>
                <h3>String - Username</h3>
                <p>Lengt: 1 to 255.</p>
            </li>
            <li>
                <h3>String - Token</h3>
                <p>Lengt: 32.</p>
            </li>
            <li>
                <h3>String - Color</h3>
                <p>One of:</p>
                <ul>
                    <li>Red</li>
                    <li>Blue</li>
                </ul>
            </li>
            <li>
                <h3>String - Status</h3>
                <p>One of:</p>
                <ul>
                    <li>waiting for player 1</li>
                    <li>waiting for player 2</li>
                    <li>won by player 1</li>
                    <li>won by player 2</li>
                    <li>tie</li>
                </ul>
            </li>
            <li>
                <h3>String - Datetime</h3>
                <p>Lengt: 19.</p>
                <p>Format: YYYY-MM-DD HH:II:SS.</p>
            </li>
        </ul>
        <script>
            var h3_click = function(event) {
                var h3 = event.currentTarget;
                var li = h3.parentElement;
                console.log([li, h3, event]);
                if(li.classList.contains('open')) {
                    li.classList.add('closed');
                    li.classList.remove('open');
                }
                else {
                    li.classList.add('open');
                    li.classList.remove('closed');
                }
            };
            var h3s = document.getElementsByTagName('h3');
            var h3_count = h3s.length;
            for(var index = 0; index < h3_count; index++)
            {
                var h3 = h3s[index];
                h3.parentElement.classList.add('closed');
                h3.addEventListener('click', h3_click);
            }
        </script>
    </body>
</html>
