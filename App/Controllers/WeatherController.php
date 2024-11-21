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
                $this->validate([
                        'title||required|min:3|max:50',

                ],$request);
                $newWeather = $this->queryBuilder->table('wheaters')->insert([
                        'title' => $request->title,
                        'created_at' => time(),
                        'updated_at' => time(),
                ]);
                return $this->sendResponse(data: $newWeather, message: "اب و هوا ".$request->title."باموفقیت افزورده شد");
    }
}
