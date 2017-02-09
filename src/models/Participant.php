<?php

namespace coolracing\models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{

    protected $table = 'participant';
    protected $primaryKey = 'idParticipant';
    protected $fillable = array('idParticipant','nom','prenom','email','dossard');
    public $timestamps = false;

    public function participants(){
        return Participant::all();
    }
    public function getById($id){
        return Participant::where('idParticipant','=',$id)->first();
    }
    public function getByName($name){
        return Participant::where('nom','=',$name)->get();
    }
    public function getByPrenom($prenom){
        return Participant::where('prenom','=',$prenom)->get();
    }
    public function getByEmail($email){
        return Participant::where('email','=',$email)->first();
    }
    public function getByAge($age){
        return Participant::where('age','=',$age)->first();
    }
    public function getByDossard($dossard){
        return Participant::where('dossard','=',$dossard)->first();
    }
}