<!doctype html>

<html lang="tr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

        <title>Document</title>
        <style>
            input{
                max-width: 20px;
            }
        </style>
    </head>
    <body>

    <section class="container">
        <div class="row jumbotron mt-5">
            <a href="{{ route('allMatch') }}" class="btn btn-success">Play All</a>

            <a href="{{ route('match') }}" class="btn btn-success" style="margin-left: 80%">Next Week</a>
        </div>
        <div id="r">
            <div class="jumbotron">
                <div class="row">

                    <div class="col-md-4">
                        <div id="f">
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
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div id="r">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th colspan="4" class="text-center">Match Result</th>

                                </tr>
                                </thead>
                                <tbody>
                                <?php $i = 1; ?>
                                @if(count($match_teams) > 0)
                                    @foreach($match_teams as $match_team)
                                        <?php $i++; ?>
                                        @if($i % 2 == 0)
                                            <tr>
                                                <td colspan="4" class="text-center">{{ $match_team->week }} Week</td>
                                            </tr>
                                        @endif
                                        <tr>

                                            <td>{{ $match_team->teamone->name }}</td>
                                            <td><input type="text" value="{{ $match_team->team_1_goal }}" data-matchid="{{ $match_team->id }}" class="teamoneinput"></td>
                                            <td><input type="text" value="{{ $match_team->team_2_goal }}" data-matchid="{{ $match_team->id }}" class="teamtwoinput"></td>
                                            <td>{{ $match_team->teamtwo->name }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div id="p">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th colspan="4" class="text-center">Probability</th>

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
                        </div>
                    </div>
                </div>

            </div>
        </div>


    </section>
    <script
            src="https://code.jquery.com/jquery-3.3.1.js"
            integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script>
        $(document).ready(function () {

            $(".teamoneinput").on("change",function () {
                var match_id = $(this).data("matchid");
                var value = $(this).val();
                $.post( "/variosoft/public/getFTable",
                    {
                        match_id : match_id,
                        value : value,
                        team : 1
                    }
                    , function( data ) {
                    $( "#f" ).html( data );
                });

            });
            $(".teamtwoinput").on("change",function () {
                var match_id = $(this).data("matchid");
                var value = $(this).val();
                $.post( "/variosoft/public/getFTable",
                    {
                        match_id : match_id,
                        value : value,
                        team : 2
                    }
                    , function( data ) {
                        $( "#f" ).html( data );
                    });

            });
        })
    </script>
    </body>
</html>