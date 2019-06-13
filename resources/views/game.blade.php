@extends('layouts.app')

@section('content')

    {{-- Leaderboard Vue --}}
    <div v-cloak class="leaderboard" id="leaderboard">
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
                @guest
                    <p>Sign in to start climbing the leaderboard.</p>
                @endguest
            </div>

        </div>
    </div>

    {{-- Game Vue --}}
    <div v-cloak id="app">
        {{-- Scoreboard section--}}
        <div class="scoreboard">
            <div class="score">
                <span>@{{ player1Wins }}</span><span>@{{ player2Wins }}</span>
            </div>
            <div class="team">
                <span>
                    @auth
                        {{ Auth::user()->name }}
                    @else
                        Player 1
                    @endauth
                </span><span>@{{ player2Name }}</span>
            </div>
            <div class="letter">
                <span>X</span><span>O</span>
            </div>
        </div>

        {{-- Game section--}}
        <div class="game">
            <div class="game-board">
                <div>
                    <div class="status">@{{status}}</div>
                    <div class="board-row">
                        <button :isDisabled="boardData[0]" class="square" @click="clickSquare(0)">@{{ boardData[0] }}</button>
                        <button :isDisabled="boardData[1]" class="square" @click="clickSquare(1)">@{{ boardData[1] }}</button>
                        <button :isDisabled="boardData[2]" class="square" @click="clickSquare(2)">@{{ boardData[2] }}</button>
                    </div>
                    <div class="board-row">
                        <button :isDisabled="boardData[3]" class="square" @click="clickSquare(3)">@{{ boardData[3] }}</button>
                        <button :isDisabled="boardData[4]" class="square" @click="clickSquare(4)">@{{ boardData[4] }}</button>
                        <button :isDisabled="boardData[5]" class="square" @click="clickSquare(5)">@{{ boardData[5] }}</button>
                    </div><div class="board-row">
                        <button :isDisabled="boardData[6]" class="square" @click="clickSquare(6)">@{{ boardData[6] }}</button>
                        <button :isDisabled="boardData[7]" class="square" @click="clickSquare(7)">@{{ boardData[7] }}</button>
                        <button :isDisabled="boardData[8]" class="square" @click="clickSquare(8)">@{{ boardData[8] }}</button>
                    </div>
                </div>
            </div>

        </div>


        {{-- Actions section --}}
        <div class="game-info">
            <a tabindex="" @click="reset">Reset Game</a><br>
            <a tabindex="" @click="toggleComputer">Play against @{{ changeOpponentName }}</a>
        </div>

    </div>



    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
@endsection
