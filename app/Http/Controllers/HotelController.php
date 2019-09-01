<?php

namespace App\Http\Controllers;
use App\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class HotelController extends Controller
{
    public function show($username){
        $user = Hotel::where('username',$username)->first();
        return view('profile')->with(['hotel'=>$user]);
    }

    public function showAuth(Request $request){
        $user = Hotel::find(Auth::id());
        switch ($request->route()->getName()){
            case 'settings':
                $countries = json_decode(file_get_contents("http://country.io/names.json"),true);
                sort($countries);
                return view('settings.updateAbout')->with(['hotel'=>$user,'countries'=>$countries]);
                break;
            case 'hotel_room':
                return view('settings.updateRooms')->with(['rooms'=>$user->rooms]);
                break;
            case 'hotel_image':
                return view('settings.updateRooms')->with(['images'=>$user->images]);
                break;
            case 'passwordChange':
                return view('settings.updatePassword');
                break;

        }

    }

    public function update(Request $request)
    {
        if (Auth::check()) {
            $id = Auth::id();

            $name = $request->input('name');
            $username = $request->input('username');
            $email = $request->input('email');
            $password = make::hash($request->input('password'));
            $country = $request->input('country');
            $city = $request->input('city');
            $district = $request->input('district');
            $telephone = $request->input('telephone');
            $room_id = $request->input('room_id');
            $price = $request->input('price');
            $number = $request->input('number');
            $user=array('name'=>$name,"username"=>$username,"email"=>$email,"password"=>$password,"country"=>$country,
                "city"=>$city,"district"=>$district,"telephone"=>$telephone,"room_id"=>$room_id,"price"=>$price,"number"=>$number);
            //$user = $request->all();
            $user['username'] = Auth::user();
            $user->update();
        } else {
            return response('Forbidden', 403);
        }
    }
    //delete hotels data
    public function delete(Request $request){
        if (Auth::check()) {
            $user['username'] = Auth::user();
            $user->delete();
        }
        else{
            return response('Forbidden', 403);
        }
    }
    //delete hotels_room
    public function deleteroom(Request $request){
        if (Auth::check()) {
            $room = $request->all();
            DB::delete('delete from hotel_room where id = ?',[$room['id']]);
        }
        else{
            return response('Forbidden', 403);
        }
    }
}