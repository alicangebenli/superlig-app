<?php

namespace App\Http\Controllers;

use App\Model\Match;
use App\Model\Setting;
use App\Model\Team;
use ConsoleTVs\Charts\Facades\Charts;

class HomeController extends Controller
{
    // Anasayfa / Başlangıç
    public  function index()
    {

        $teams = Team::get()->sortByDesc('total_average')->sortByDesc('total_pts');
        $match_teams = Match::orderBy('id','desc')->get();
        $current_week = Setting::find(1)->current_week;
        $possibility_win = ($current_week==4 || $current_week==5) ? $this->possibility_win() : [];
        $possibility_win_collection = collect($possibility_win);
        $sorted = $possibility_win_collection->sortByDesc('p');
        //dd($possibility_win);
        $chart = ($possibility_win != []) ? Charts::create('bar', 'highcharts')
                ->title('Kazanma Oranları')
                ->elementLabel('Kazanma Oranları (%)')
                ->labels([$possibility_win[0]["team"],$possibility_win[1]["team"],$possibility_win[2]["team"],$possibility_win[3]["team"]])
                ->values([$possibility_win[0]["p"],$possibility_win[1]["p"],$possibility_win[2]["p"],$possibility_win[3]["p"]])
                ->dimensions(350,300)
                ->responsive(false) : [];

        return view("index",compact('teams','match_teams','current_week','sorted','chart'));

    }
    // Haftalık Maç Oynat
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


