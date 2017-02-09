<?php

namespace coolracing\models;

use Illuminate\Database\Eloquent\Model;

class Evenement extends Model
{

    protected $table = 'evenement';
    protected $primaryKey = 'idEvenement';
    protected $fillable = array('idEvenement','nom','desc','dateDeb','dateFin','lieu', 'idCompte','termine');
    public $timestamps = false;

    public function evenements(){
        return Evenement::all();
    }
    public function getById($id){
        return Evenement::where('idEvenement','=', $id)->first();
    }
    public function getByName($nom){
        return Evenement::where('nom','=', $nom)->first();
    }
    public function getByDates($dateDeb, $dateFin){
        return Evenement::whereDate('dateDeb','>=',$dateDeb)->whereDate('dateFin','<=',$dateFin)->get();
    }
    public function getByPlace($lieu){
        return Evenement::where('lieu','=',$lieu)->get();
    }

    public function organisateur(){
        return $this->belongsTo('\coolracing\models\Compte', 'idCompte')->get();
    }

    public function epreuves(){
        return $this->hasMany('\coolracing\models\Epreuve', 'idEvenement')->get();
    }
}