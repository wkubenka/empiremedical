/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue");

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component(
    "example-component",
    require("./components/ExampleComponent.vue").default
);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

/**
 * The game itself. Lives on game.blade.php, but could be made into a component
 *
 */
let app = new Vue({
    el: "#app",
    data: {
        player1Wins: 0, //count of times player 1 (or signed in user) has won
        player2Wins: 0, //count of times player 2 (or computer) has won
        player1IsNext: true,
        player2IsComputer: true,
        boardData: Array(9).fill(null), //game information is stored as an array and mapped to the board
        winner: null //used for end of game messages and code execution
    },
    computed: {
        //Play against another person vs Play against the Computer
        changeOpponentName: function() {
            return this.player2IsComputer ? "another person" : "the Computer";
        },
        //Any status message above the board
        //Next Turn, Winner, Etc...
        status: function() {
            let status;
            if (this.winner === null) {
                const letter = this.player1IsNext ? "X" : "O";
                status = "Next Player: " + letter;
            } else {
                status = this.winner + " won the game!";
            }
            return status;
        },
        //Name of player two on the scoreboard
        player2Name: function() {
            return this.player2IsComputer ? "Computer" : "Player 2";
        }
    },
    methods: {
        //Determine if someone has won the game
        calculateWinner: function() {
            const lines = [
                [0, 1, 2],
                [3, 4, 5],
                [6, 7, 8],
                [0, 3, 6],
                [1, 4, 7],
                [2, 5, 8],
                [0, 4, 8],
                [2, 4, 6]
            ]; //all possible winning combinations

            //foreach possible winning combination
            //check if the first index indicated is set
            //and that it matches the second and third
            for (let i = 0; i < lines.length; i++) {
                const [a, b, c] = lines[i];
                if (
                    this.boardData[a] &&
                    this.boardData[a] === this.boardData[b] &&
                    this.boardData[a] === this.boardData[c]
                ) {
                    this.winner = this.boardData[a];
                    if (this.boardData[a] === "X") this.player1Wins++;
                    else this.player2Wins++;
                    break;
                }
            }

            //If no winner is found and all squares have been clicked
            if (this.winner === null && !this.boardData.includes(null)) {
                this.winner = "Cat";
            }

            //If a winner is found
            //Notify the server to increment the user
            if (this.winner !== null) {
                axios
                    .post("/game/complete", {
                        won: this.winner === "X"
                    })
                    .then(function(response) {
                        //console.log(response);
                    })
                    .catch(function(error) {
                        //If not an authorization error
                        if (error.response.status !== 401) console.log(error);
                        //Should do something here to block future requests and save some data
                    });
            }
        },
        //Handle the user clicking on a square
        clickSquare: function(i) {
            //Return if the square has already been clicked
            //or a winner has already been declared.
            if (this.boardData[i] !== null || this.winner !== null) return;
            this.$set(this.boardData, i, this.player1IsNext ? "X" : "O"); //set the boardData index
            this.player1IsNext = !this.player1IsNext;
            this.calculateWinner();
            //If the computer is next
            if (!this.player1IsNext && this.player2IsComputer)
                this.computerMove();
        },
        //Have the computer randomly place a mark
        //This would be a great place to add an "AI"
        computerMove: function() {
            //ensure winner has not already been declared
            if (this.winner !== null) return;
            let i = Math.floor(Math.random() * 9); //0-8
            //find one that isn't already claimed
            while (this.boardData[i] !== null) {
                i = Math.floor(Math.random() * 9);
            }
            this.clickSquare(i);
        },
        //reset the game board
        reset: function() {
            this.player1IsNext = Math.random() >= 0.5; //50% chance of player 1 going first
            this.boardData = Array(9).fill(null);
            this.winner = null;
            if (!this.player1IsNext && this.player2IsComputer)
                this.computerMove();
        },
        //Change between playing against a computer and a player
        toggleComputer: function() {
            this.player2IsComputer = !this.player2IsComputer;
            this.reset();
            this.player1Wins = 0;
            this.player2Wins = 0;
        }
    }
});

//Vue for the leaderboard
//currently on game.blade.php
//could become a component
let leaderboard = new Vue({
    el: "#leaderboard",
    data: { open: false, leaders: [] },
    methods: {
        //Open or close leaderboard
        toggleLeaderboard: function() {
            this.open = !this.open;
            if (this.open) {
                this.loadLeaders();
            }
        },
        //fetch the leaders from the server
        loadLeaders: function() {
            axios
                .get("/leaders")
                .then(response => {
                    this.leaders = response.data.data;
                })
                .catch(error => {
                    console.log(error);
                });
        }
    },
    filters: {
        //Returns XX.XX%
        percentage: function(value) {
            return parseFloat(value).toFixed(2) + "%";
        }
    }
});
