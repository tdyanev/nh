<template>

<div class="stack" v-if="value > 0">
    <div class="stacked" v-for="chip in chips">
        <div class="pokerchip iso" v-for="n in chip.count"
        :class="chip.color" :style="{ top: ((n - 1) * -7) + 'px'}"
        ></div>
    </div>


    <div class="amount"><span class="badge badge-secondary">
    {{ this.amount }}</span></div>

</div>

</template>

<script>

const denom = [
    { value: 500, color: 'pink' },
    { value: 100, color: 'black' },
    { value: 25, color: 'green' },
    { value: 5, color: 'red' },
    { value: 1, color: 'blue' },

]

export default {
    props: {
        currency: {
            type: String,
            default: '$',
        },
        value: {
            type: Number,
            default: 0,
        },
    },
    data() {
        return {
        }
    },
    computed: {
        amount() {
            return this.currency === '$' ?
                this.currency + this.value :
                this.value + ' ' + this.currency;
        },
        chips() {
            let i = this.value, key = 0, chips = [], x = 100, j = 0;

            while (i || !!denom[key]) {
                if (i >= denom[key].value) {
                    chips[j] = chips[j] || {
                        color: denom[key].color,
                        count: 0,
                    };

                    chips[j].count = chips[j].count + 1;

                    //chips.push(denom[key].color);

                    i -= denom[key].value;
                } else {
                    key++;
                    j = chips.length;
                }
            }
            return chips;
        }
    }
}
</script>

<style>
.stack { position: absolute; }
.amount { text-align: center; margin: 0; font-size: 2em; position:relative; top: -15px; }
.pokerchip{
  margin: 0 .2em;
  position:relative;
  display:inline-block;
  -webkit-box-sizing:border-box;
  -moz-box-sizing:border-box;
  box-sizing:border-box;
  width:3em;
  height:3em;
  background:#555;
  border-radius:50%;
  border:.2em dashed white;
  //transition:all .5s ease;
  backface-visibility:hidden;
}

.stacked {
    display: inline-block;
    position: relative;
    float: left;
}

.stacked .pokerchip:not(:first-child) {
    position: absolute; left: 0;
}

.pokerchip.red{ background:#DD4631; }

.pokerchip.green{ background:green; }

.pokerchip.pink{ background: pink; }

.pokerchip.blue{ background:#317DDD; }



.pokerchip.flat{
  box-shadow:0 0 0 .2em #fff;
}

.pokerchip.iso{
  transform: perspective(3000px) rotateX(55deg) rotateZ(-40deg);
  box-shadow:
    -1px 1px 0px #555,
    -2px 2px 0px #555,
    -3px 3px 0px #555,
    -4px 4px 0px #555,
    -5px 5px 0px #555,
    -6px 6px 10px #555;
}

</style>
