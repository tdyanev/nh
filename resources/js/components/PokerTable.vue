<template>

<div class="table">
    <player v-for="(player, key) in players" :key="key"
        :coords="coords[key]"
        v-bind="player"
    ></player>

    <div class="center-area">
        <div class="pot">Pot: {{ pot }}</div>
        <div class="board">
            <card :label="card" v-for="(card, key) in board" :key="key"></card>
        </div>
        <div class="chips">
            <chips :value="chips"></chips>
        </div>
    </div>

</div>

</template>

<script>
import Player from './Player'
import Chips from './Chips'
import { EventBus } from './EventBus.js';
//import { EventBus } from './EventBus';

export default {
    props: {
        players: Array,
        blinds: Array,
        button: Number,
    },
    components: {
        Player,
        Chips,
    },
    data() {
        return Object.assign({
            coords: [
                {
                    seat: { x: 185, y: 37},
                    bets: { x: 70, y: 95},
                },
                {
                    seat: { x: 489, y: 37},
                    bets: { x: 70, y: 95},
                },
                {
                    seat: { x: 815, y: 37},
                    bets: { x: 65, y: 95},
                },
                {
                    seat: { x: 5, y: 265},
                    bets: { x: 5, y: 5},
                },
                {
                    seat: { x: 5, y: 5},
                    bets: { x: 5, y: 5},
                },
            ]
        }, this.initialData());
    },

    methods: {
        currentPlayer() {
            return this.currentPlayerId;
        },

        findNextId(i, folded=[]) {
            let j = i;

            do {
                j = ++j >= this.players.length ? 0 : j;

                if (j === i) {
                    return null;
                }

            } while (folded.indexOf(j) > -1 );

            return j;
        },

        switchPlayer() {
            EventBus.$emit(this.eventName(), {
                method: 'removeFocus',
            });


            //check if it's time for new street or showdown

            let id = this.findNextId(this.currentPlayerId, this.folded);

            if (id === this.lastAggressor) {
                //time for new street

                if (this.board.length === 5) {
                    console.log('time for showdown');

                    return;
                }

                if (this.folded.length + 1 >= this.players.length) {
                    // everybody folded, we have a winner

                    console.log('hand over');

                    return;
                }

                //time for new street

                this.collectBets();
                this.resetInvestments();
                this.lastAggressor = null;

                return;
            }

            this.currentPlayerId = id;

            this.focusCurrentPlayer();
        },

        focusCurrentPlayer() {
            EventBus.$emit(this.eventName(), {
                method: 'setFocus',
            });
        },

        initialData() {

            return {
                pot: 0,
                chips: 0,
                lastBet: 0,
                currentPlayerId: this.findNextId(this.button),
                investedOnStreet: this.players.map(e => 0),
                lastAggressor: null,
                board: [],
                folded: [],
            }

        },

        eventName() {
            return 'player-' + this.currentPlayer();
        },

        updatePot(value, setAggressor=true) {
            this.pot += value;
            this.lastBet = value;

            if (setAggressor) {
                this.lastAggressor = this.currentPlayerId;
            }

            this.investedOnStreet[this.currentPlayerId] += value;
        },

        resetInvestments() {
            this.investedOnStreet = this.players.map(e => 0);
        },

        collectBets() {
            // not sure if this is okay
            //setTimeout(() => {
                this.chips = this.pot;
                EventBus.$emit('collectBets');
            //}, 500);
        }
    },

    mounted() {
        EventBus.$on('inflatePot', value => {
            //this.pot += value;
        });

        EventBus.$on('dealBoardCards', data => {
            let street = '';

            switch(this.board.length) {
                case 0:
                    street = 'flop';
                    break;
                case 3:
                    street = 'turn';
                    break;
                case 4:
                    street = 'river';
                    break;
            }

            EventBus.$emit('handAction', {
                key: 'Dealing ' + street + ':',
                value: data.cards.join(','),
            });

            this.board = this.board.concat.apply(this.board, data.cards);
            this.currentPlayerId = this.button;
            this.switchPlayer();
        });

        EventBus.$on('post', data => {
            let value = data.type === 'SB' ? this.blinds[0] : this.blinds[1];

            EventBus.$emit(this.eventName(), {
                method: 'post',
                value,
            });

            this.updatePot(value);

            this.switchPlayer();
        });

        EventBus.$on('bet', data => {
            let method = data.type === 'R' ? 'raiseTo' : 'betTo';

            EventBus.$emit(this.eventName(), {
                method,
                value: data.value,

            });

            this.updatePot(data.value - this.investedOnStreet[this.currentPlayerId]);

            this.switchPlayer();
        });

        EventBus.$on('fold', () => {
            EventBus.$emit(this.eventName(), {
                method: 'fold',
            });

            this.folded.push(this.currentPlayerId);
            this.switchPlayer();
        });

        EventBus.$on('check', () => {
            EventBus.$emit(this.eventName(), {
                method: 'check',
            });

            this.switchPlayer();
        });

        EventBus.$on('call', () => {
            EventBus.$emit(this.eventName(), {
                method: 'callBet',
                value: this.lastBet,
            });

            this.updatePot(this.lastBet - this.investedOnStreet[this.currentPlayerId], false);

            this.switchPlayer();
        });

        EventBus.$on('resetHand', () => {
            Object.assign(this.$data, this.initialData());
        });
    },


    computed: {
    }
}
</script>

<style>
.table {
	width: 1000px;
	height: 450px;
	background-color: #4aad4a;
	border: 15px solid #a95555;
	border-radius: 150px;
	margin: 50px;
/*
	left: 50%;
	top: 50%;
	-webkit-transform: translateX(-50%) translateY(-50%);
	transform: translateX(-50%) translateY(-50%);
	position: relative;
*/
}
.center-area {
    position: absolute;
    top: 250px;
    left: 300px;
    width: 600px;
}

.board {
    border: 3px solid yellow;
    border-radius: 5px;
    height: 150px;
}

.pot, .chips {
    text-align: center;
}
</style>
