<?php
/**
 * Created by PhpStorm.
 * User: keiko
 * Date: 27/12/16
 * Time: 16:08
 */

namespace coolracing\models;

use Illuminate\Database\Eloquent\Model;
class Possede extends Model
{
    protected $table = 'possede';
    protected $primaryKey = array('idParticipant', 'idCompte');
    protected $fillable = array('idParticipant', 'idCompte');
    public $timestamps = false;
    public $incrementing = false;

    public function possedes(){
        return Possede::all();
    }
    public function getByParticipant($idPar){
        return Possede::where('idParticipant', '=',$idPar)->first();
    }
    public function getByCompte($idCompte){
        return Possede::where('idCompte', '=', $idCompte)->first();
    }
    public function participants(){
        return $this->belongsTo('\coolracing\models\Participant','idParticipant');
    }
    public function comptes(){
        return $this->belongsTo('\coolracing\models\Compte', 'idCompte');
    }
}