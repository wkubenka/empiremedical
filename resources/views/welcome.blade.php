<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Tic-Tac-Toe</title>
        <!-- Styles -->

        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div id="app">
                <div class="scoreboard">
                    <div class="score">
                        <span>@{{ player1Wins }}</span><span>@{{ player2Wins }}</span>
                    </div>
                    <div class="team">
                        <span>Player 1</span><span>@{{ player2Name }}</span>
                    </div>
                    <div class="team">
                        <span>X</span><span>O</span>
                    </div>
                </div>
                <div class="game">
                    <div class="game-board">
                        <div>
                            <div class="status">Next player: @{{nextPlayer}}</div>
                            <div class="board-row">
                                <button class="square" @click="clickSquare(0)">@{{ boardData[0] }}</button>
                                <button class="square" @click="clickSquare(1)">@{{ boardData[1] }}</button>
                                <button class="square" @click="clickSquare(2)">@{{ boardData[2] }}</button>
                            </div>
                            <div class="board-row">
                                <button class="square" @click="clickSquare(3)">@{{ boardData[3] }}</button>
                                <button class="square" @click="clickSquare(4)">@{{ boardData[4] }}</button>
                                <button class="square" @click="clickSquare(5)">@{{ boardData[5] }}</button>
                            </div><div class="board-row">
                                <button class="square" @click="clickSquare(6)">@{{ boardData[6] }}</button>
                                <button class="square" @click="clickSquare(7)">@{{ boardData[7] }}</button>
                                <button class="square" @click="clickSquare(8)">@{{ boardData[8] }}</button>
                            </div>
                        </div>
                    </div>
                    <div class="game-info">
                        <div>
                            <p v-if="winner !== null">
                                @{{ winner  }} won the game!
                            </p>
                            <a @click="reset">Reset Game</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="leaderboard" id="leaderboard">
            <!-- Trigger/Open The Modal -->
            <button @click="toggleLeaderboard">Show Leaderboard</button>

            <!-- The Modal -->
            <div v-if="open" class="modal">

                <!-- Modal content -->
                <div class="modal-content">
                    <span @click="toggleLeaderboard" class="close">&times;</span>
                    <p><strong>Most Wins</strong></p>
                    <table>
                        <thead>
                            <tr>
                                <th>
                                    Name
                                </th>
                                <th>
                                    Wins
                                </th>
                                <th>
                                    Games
                                </th>
                                <th>
                                    Win %
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="leader in leaders">
                                <td>
                                    @{{ leader.name }}
                                </td>
                                <td>
                                    @{{ leader.wins }}
                                </td>
                                <td>
                                    @{{ leader.games }}
                                </td>
                                <td>
                                    @{{ leader.win_percent | percentage}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
    </body>
</html>
