<?php
/**
 * Created by PhpStorm.
 * User: Pierre
 * Date: 09/02/2017
 * Time: 12:55
 */

namespace charly\models;


use Illuminate\Database\Eloquent\Model;

class ContenuGroupe extends Model
{

    protected $table = 'contenu_groupe';
    protected $primaryKey = 'id';
    public $timestamps = false;

}