@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">

<!--
<card label="Ks"></card>
<card label="10c"></card>
<player name="Ivan" :stack="5505" :coords="{
    bets: {

    },
    seat: {

    },

}"></player>

                <poker-table :players="[
                    { name: 'John', stack: 550, },
                    { name: 'John', stack: 250, },
                    { name: 'John', stack: 350, },
                    { name: 'John', stack: 50, },
                ]">
                </poker-table>


-->
<replayer
    stakes="1/2"
    :blinds="[1, 2]"
    :straddles="[]"
    type="Cash"
    game="Holdem"
    :button="1"
    :hero="0"
    :table="{
        blinds: [1, 2],
        button: 1,
        hero: 0,

    }"
    actions="P:SB/P:BB/D:7h,7d/R:10/R:40/F/F/C/Z:Ah,10s,2d/X/B:8/C/Z:5s/X/B:16/R:40/R:110/F"

    :players="[
                    { name: 'Ray', stack: 550, },
                    { name: 'Mike', stack: 250, },
                    { name: 'Tim', stack: 350, },
                    { name: 'John', stack: 50, },
    ]"
>
</replayer>

                </div>
            </div>
        </div>
        <div class="col-md-2">
<!--
            <div class="card-header">{{ __('Hand summary') }}</div>
            <div class="card-body">
            </div>
-->
        </div>
    </div>
</div>
@endsection
