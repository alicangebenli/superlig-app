<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    //
    public $timestamps = false;
    public $guarded = [];
    protected $appends = ["total_win","total_lose","total_average","total_delay","total_pts"];
    public function matchesone(){
        return $this->hasMany('App\Model\Match','team_1','id');
    }
    public function matchestwo(){
        return $this->hasMany('App\Model\Match','team_2','id');
    }
    public function getTotalWinAttribute(){
        $win = 0;
        foreach ($this->matchesone as $value){
            if ($value->team_1_goal > $value->team_2_goal){
                $win++;
            }
        }
        foreach ($this->matchestwo as $value){
            if ($value->team_2_goal > $value->team_1_goal){
                $win++;
            }
        }

        return $win;

    }
    public function getTotalDelayAttribute(){
        $delay = 0;
        foreach ($this->matchesone as $value){
            if ($value->team_1_goal == $value->team_2_goal){
                $delay++;
            }
        }
        foreach ($this->matchestwo as $value){
            if ($value->team_2_goal == $value->team_1_goal){
                $delay++;
            }
        }

        return $delay;

    }
    public function getTotalLoseAttribute(){
        $lose = 0;
        foreach ($this->matchesone as $value){
            if ($value->team_1_goal < $value->team_2_goal){
                $lose++;
            }
        }
        foreach ($this->matchestwo as $value){
            if ($value->team_2_goal < $value->team_1_goal){
                $lose++;
            }
        }

        return $lose;

    }

    public function getTotalAverageAttribute(){
        $w_goal = 0;
        foreach ($this->matchesone as $value){
            $w_goal += $value->team_1_goal;
        }
        foreach ($this->matchestwo as $value){
            $w_goal += $value->team_2_goal;
        }

        $l_goal = 0;
        foreach ($this->matchesone as $value){
            $l_goal += $value->team_2_goal;
        }
        foreach ($this->matchestwo as $value){
            $l_goal += $value->team_1_goal;
        }

        return $w_goal - $l_goal;
    }
    public function getTotalPtsAttribute()
    {
        return $this->total_win * 3 + $this->total_delay;
    }



}
