<table class="table table-bordered">
    <thead>
    <tr>
        <th colspan="7" class="text-center">League Table</th>

    </tr>
    <tr>
        <th>Teams</th>
        <th>PTS</th>
        <th>P</th>
        <th>W</th>
        <th>D</th>
        <th>L</th>
        <th>GD</th>

    </tr>
    </thead>
    <tbody>

    @foreach($teams as $team)
        <tr>
            <td>{{ $team->name }}</td>
            <td>{{ $team->total_pts }}</td>
            <td>{{ $current_week }}</td>
            <td>{{ $team->total_win }}</td>
            <td>{{ $team->total_delay }}</td>
            <td>{{ $team->total_lose }}</td>
            <td>{{ $team->total_average }}</td>
        </tr>
    @endforeach
    </tbody>
</table>