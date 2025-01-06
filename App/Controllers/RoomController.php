<?php
namespace App\Controllers;

use App\Controllers\Controller;

class RoomController extends Controller
{
    protected $roles;
    public function __construct()
    {
        parent::__construct();

    }
    public function index($request)
    {
        // dd($request);
        $rooms = $this->queryBuilder->table('rooms')->select([' rooms.* ', 'GROUP_CONCAT(features.title) as features', 'destinations.title as destination', 'weather.title as weather', 'IF(room_like.id is NULL, 0 ,1) as liked'])
        ->join('room_feature', 'rooms.id', "=", 'room_feature.room_id', 'LEFT')
        ->join("features", " room_feature.feature_id", "=", "features.id", "LEFT")
        ->join('destinations', 'rooms.destination_id', "=", 'destinations.id', 'LEFT')
        ->join("weather", " destinations.weather_id", "=", "weather.id", "LEFT")
        ->join("room_like", " room_like.room_id", "=", "rooms.id", "LEFT" , ["room_like.user_id",$request->userDetail->id ?? 0])
        ->groupBy('rooms.id , room_like.id')
        ->getAll()->execute();
        return $this->sendresponse(message: "لیست اتاق ها با موفقیت دریافت شد", data: $rooms);
    }
    public function get($id,$request)
    {
        // dd($request);

        $room = $this->queryBuilder->table('rooms')->select([' rooms.* ', 'GROUP_CONCAT(features.title) as features', 'destinations.title as destination', 'weather.title as weather' , 'IF(room_like.id is NULL, 0 ,1) as liked'])
        ->join('room_feature', 'rooms.id', "=", 'room_feature.room_id', 'LEFT')
        ->join("features", " room_feature.feature_id", "=", "features.id", "LEFT")
        ->join('destinations', 'rooms.destination_id', "=", 'destinations.id', 'LEFT')
        ->join("weather", " destinations.weather_id", "=", "weather.id", "LEFT")
         ->join("room_like", " room_like.room_id", "=", "rooms.id", "LEFT" , ["room_like.user_id",$request->userDetail->id ?? 0])
         ->groupBy('rooms.id , room_like.id')
         ->where(column: 'rooms.id', value: $id)
        ->get()->execute();
        dd($room);
        if(isset($room->features)) $room->features = explode(",", $room->features);
        if ($room) {
            return $this->sendresponse(message: "اتاق مورد نظر با موفقیت دریافت شد", data: $room);
        } else {
            return $this->sendresponse(message: "اتاق مورد نظر یافت نشد", error: true, status: HTTP_BadREQUEST);
        }
    }

