<?php
namespace App\Controllers;

use App\Controllers\Controller;

class WeatherController extends Controller
{

    private $roles;
    public function __construct()
    {
        parent::__construct();

 
    }
    public function index()
    {
        // dd("test");
        $weathers = $this->queryBuilder->table('weather')->getAll()->execute();
        return $this->sendResponse(data: $weathers, message: "آب و هوا  ها با موفقیت دریافت شدند");
    }
    public function store($request)
    {
                $this->validate([
                        'title||required|min:3|max:50',

                ],$request);
                $newWeather = $this->queryBuilder->table('weather')->insert([
                        'title' => $request->title,
                        'created_at' => time(),
                        'updated_at' => time(),
                ])->execute();
                return $this->sendResponse(data: $newWeather, message: "اب و هوا ".translate_key($request->title)." باموفقیت افزورده شد");
    }
    public function get ($id){
        $weathers = $this->queryBuilder->table('weather')->where(value: $id)->get()->execute();
        if(!$weathers)
        return $this->sendResponse(data: $weathers, message: "آب و هوا مورد نظر یافت نشد",error:true,status:HTTP_NotFOUND);

        return $this->sendResponse(data: $weathers, message: "آب و هوا با موفقیت دریافت شد");


    }
    public function update ($id,$request ){
        $this->validate([
            'title||required|min:3|max:50',

    ],$request);
    $UpdatedWeather = $this->queryBuilder->table('weather')->update([
            'title' => $request->title,
         
            'updated_at' => time(),
    ])->where($id)->execute();
    return $this->sendResponse(data: $UpdatedWeather, message: "اب و هوا  باموفقیت ویرایش شد");

    }
    public function destroy( $id){
       
    $DeletedWeather = $this->queryBuilder->table('weather')->update([
            'deleted_at' => time(),
       
    ])->where($id)->execute();
    return $this->sendResponse(data: $DeletedWeather, message: "اب و هوا  باموفقیت حذف شد");

    }

}
