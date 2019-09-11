<template>

<!--
<div class="p-card" :class="`suit-${ suit }`">

    <div class="tl t-left-label">
        <div class="x">{{ value }}</div>
        <div class="x">{{ suits[suit] }}</div>
    </div>

    <div class="tl big">{{ value }} </div>

    <div class="tl b-right-label">
        <p>{{ suits[suit] }}</p>
        <p>{{ value }}</p>
    </div>
</div>

-->
<div class="p-card" :class="`suit-${ suitCode }`">
  <div class="z top">
    <p>{{ value }}</p>
    <p>{{ suit }}</p>
  </div>

  <div class="center">{{ value }}</div>

  <div class="z bottom">
    <p>{{ suit }}</p>
    <p>{{ value }}</p>
  </div>

</div>

</template>

<script>

export default {
    props: {
        label: String,
    },
    data() {
        let suits = {
              c: '♣ ',
              d: '♦',
              h: '♥',
              s: '♠',
            },
            suit, suitCode, value;

        if (this.label) {
            suitCode = this.label.slice(-1);
            value = this.label.slice(0, -1);
            suit = suits[suitCode];
        } else {
            suitCode = '';
            suit = '';
            value = ''
        }

        return {
            suit,
            suitCode,
            value,
        }
    },
    mounted() {
    },
    computed: {


    },
}
</script>

<style>

.p-card {
  width: 70px;
  height: 110px;
  position: relative;
  color: white;
  font-weight: bold;
  border-radius: 6px;
  font-family: Helvetica;
  display: inline-block;
  background: gray;
  border: 1px solid orange;
  margin-right: 1px;
}

.z {
  position: absolute;
    font-size: .9em;
    text-align: center;
}

.center {
  text-align: center;
  font-size: 2.8em;
  padding-top: 25px;
}

.top {
  left: 4px;
  top: 4px;

}

.bottom {
  bottom: 4px;
  right: 4px;
}

.p-card p {
  margin: 0;
  padding: 0;
  line-height: 1em;
}

.suit-c { background: green; }
.suit-d { background: blue; }
.suit-h { background: red; }
.suit-s { background: black; }

/* 
.p-card {
    color: white;
    width: 60px;
    height: 100px;
    border-radius: 3px;
    font-weight: bold;
    display: inline-block;
    position: relative;
}

.tl {
    position: absolute;
}

.big {
    font-size: 3em;
    text-align: center;
}

.x { margin:0; padding: 0;}

.t-left-label {
    left: 0;
    top: 0;
}

.b-right-label {
    right: 0;
    bottom: 0;
}

.p-card .label { font-size: 2.5em; }
.p-card .suit { font-size: .9em; }
****/
</style>