    public function store($request)
    {
        $this->validate([
            "host_id||number",
            "destination_id||number",
            "title||required|min:5|string",
            "room_detail||string",
            "capacity||number",
            "addition_capacity||number",

        ], $request);
        if ($request->userDetail->role == "host") {
            $request->host_id = $request->userDetail->id;
        }
        $getHost = $this->queryBuilder->table("users")->where($request->host_id, 'id')->where("host", "role")->get()->execute();
        if ($getHost) {
            return $this->sendResponse(data: $getHost, message: "میزبان مورد نظر یافت نشد", error: true, status: HTTP_NotFOUND);
        }

        $new_room = $this->queryBuilder->table('rooms')->insert([
            "host_id" => $request->host_id,
            "destination_id" => $request->destination_id,
            "title" => $request->title,
            "room_detail" => $request->room_detail,
            "capacity" => $request->capacity,
            "addition_capacity" => $request->addition_capacity,
            "created_at" => time(),
            "updated_at" => time(),
        ])->execute();
        return $this->sendResponse(data: $new_room, message: "اتاق مورد نظر با موفقیت اضافه شد");
    }
    public function update($id, $request)
    {
        $this->validate([

            "title||required|min:5|string",
            "room_detail||string",
            "capacity||number",
            "addition_capacity||number",

        ], $request);
        $new_room = $this->queryBuilder->table('rooms')->update([

            "title" => $request->title,
            "room_detail" => $request->room_detail,
            "capacity" => $request->capacity,
            "addition_capacity" => $request->addition_capacity,

            "updated_at" => time(),
        ])->where(value: $id)->execute();
        return $this->sendResponse(data: $new_room, message: "اتاق مورد نظر با موفقیت ویرایش شد");

    }
    public function destroy($id)
    {
        $deleted_room = $this->queryBuilder->table('rooms')->update([
            'deleted_at' => time(),
        ])->where(value: $id)->execute();
        if ($deleted_room) {
            return $this->sendResponse(data: $deleted_room, message: "اتاق مورد نظر با موفقیت حذف شد");
        } else {
            return $this->sendResponse(error: true, status: HTTP_BadREQUEST, message: " اتاق مورد نظر یافت نشد");
        }

    }
    public function append_feature($request)
    {
        $this->validate([
            "room_id||required|number",
            "feature_id||required|number",
        ], $request);
        // dd("ok");
        $getRoom = $this->queryBuilder->table("rooms")->where($request->room_id, 'id')->get()->execute();
        if (!$getRoom) {
            return $this->sendResponse(data: $getRoom, message: "اتاق مورد نظر یافت نشد", error: true, status: HTTP_NotFOUND);
        }
        $checkRoom = $this->queryBuilder->table("features")->where($request->feature_id, 'id')->get()->execute();
        if (!$checkRoom) {
            return $this->sendResponse(data: $checkRoom, message: "ویژگی مورد نظر یافت نشد", error: true, status: HTTP_NotFOUND);
        }
        $append_feature = $this->queryBuilder->table('room_feature')->insert([
            "room_id" => $request->room_id,
            "feature_id" => $request->feature_id,
            "created_at" => time(),

        ])->execute();
        return $this->sendResponse(data: $append_feature, message: "ویژگی مورد نظر با موفقیت به اتاق شما اضافه شد");

    }
    public function add_feature($request)
    {
        $this->validate([
            "title||required|string",

        ], $request);
        $add_feature = $this->queryBuilder->table('features')->insert([
            "title" => $request->title,
            "created_at" => time(),
            "updated_at" => time(),
        ])->execute();
        if ($add_feature) {
            return $this->sendResponse(data: $add_feature, message: "ویژگی مورد نظر با موفقیت  اضافه شد");
        } else {
            return $this->sendResponse(data: [], message: "ویژگی مورد نظر اضافه نشد", error: true, status: HTTP_NotFOUND);

        }

    }
    public function room_like($request)
    {
        $this->validate([
            'room_id||number',
        ], $request);
        $room = $this->queryBuilder->table('rooms')->where($request->room_id)->get()->execute();
        $get_like = $this->queryBuilder->table('room_like')->where($request->room_id, "room_id")->where($request->userDetail->id, 'user_id')->get()->execute();
        if (!$room) {
            return $this->sendResponse(status: HTTP_BadREQUEST, error: true, message: "اتاق مورد نظر پیدا نشد");
        }

        if ($get_like) {
            $deleted_like = $this->queryBuilder->table('room_like')->delete()->where($get_like->id)->execute();
            return $this->sendResponse(data: $deleted_like, message: " لایک پست مورد نظر با موفقیت برداشته شد ");
        }
        $room_like = $this->queryBuilder->table('room_like')->insert([
            'room_id' => $request->room_id,
            'user_id' => $request->userDetail->id,
            'created_at' => time(),
        ])->execute();
        return $this->sendResponse(data: $room_like, message: "پست مورد نظر با موفقیت لایک شد");
    }
    public function room_reserve($request){
       
       
     
        $this->validate([
            'room_id||required|number',
            'user_id||number',
            'entry_date||required|string',
            'exit_date||required|string',
            'status||enum:pending,payed,reject,cancel',
        ],$request);
         // getting date from user entry 
        // gdate stands for gregorain date
        
        $entry_date = explode("/",$request->entry_date);
        $entry_year = $entry_date[0];
        $entry_month = $entry_date[1];
        $entry_day = $entry_date[2];
        // convering to gregorain and time stamp
        $entry_timestamp = jmktime('14','00','00',$entry_month,$entry_day,$entry_year);
        $request->entry_timestamp = $entry_timestamp;
        
        // getting date from user exit 
        // gdate stands for gregorain date
        $exit_date = explode("/",$request->exit_date);
        $exit_year = $exit_date[0];
        $exit_month = $exit_date[1];
        $exit_day = $exit_date[2];
        // convering to gregorain and time stamp
        $exit_timestamp = jmktime('12','00','00',$exit_month,$exit_day,$exit_year);
        $request->exit_timestamp = $exit_timestamp;
       
        // checking room and user by $id
        $getRoom = $this->queryBuilder->table("rooms")->where($request->room_id, 'id')->get()->execute();
        if (!$getRoom) {
            return $this->sendResponse(data: $getRoom, message: "اتاق مورد نظر یافت نشد", error: true, status: HTTP_NotFOUND);
        }
        $checkUser = $this->queryBuilder->table("users")->where($request->user_id, 'id')->get()->execute();
        if (!$checkUser) {
            return $this->sendResponse(data: $checkUser, message: "کاربر مورد نظر یافت نشد", error: true, status: HTTP_NotFOUND);
        }
        if ($request->userDetail->role == 'geust' || $request->userDetail->role == 'host')
            $request->user_id = $request->userDetail->id;
        $reserved_room = $this->queryBuilder->table('reserves')->insert([
            'room_id' => $request->room_id,
            'user_id' =>  $request->user_id,
            'entry_date' => $request->entry_date,
            'entry_timestamp' => $request->entry_timestamp,
            'exit_timestamp' => $request->exit_timestamp,
            'exit_date' => $request->exit_date,
            'status' => $request->status ?? 'pending',
            'created_at' => time(),
            'updated_at' => time(),

        ])->execute();
        return $this->sendResponse(data: $reserved_room, message: "اتاق مورد نظر با موفقیت رزرو شد");

    }
}
