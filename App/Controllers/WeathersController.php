<?php
namespace App\Controllers;

use App\Database\QueryBuilder;
use App\Middlewares\CheckAccessMiddleware;
use App\Traits\ResponseTrait;
use App\Validations\ValidateData;
class WeathersController{
    use ResponseTrait;
    protected $queryBuilder ;
    protected $Access ;
    private $roles;
public function __construct(){
        $this->roles = ['admin', 'support'];
    $this->queryBuilder = new QueryBuilder();
    $this->Access = new CheckAccessMiddleware();
    $data = getPostDataInput();
        $this->Access->checkAccess($data->userDetail->role,$this->roles);
      

}
public function index(){
        $weathers = $this->queryBuilder->table('weather')->getAll()->execute();
        return $this->sendResponse(data: $weathers, message: "آب و هوا با موفقیت دریافت شد");
}
public function store( $request){
        dd("access ok");
}
}