    // Tüm Maçların Oynat
    public function allMatch(){
        $count = Setting::find(1)->current_week;
        for ($i = 1; $i<=6-$count; $i++){
           $this->match();
        }
        return redirect()->back();
    }
    // Haftalık Oynayacak Takımların Listelenmesi
    public function get_match_team($current_week){
        $team_1 = rand(1,4);
        $team_2 = rand(1,4);

        if(Match::where(['team_1'=>$team_1,'team_2'=>$team_2])->count() > 0 || $team_1 == $team_2 || Match::where(['week'=>$current_week,'team_1'=>$team_1])->count() > 0 || Match::where(['week'=>$current_week,'team_2'=>$team_1])->count() > 0 || Match::where(['week'=>$current_week,'team_1'=>$team_2])->count() > 0 || Match::where(['week'=>$current_week,'team_2'=>$team_2])->count() > 0){

            return $this->get_match_team($current_week);

        }
        return ['team_1'=>$team_1,'team_2'=>$team_2];


    }
    // Kazanma Oranlarını Bulma
    public function possibility_win(){
        $current_week = Setting::find(1)->current_week;
        if($current_week == 4) {
            if ((Team::find(1)->total_pts - Team::find(2)->total_pts) > 6 && (Team::find(1)->total_pts - Team::find(3)->total_pts) > 6 && (Team::find(1)->total_pts - Team::find(4)->total_pts) > 6) {
                $y1 = 0;
                $team_1 = 100;
            } else {
                $y1 = 1;
            }

            if ((Team::find(2)->total_pts - Team::find(1)->total_pts) > 6 && (Team::find(2)->total_pts - Team::find(3)->total_pts) > 6 && (Team::find(2)->total_pts - Team::find(4)->total_pts) > 6) {
                $y2 = 0;
                $team_2 = 100;
            } else {
                $y2 = 1;
            }

            if ((Team::find(3)->total_pts - Team::find(1)->total_pts) > 6 && (Team::find(3)->total_pts - Team::find(2)->total_pts) > 6 && (Team::find(3)->total_pts - Team::find(4)->total_pts) > 6) {
                $y3 = 0;
                $team_3 = 100;
            } else {
                $y3 = 1;
            }

            if ((Team::find(4)->total_pts - Team::find(1)->total_pts) > 6 && (Team::find(4)->total_pts - Team::find(2)->total_pts) > 6 && (Team::find(4)->total_pts - Team::find(3)->total_pts) > 6) {
                $y4 = 0;
                $team_4 = 100;
            } else {
                $y4 = 1;
            }
        }else {
            if ((Team::find(1)->total_pts - Team::find(2)->total_pts) > 3 && (Team::find(1)->total_pts - Team::find(3)->total_pts) > 3 && (Team::find(1)->total_pts - Team::find(4)->total_pts) > 3) {
                $y1 = 0;
                $team_1 = 100;
            } else {
                $y1 = 1;
            }

            if ((Team::find(2)->total_pts - Team::find(1)->total_pts) > 3 && (Team::find(2)->total_pts - Team::find(3)->total_pts) > 3 && (Team::find(2)->total_pts - Team::find(4)->total_pts) > 3) {
                $y2 = 0;
                $team_2 = 100;
            } else {
                $y2 = 1;
            }

            if ((Team::find(3)->total_pts - Team::find(1)->total_pts) > 3 && (Team::find(3)->total_pts - Team::find(2)->total_pts) > 3 && (Team::find(3)->total_pts - Team::find(4)->total_pts) > 3) {
                $y3 = 0;
                $team_3 = 100;
            } else {
                $y3 = 1;
            }

            if ((Team::find(4)->total_pts - Team::find(1)->total_pts) > 3 && (Team::find(4)->total_pts - Team::find(2)->total_pts) > 3 && (Team::find(4)->total_pts - Team::find(3)->total_pts) > 3) {
                $y4 = 0;
                $team_4 = 100;
            } else {
                $y4 = 1;
            }
        }
        if ($y1==1){
            $team_1 = $y2 * $y3 * $y4 *round((Team::find(1)->total_win / (Team::find(1)->total_win + Team::find(2)->total_win + Team::find(3)->total_win + Team::find(4)->total_win)) * 100);
        }
        if ($y2==1){
            $team_2 = $y1 * $y3 * $y4 *round((Team::find(2)->total_win / (Team::find(1)->total_win +Team::find(2)->total_win + Team::find(3)->total_win + Team::find(4)->total_win)) * 100);
        }
        if ($y3==1){
            $team_3= $y1 * $y2 * $y4 * round((Team::find(3)->total_win / (Team::find(1)->total_win +Team::find(2)->total_win + Team::find(3)->total_win + Team::find(4)->total_win)) * 100);
        }
        if($y4 == 1){
            $team_4= $y1 * $y2 * $y3 * round((Team::find(4)->total_win / (Team::find(1)->total_win +Team::find(2)->total_win + Team::find(3)->total_win + Team::find(4)->total_win)) * 100);
        }

        return
            [
                ["team"=>Team::find(1)->name,"p"=>$team_1],
                ["team"=>Team::find(2)->name,"p"=>$team_2],
                ["team"=>Team::find(3)->name,"p"=>$team_3],
                ["team"=>Team::find(4)->name,"p"=>$team_4]

            ]
            ;
    }
    // Fikstür Tablosu Ajax
    public function getFTable()
    {
        $this->validate(request(),[
            "value" => "numeric|required",
            "match_id"=> "numeric|required",
            "team"=>"numeric|required"
        ]);
        if (request()->team == 1){
            Match::where("id",request()->match_id)->update(["team_1_goal" =>request()->value]);
        }else{
            Match::where("id",request()->match_id)->update(["team_2_goal" =>request()->value]);
        }

        $teams = Team::get()->sortByDesc('total_average')->sortByDesc('total_pts');
        $current_week = Setting::find(1)->current_week;
        return view("render.f",compact('teams','current_week'));

    }
    // Probability Tablosu Ajax
    public function getPTable(){
        $current_week = Setting::find(1)->current_week;
        $possibility_win = ($current_week==4 || $current_week==5) ? $this->possibility_win() : [];
        $possibility_win_collection = collect($possibility_win);
        $sorted = $possibility_win_collection->sortByDesc('p');

        $chart = ($possibility_win != []) ? Charts::create('bar', 'highcharts')
            ->title('Kazanma Oranları')
            ->elementLabel('Kazanma Oranları (%)')
            ->labels([$possibility_win[0]["team"],$possibility_win[1]["team"],$possibility_win[2]["team"],$possibility_win[3]["team"]])
            ->values([$possibility_win[0]["p"],$possibility_win[1]["p"],$possibility_win[2]["p"],$possibility_win[3]["p"]])
            ->dimensions(350,300)
            ->responsive(false) : [];
        return view("render.p",compact('current_week','sorted','chart'));
    }
}
