<?php

namespace charly\models;

/**
 * Created by PhpStorm.
 * User: Axel
 * Date: 09/02/2017
 * Time: 10:03
 */

use Illuminate\Database\Eloquent\Model;

class user extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id';
    protected $fillable = array('id', 'nom', 'email', 'password', 'message', 'age');
    public $timestamps = false;
}