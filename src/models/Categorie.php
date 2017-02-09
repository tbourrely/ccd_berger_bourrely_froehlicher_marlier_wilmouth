<?php

namespace coolracing\models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{

    protected $table = 'categorie';
    protected $primaryKey = 'idCategorie';
    protected $fillable = array('idCategorie','nom','desc');
    public $timestamps = false;

    public function epreuves(){
        return $this->hasMany('\coolracing\models\Epreuve', 'idCategorie');
    }
}