<?php
/**
 * Created by PhpStorm.
 * User: Pierre
 * Date: 09/02/2017
 * Time: 12:42
 */

namespace charly\models;

use Illuminate\Database\Eloquent\Model;
class Groupe extends Model
{

    protected $table = 'groupe';
    protected $primaryKey = 'id';
    public $timestamps = false;



}