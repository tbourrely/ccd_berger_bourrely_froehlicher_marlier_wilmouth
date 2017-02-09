<?php
/**
 * Created by PhpStorm.
 * User: keiko
 * Date: 27/12/16
 * Time: 16:10
 */

namespace coolracing\models;
use \Illuminate\Database\Eloquent\Model;

class Compte extends Model
{
    protected $table = 'compte';
    protected $primaryKey = 'idCompte';
    protected $fillable = array('idCompte','nom','prenom','email','age');
    public $timestamps = false;

    public function comptes(){
        return Compte::all();
    }
    public function getById($id){
        return Compte::where('idCompte','=',$id)->first();
    }
    public function getByName($name){
        return Compte::where('nom','=',$name)->get();
    }
    public function getByPrenom($prenom){
        return Compte::where('prenom','=',$prenom)->first();
    }
    public function getByEmail($email){
        return Compte::where('email','=',$email)->first();
    }
    public function getByAge($age){
        return Compte::where('age','=',$age)->first();
    }
    public function evenements(){
        return $this->hasMany('\coolracing\models\Evenement','idCompte');
    }
}