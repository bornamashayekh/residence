<?php
namespace App\Controllers;

use App\Controllers\Controller;

class WeatherController extends Controller
{

    private $roles;
    public function __construct()
    {
        parent::__construct();

        $this->roles = ['admin', 'support'];

        $data = getPostDataInput();
        $this->Access->checkAccess($data->userDetail->role, $this->roles);

    }
    public function index()
    {
        $weathers = $this->queryBuilder->table('weather')->getAll()->execute();
        return $this->sendResponse(data: $weathers, message: "آب و هوا با موفقیت دریافت شد");
    }
    public function store($request)
    {
        dd("access ok");
    }
}
