<?php
/**
 * Created by PhpStorm.
 * User: Pierre
 * Date: 09/02/2017
 * Time: 10:32
 */

namespace charly\controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Validator;


class GroupeController extends BaseController
{
    public function interfaceCreationGroupe(RequestInterface $request, ResponseInterface $response, $args){
        $this->render($response, 'group\create');
    }
}