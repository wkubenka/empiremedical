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

let app = new Vue({
    el: "#app",
    data: {
        player1Wins: 0,
        player2Wins: 0,
        player1IsNext: true,
        player2IsComputer: true,
        boardData: Array(9).fill(null),
        winner: null
    },
    computed: {
        changeOpponentName: function() {
            return this.player2IsComputer ? "another person" : "the Computer";
        },
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
                [2, 4, 6]
            ];
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
                }
            }
            if (this.winner === null && !this.boardData.includes(null)) {
                this.winner = "Cat";
            }
            if (this.winner !== null) {
                axios
                    .post("/game/complete", {
                        won: this.winner === "X"
                    })
                    .then(function(response) {
                        //console.log(response);
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
            }
        },
        clickSquare: function(i) {
            if (this.boardData[i] !== null || this.winner !== null) return;
            this.$set(this.boardData, i, this.player1IsNext ? "X" : "O");
            this.player1IsNext = !this.player1IsNext;
            this.calculateWinner();
            if (!this.player1IsNext && this.player2IsComputer)
                this.computerMove();
        },
        computerMove: function() {
            if (this.winner !== null) return;
            let i = Math.floor(Math.random() * 9);
            while (this.boardData[i] !== null) {
                i = Math.floor(Math.random() * 9);
            }
            this.clickSquare(i);
        },
        reset: function() {
            this.player1IsNext = Math.random() > 0.5;
            this.boardData = Array(9).fill(null);
            this.winner = null;
            if (!this.player1IsNext && this.player2IsComputer)
                this.computerMove();
        },
        toggleComputer: function() {
            this.player2IsComputer = !this.player2IsComputer;
            this.reset();
            this.player1Wins = 0;
            this.player2Wins = 0;
        }
    }
});

let leaderboard = new Vue({
    el: "#leaderboard",
    data: { open: false, leaders: [] },
    methods: {
        toggleLeaderboard: function() {
            this.open = !this.open;
            if (this.open) {
                axios
                    .get("/leaders")
                    .then(response => {
                        console.log(response);

                        this.leaders = response.data.data;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }
        }
    },
    filters: {
        percentage: function(value) {
            return parseFloat(value).toFixed(2) + "%";
        }
    }
});
