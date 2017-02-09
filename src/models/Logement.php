<?php

namespace charly\models;

/**
 * Created by PhpStorm.
 * User: Axel
 * Date: 09/02/2017
 * Time: 10:03
 */

use Illuminate\Database\Eloquent\Model;

class Logement extends Model
{
    protected $table = 'logement';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function groupes() {
        return $this->hasMany('\charly\models\Groupe', 'idLogement');

    public function notations(){
        return $this->hasMany('charly\models\Notation','idLogement');
    }

}