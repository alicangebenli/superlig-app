<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Match extends Model
{
    //
    public $timestamps = false;
    public $guarded = [];

    public function teamone(){
       return $this->belongsTo('App\Model\Team','team_1','id');

    }

    public function teamtwo(){
        return $this->belongsTo('App\Model\Team','team_2','id');
    }


}
