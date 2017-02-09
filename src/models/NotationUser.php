<?php
/**
 * Created by PhpStorm.
 * User: Axel
 * Date: 09/02/2017
 * Time: 20:20
 */

namespace charly\models;


use Illuminate\Database\Eloquent\Model;

class NotationUser extends Model
{
    protected $table = 'notationUser';
    protected $primaryKey = 'id';
    public $timestamps = false;
}