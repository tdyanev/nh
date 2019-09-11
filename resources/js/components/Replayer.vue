<template>

<div class="replayer">
    <poker-table :players="players"
        :blinds="blinds"
        v-bind="table"
    ></poker-table>


    <div>replay buttons</div>
    <button @click="next">next</button>


    <ol>
        <li v-for="(item, i) in history">{{ item.key }} {{ item.value }}
            <button @click="jumpTo(i)">jump</button>
        </li>
    </ol>
</div>

</template>

<script>
import PokerTable from './PokerTable'
import { EventBus } from './EventBus.js';

export default {
    props: {
        table: Object,
        actions: String,

        // below will be removed
        players: Array,
        blinds: Array,
    },
    components: {
        PokerTable,
    },

    data() {

        return {
            actionList: this.actions.split('/'),
            currentActionId: 0,
            history: this.initialHistory(),
        }
    },
    computed: {
    },

    mounted() {
        EventBus.$on('handAction', data => {
            this.history.push(data);
        });
    },

    methods: {

        initialHistory() {
            return [{
                key: 'Start of the hand',
            }];
        },

        jumpTo(step=0) {
            let action = this.actionList[step],
                code, value, data, eventName;

            if (! action) {
                console.log('invalid action');
                return;
            }

            EventBus.$emit('resetHand');

            this.history = this.initialHistory();
            this.currentActionId = 0;

            for (let i = 0; i < step; i++) {
                this.next();
            }

        },

        next() {
            let action = this.actionList[this.currentActionId],
                code = action.charAt(0),
                value = action.slice(2),
                data = {},
                eventName;

            switch(code) {
                case 'P':
                    eventName = 'post';
                    data.type = value;
                break;
                case 'D':
                    data.cards = value.split(',');
                    data.hero = this.table.hero;

                    eventName = 'dealCards';
                break;
                case 'F':
                    eventName = 'fold';
                break;
                case 'X':
                    eventName = 'check';
                break;
                case 'C':
                    eventName = 'call';
                break;
                case 'Z':
                    eventName = 'dealBoardCards';
                    data.cards = value.split(',');
                break;
                case 'F':

                break;
                case 'R':
                case 'B':
                    data.value = parseInt(value, 10);
                    data.type = code;
                    eventName = 'bet';
                break;

                default:
            }

            EventBus.$emit(eventName, data);

            this.currentActionId++;
        },


    }
}
</script>

<style>
</style>
