<?php 
namespace App\Controllers;
use App\Database\QueryBuilder;
use App\Traits\ResponseTrait;
use App\Middlewares\CheckAccessMiddleware;
use App\Validations\ValidateData;


class Controller{
    use ResponseTrait;
    use ValidateData;

    protected $Access ;
    protected $queryBuilder;
    public function __construct() {
        $this->queryBuilder =new QueryBuilder();
        $this->Access = new CheckAccessMiddleware();
    }
}