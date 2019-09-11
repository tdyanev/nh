<template>

<div class="player" :style="{
    top: coords.seat.y + 'px',
    left: coords.seat.x + 'px',
    opacity: this.folded ? 0.5 : 1,
}" :class="{ active, }">

    <div class="player-bet-area" :style="{
        top: coords.bets.y + 'px',
        left: coords.bets.x + 'px',
    }">
        <chips :value="currentBet"></chips>
    </div>

    <div class="player-cards">
        <card :label="card" v-for="(card, key) in cards"
            :key="key"></card>
    </div>
    <div class="row">

        <div class="col-3">round image</div>
        <div class="col-9">
            <div> {{ name }}</div>
            <div> {{ stack - invested }}</div>
            <div class="player-action">{{ action }}</div>
        </div>

    </div>



</div>

</template>

<script>
import Card from './Card';
import Chips from './Chips';
import { EventBus } from './EventBus.js';

export default {
    props: {
        coords: Object,
        name: String,
        stack: Number,
    },
    components: {
        Card,
        Chips,
    },
    data() {
        let id = this.$vnode.key;

        return Object.assign({
            id,
            eventName: 'player-' + id,
        }, this.initialData());
    },
    mounted() {
        EventBus.$on('dealCards', data => {
            if (data.hero === this.id) {
                this.cards = data.cards;

                EventBus.$emit('handAction', {
                    key: this.name,
                    value: 'is dealt ' + data.cards.join(','),
                });

            } else {
                this.cards = [null, null];
            }
        });

        EventBus.$on(this.eventName, (payload) => {
            if (! payload.method || ! this[payload.method]) {
                return;
            }
            this[payload.method].call(this, payload.value);
        });

        EventBus.$on('collectBets', () => {
            this.currentBet = 0;
            this.action = '';
        });

        EventBus.$on('resetHand', () => {
            Object.assign(this.$data, this.initialData());
        });

    },

    methods: {
        dealCards(cards) {
            this.cards = cards;
        },

        initialData() {
            return {
                cards: [],
                action: '',
                currentBet: 0,
                invested: 0,
                folded: false,
                active: false,
            }
        },

        setFocus() {
            this.active = true;
        },

        removeFocus() {
            this.active = false;
        },

        betTo(value, action = 'bet', showSize=true) {
            this.stackSize -= value;
            this.invested += value;

            this.currentBet = value;

            this.emitAction(action + (showSize ? ' ' + value : ''));
        },

        raiseTo(value) {

            this.invested -= this.currentBet;

            return this.betTo(value, 'raise');
        },

        fold() {
            this.folded = true;
            this.emitAction('fold');
        },

        post(value) {
            return this.betTo(value, 'post blind');
        },

        check() {
            this.emitAction('check');
        },

        emitAction(action) {
            this.action = action;

            EventBus.$emit('handAction', {
                key: this.name,
                value: action,
            });
        },

        callBet(value) {
            this.invested -= this.currentBet;

            return this.betTo(value, 'call', false);
        }
    },
    created() {

    }
}
</script>

<style>
.player {
    position: absolute;
    width: 200px;
    height: 80px;
    background: black;
    color: white;
    border: 3px solid gray;
    border-radius: 5px;
/*
	left: 50%;
	top: 50%;
	-webkit-transform: translateX(-50%) translateY(-50%);
	transform: translateX(-50%) translateY(-50%);
	position: relative;
*/
}

.player.active {
box-shadow: 0px 0px 0px 10px #f00;
}

.player-bet-area {
    position: absolute;
    width: 100%;
}

.player-cards {
    position: absolute;
    top: -40px;
    right: 0px;
    width: 145px;
    height: 37px;
    overflow: hidden;
}

.player-action {
    text-transform: uppercase;
}
</style>
