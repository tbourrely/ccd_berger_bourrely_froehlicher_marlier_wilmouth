<?php
/**
 * Created by PhpStorm.
 * User: Pierre
 * Date: 09/02/2017
 * Time: 15:34
 */

namespace charly\models;


use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $table = 'invitation';
    protected $primaryKey = 'id';
    protected $fillable = ['idUser', 'idGroupe', 'url', 'status'];
    public $timestamps = false;

    public function user(){
        return $this->belongsTo('\charly\models\User','idUser');
    }

    public function groupe(){
        return $this->belongsTo('\charly\models\Groupe','idGroupe');

    }

}

