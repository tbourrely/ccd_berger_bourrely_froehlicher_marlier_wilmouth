<?php
/**
 * Created by PhpStorm.
 * User: keiko
 * Date: 27/12/16
 * Time: 16:51
 */

namespace coolracing\models;

use \Illuminate\Database\Eloquent\Model;

class Participe extends Model
{
    protected $table = 'participe';
    protected $primaryKey = array('idParticipant','idEpreuve');
    protected $fillable = array('idParticipant','idEpreuve');
    public $incrementing = false; // disable auto_increment
    public $timestamps = false;

    public function participations(){
        return Participe::all();
    }
    public function getByParticipant($idPar){
        return Participe::where('idParticipant', '=', $idPar)->first();
    }
    public function getByEpreuve($idEp){
        return Participe::where('idEpreuve','=',$idEp)->get();
    }
    public function participants(){
        return $this->belongsTo('\coolracing\models\Participant','idParticipant');
    }
    public function epreuves(){
        return $this->belongsTo('\coolracing\models\Epreuve', 'idEpreuve');
    }
    public function countParticipants($idEpreuve){
        return Participe::where('idEpreuve','=',$idEpreuve)->count();
    }
    public function addParticipation($idParticipant, $idEpreuve){
        Participe::firstOrCreate(
            array(
                'idParticipant'=>$idParticipant,
                'idEpreuve'=>$idEpreuve
            )
        );
    }
}