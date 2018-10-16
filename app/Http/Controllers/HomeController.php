<?php

namespace App\Http\Controllers;

use App\Model\Match;
use App\Model\Setting;
use App\Model\Team;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class HomeController extends Controller
{
    //
    public  function index()
    {
        Charts::database(User::all(), 'bar', 'highcharts')
            ->setElementLabel("Total")
            ->setDimensions(1000, 500)
            ->setResponsive(false)
            ->groupByDay();

        //dd(Team::find(1)->total_win);


        $teams = Team::get()->sortByDesc('total_average')->sortByDesc('total_pts');
        $match_teams = Match::orderBy('id','desc')->get();
        $current_week = Setting::find(1)->current_week;
        $possibility_win = ($current_week==4 || $current_week==5) ? $this->possibility_win() : [];
        $possibility_win_collection = collect($possibility_win);
        $sorted = $possibility_win_collection->sortByDesc('p');


        return view("index",compact('teams','match_teams','current_week','sorted'));

    }

    public function match(){





        // Haftayı Arttırma
        if(Match::count() % 2 == 0) {
            Setting::find(1)->increment('current_week', 1);
        }
        if(Setting::find(1)->current_week == 7){
            Match::truncate();
            Setting::find(1)->update(["current_week"=>1]);
        }
        $curent_week = Setting::find(1)->current_week;
        // Karşılaşma Yapacak Takımların Bulunması
        $teams = $this->get_match_team($curent_week);


        // Oyuncu Gücü
        if($curent_week == 1) {
            $power = [];
            $power[1] = rand(1,30);
            $power[2] = rand(1,30);
            $power[3] = rand(1,30);
            $power[4] = rand(1,30);
            for ($i = 1; $i <= count($power); $i++) {
                Team::find($i)->update(['player_power' => $power[$i]]);
            }
        }

        // Gol Sayısı
            $team_1_goal = ceil((Team::find($teams["team_1"])->player_power + 10 + rand(-10,10))/10);
            $team_2_goal = ceil((Team::find($teams["team_2"])->player_power + 0 + rand(1,20))/10);


        // Karşılaşma Kayıt İşlemi

        Match::create(['week'=>$curent_week,'team_1'=>$teams["team_1"],'team_2'=>$teams["team_2"],'team_1_goal'=>$team_1_goal,'team_2_goal'=>$team_2_goal]);


        if(Match::count() % 2 != 0){
            $this->match();
        }
        //return  dd($team_1);
        return redirect()->back();

    }

    public function get_match_team($current_week){
        $team_1 = rand(1,4);
        $team_2 = rand(1,4);

        if(Match::where(['team_1'=>$team_1,'team_2'=>$team_2])->count() > 0 || $team_1 == $team_2 || Match::where(['week'=>$current_week,'team_1'=>$team_1])->count() > 0 || Match::where(['week'=>$current_week,'team_2'=>$team_1])->count() > 0 || Match::where(['week'=>$current_week,'team_1'=>$team_2])->count() > 0 || Match::where(['week'=>$current_week,'team_2'=>$team_2])->count() > 0){

            return $this->get_match_team($current_week);

        }
        return ['team_1'=>$team_1,'team_2'=>$team_2];


    }
    public function possibility_win(){
        $team_1 = round((Team::find(1)->total_win / (Team::find(1)->total_win + Team::find(2)->total_win + Team::find(3)->total_win + Team::find(4)->total_win)) * 100);
        $team_2 = round((Team::find(2)->total_win / (Team::find(1)->total_win +Team::find(2)->total_win + Team::find(3)->total_win + Team::find(4)->total_win)) * 100);
        $team_3= round((Team::find(3)->total_win / (Team::find(1)->total_win +Team::find(2)->total_win + Team::find(3)->total_win + Team::find(4)->total_win)) * 100);
        $team_4= round((Team::find(4)->total_win / (Team::find(1)->total_win +Team::find(2)->total_win + Team::find(3)->total_win + Team::find(4)->total_win)) * 100);
        return
            [
                ["team"=>Team::find(1)->name,"p"=>$team_1],
                ["team"=>Team::find(2)->name,"p"=>$team_2],
                ["team"=>Team::find(3)->name,"p"=>$team_3],
                ["team"=>Team::find(4)->name,"p"=>$team_4]

            ]
            ;
    }

    public function allMatch(){
        $count = Setting::find(1)->current_week;
        for ($i = 1; $i<=6-$count; $i++){
           $this->match();
        }
        return redirect()->back();
    }

    function getFTable()
    {

        if (request()->team == 1){
            Match::where("id",request()->match_id)->update(["team_1_goal" =>request()->value]);
        }else{
            Match::where("id",request()->match_id)->update(["team_2_goal" =>request()->value]);
        }

        $teams = Team::get()->sortByDesc('total_average')->sortByDesc('total_pts');
        $current_week = Setting::find(1)->current_week;
        return view("f",compact('teams','current_week'));

    }

}
