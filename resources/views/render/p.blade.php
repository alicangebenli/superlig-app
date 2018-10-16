<table class="table table-bordered">
    <thead>
    <tr>
        <th colspan="4" class="text-center">Probability Of Champion</th>

    </tr>
    </thead>
    <tbody>

    @if($current_week == 4 || $current_week == 5 )
        <tr>
            <td colspan="4" class="text-center">{{ $current_week }} Week</td>
        </tr>
        @foreach($sorted as $p)

            <tr>
                <td>{{ $p["team"]}}</td>
                <td>%{{ $p["p"]}}</td>
            </tr>
        @endforeach

    @endif
    </tbody>
</table>
<div class="row">
    @if($current_week == 4 || $current_week == 5 )
    {!! $chart->html()  !!}
        @endif
</div>