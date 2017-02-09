<?php

namespace coolracing\models;

use Illuminate\Database\Eloquent\Model;

class Epreuve extends Model
{

    protected $table = 'epreuve';
    protected $primaryKey = 'idEpreuve';

    protected $fillable = array('idEpreuve','nom','desc','nbParticipants', 'dateDeb','dateFin', 'idCategorie', 'idEvenement','close', 'resultats');
    public $timestamps = false;

    public function epreuves(){
        return Epreuve::all();
    }

    public function getById($id){
        return Epreuve::where('idEpreuve','=',$id)->first();
    }

    public function getByName($name){
        return Epreuve::where('nome','=',$name)->first();
    }

    public function getByDates($dateDeb, $dateFin){
        return Epreuve::whereDate('dateDeb','>=',$dateDeb)->whereDate('dateFin','<=',$dateFin)->get();
    }

    public function evenement(){
        return $this->belongsTo('\coolracing\models\Evenement', 'idEvenement')->first();
    }

    public function categorie(){
        return $this->belongsTo('\coolracing\models\Categorie', 'idCategorie')->first();
    }
}