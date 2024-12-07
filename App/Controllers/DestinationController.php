<?php
namespace App\Controllers;

use App\Controllers\Controller;

class DestinationController extends Controller
{
    protected $role;
    public function __construct()
    {
        parent::__construct();


    }
    public function index()
    {
        $destenations = $this->queryBuilder->table('destinations')->getAll()->execute();
        return $this->sendResponse(data: $destenations, message: "مقاصد با موفقیت دریافت شد", error: false);
    }
    public function store($request)
    {
        $this->validate([
            'title||required|min:3|max:50',
            'weather_id||required|number',

        ], $request);
        $weather = $this->queryBuilder->table('weather')->where($request->weather_id)->get()->execute();
        if (!$weather) {
            return $this->sendResponse(data: false, message: "آب و هوای وارد شده نامعتبر است", error: true, status: HTTP_BadREQUEST);
        }
        $newDestination = $this->queryBuilder->table('destinations')->insert([
            'title' => $request->title,
            'weather_id' => $request->weather_id,
            'created_at' => time(),
            'updated_at' => time(),
        ])->execute();
        return $this->sendResponse(data: $newDestination, message: "مقصد جدید باموفقیت افزورده شد");
    }
    public function get ($id){
        $destinations = $this->queryBuilder->table('destinations')->where(value: $id)->get()->execute();
       
        if(!$destinations)
        return $this->sendResponse(data: $destinations, message: "مقصد مورد نظر یافت نشد",error:true,status:HTTP_NotFOUND);

        return $this->sendResponse(data: $destinations, message: "مقصد با موفقیت دریافت شد");


    }
    public function update ($id,$request ){
        $this->validate([
            'title||required|min:3|max:50',
            'weather_id||required|number',


    ],$request);
    $weather = $this->queryBuilder->table('weather')->where($request->weather_id)->get()->execute();
    if (!$weather) {
        return $this->sendResponse(data: false, message: "آب و هوای وارد شده نامعتبر است", error: true, status: HTTP_BadREQUEST);
    }
    $UpdatedDestination = $this->queryBuilder->table('destinations')->update([
            'title' => $request->title,
        "weather_id"=>$request->weather_id,
            'updated_at' => time(),
    ])->where($id)->execute();
    return $this->sendResponse(data: $UpdatedDestination, message: "مقصد  باموفقیت ویرایش شد");

    }
    public function destroy( $id){
       
    $DeletedDestination = $this->queryBuilder->table('destinations')->update([
            'deleted_at' => time(),
       
    ])->where($id)->execute();
    return $this->sendResponse(data: $DeletedDestination, message: "مقصد  باموفقیت حذف شد");

    }
}
