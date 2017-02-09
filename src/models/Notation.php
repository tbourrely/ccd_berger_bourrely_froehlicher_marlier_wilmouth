<?php
/**
 * Created by PhpStorm.
 * User: Axel
 * Date: 09/02/2017
 * Time: 18:35
 */

namespace charly\models;


use Illuminate\Database\Eloquent\Model;

class Notation extends Model
{
    protected $table = 'notation';
    protected $primaryKey = 'id';
    public $timestamps = false;
}