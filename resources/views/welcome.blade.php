<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Tic-Tac-Toe</title>
        <!-- TODO: Move this style to its own file -->
        <style>
            body {
            font: 14px "Century Gothic", Futura, sans-serif;
            margin: 20px;
            }

            ol, ul {
            padding-left: 30px;
            }

            .board-row:after {
            clear: both;
            content: "";
            display: table;
            }

            .status {
            margin-bottom: 10px;
            }

            .square {
            background: #fff;
            border: 1px solid #999;
            float: left;
            font-size: 24px;
            font-weight: bold;
            line-height: 34px;
            height: 34px;
            margin-right: -1px;
            margin-top: -1px;
            padding: 0;
            text-align: center;
            width: 34px;
            }

            .square:focus {
            outline: none;
            }

            .kbd-navigation .square:focus {
            background: #ddd;
            }

            .game {
                display: flex;
                flex-direction: row;
                margin: auto;
            }

            .game-info {
            margin-left: 20px;
            }
            .scoreboard {
                display: table;
                margin: auto;
            }

            .score,
            .team,
            .letter {
                display: table-row;
            }

            .score span,
            .team span,
            .letter span {
                display: table-cell;
            }

            .score span:first-child,
            .team span:first-child,
            .letter span:first-child {
                text-align: right;
                padding-right: 10px;
                position: relative;
            }

            .score span:last-child,
            .team span:last-child,
            .letter span:last-child {
                text-align: left;
                padding-left: 10px;
            }

            /*the line between scores*/
            .score span:first-child::before,
            .team span:first-child::before,
            .letter span:first-child::before {
                content: "-";
                position: absolute;
                right:-2px;
                top: 0;
            }

        </style>

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

        <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
        <script>
            //TODO: Move this to its own file
            let app = new Vue({
                el: '#app',
                data: {
                    player1Wins: 0,
                    player2Wins: 0,
                    player1IsNext: true,
                    player2IsComputer: true,
                    boardData: Array(9).fill(null),
                    winner: null,
                },
                computed: {
                    nextPlayer: function() {
                        return this.player1IsNext  ? 'X' : 'O';
                    },
                    player2Name: function() {
                        return this.player2IsComputer ? "Computer" : "Player 2";
                    }
                },
                methods: {
                    calculateWinner: function() {
                        const lines = [
                            [0, 1, 2],
                            [3, 4, 5],
                            [6, 7, 8],
                            [0, 3, 6],
                            [1, 4, 7],
                            [2, 5, 8],
                            [0, 4, 8],
                            [2, 4, 6],
                        ];
                        for (let i = 0; i < lines.length; i++) {
                            const [a, b, c] = lines[i];
                            if (this.boardData[a] && this.boardData[a] === this.boardData[b] && this.boardData[a] === this.boardData[c]) {
                                this.winner = this.boardData[a];
                                if(this.boardData[a] === "X") this.player1Wins++;
                                else this.player2Wins++;
                            }
                        }
                        if(this.winner === null && !this.boardData.includes(null)){
                             this.winner = 'Cat';
                        }
                    },
                    clickSquare: function(i) {
                        if(this.boardData[i] !== null || this.winner !== null) return;
                        this.$set(this.boardData, i, this.player1IsNext  ? 'X' : 'O');
                        this.player1IsNext = !this.player1IsNext;
                        this.calculateWinner();
                        if(!this.player1IsNext && this.player2IsComputer) this.computerMove();
                    },
                    computerMove: function() {
                        if(this.winner !== null) return;
                        let i = Math.floor(Math.random() * 9);
                        while(this.boardData[i] !== null) {
                            i = Math.floor(Math.random() * 9);
                        }
                        this.clickSquare(i);
                    },
                    reset: function() {
                        this.player1IsNext = Math.random() > .5;
                        this.boardData = Array(9).fill(null);
                        this.winner = null;
                        if(!this.player1IsNext && this.player2IsComputer) this.computerMove();
                    }
                }
            })
        </script>
    </body>
</html>
