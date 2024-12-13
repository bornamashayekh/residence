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
    public function index()
    {
// SELECT rooms.* , GROUP_CONCAT(features.title) as features FROM rooms
// LEFT JOIN room_feature on rooms.id = room_feature.room_id
// LEFT JOIN features on room_feature.feature_id = features.id
// GROUP BY rooms.id
        $rooms = $this->queryBuilder->table('rooms')->select([' rooms.* ', 'GROUP_CONCAT(features.title) as features', 'destinations.title as destination', 'weather.title as weather'])->join('room_feature', 'rooms.id', "=", 'room_feature.room_id', 'LEFT')->join("features", " room_feature.feature_id", "=", "features.id", "LEFT")->groupBy('rooms.id')->join('destinations', 'rooms.destination_id', "=", 'destinations.id', 'LEFT')->join("weather", " destinations.weather_id", "=", "weather.id", "LEFT")->getAll()->execute();
        return $this->sendresponse(message: "لیست اتاق ها با موفقیت دریافت شد", data: $rooms);
    }
    public function get($id)
    {

        $room = $this->queryBuilder->table('rooms')->select([' rooms.* ', 'GROUP_CONCAT(features.title) as features', 'destinations.title as destination', 'weather.title as weather'])->join('room_feature', 'rooms.id', "=", 'room_feature.room_id', 'LEFT')->join("features", " room_feature.feature_id", "=", "features.id", "LEFT")->groupBy('rooms.id')->join('destinations', 'rooms.destination_id', "=", 'destinations.id', 'LEFT')->join("weather", " destinations.weather_id", "=", "weather.id", "LEFT")->where(column: 'rooms.id', value: $id)->get()->execute();
        $room->features = explode(",", $room->features);
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
    public function room_like($request){
        $this->validate([
            'room_id||number'
        ], $request);
        $room = $this->queryBuilder->table('rooms')->where($request->room_id)->get()->execute();
        $get_like = $this->queryBuilder->table('room_like')->where($request->room_id , "room_id")->where($request->userDetail->id , 'user_id')->get()->execute();
        if (!$room)
            return $this->sendResponse(status: HTTP_BadREQUEST, error: true, message: "اتاق مورد نظر پیدا نشد");
        if($get_like){
            $deleted_like = $this->queryBuilder->table('room_like')->delete()->where($get_like->id)->execute();
                return $this->sendResponse(data: $deleted_like, message: " لایک پست مورد نظر با موفقیت برداشته شد ");
            }
            $room_like = $this->queryBuilder->table('room_like')->insert([
                'room_id' => $request->room_id,
                'user_id' => $request->userDetail->id,
                'created_at' => time()
                ])->execute();
                return $this->sendResponse(data: $room_like, message: "پست مورد نظر با موفقیت لایک شد");
    }
}
