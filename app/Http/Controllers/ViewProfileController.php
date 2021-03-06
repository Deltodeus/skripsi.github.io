<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Mentor;
use App\Mentee;
use App\Admin;
use App\User;

class ViewProfileController extends Controller
{
    public function show(){
        
        if(Auth::user()->role == 'admin'){
            $userData = DB::table('users')
            ->join('admins','users.id','=','admins.user_id')
            ->select('admins.name','admins.id','users.username','admins.birth_date','admins.gender','users.email','admins.phone','admins.birth_place','admins.address','admins.profile_picture')
            ->where('users.id','=',Auth::id())
            ->get();
        }
        if(Auth::user()->role == 'mentee'){
            $userData = DB::table('users')
            ->join('mentees','users.id','=','mentees.user_id')
            ->select('mentees.name','mentees.id','mentees.birth_date','mentees.gender','users.email','mentees.phone','mentees.birth_place','mentees.address','mentees.portofolio','mentees.cv','mentees.profile_picture','users.username')
            ->where('users.id','=',Auth::id())
            ->get();
        }
        if(Auth::user()->role == 'mentor'){
            $userData = DB::table('users')
            ->join('mentors','users.id','=','mentors.user_id')
            ->select('mentors.name','mentors.id','mentors.birth_date','mentors.gender','users.email','mentors.phone','mentors.birth_place','mentors.address','mentors.profile_picture','users.username')
            ->where('users.id','=',Auth::id())
            ->get();
        }
 
        $age = Carbon::parse($userData[0]->birth_date)->diff(Carbon::now())->y;
        
        $auth = Auth::check();
        return view('profile',compact('auth','userData','age'));
    }

    public function edit(Request $request, $id){
        $this->validate($request,[
            'name' => 'required|min:4',
            'address' => 'nullable|min:10',
            'phone' => 'nullable|min:12',
            'birth_date' => 'nullable',
            'birth_place' => 'nullable|min:4',
            'gender' => 'required',
            'age' => 'nullable',
            'email' => 'required|min:13',
            'cv' => 'nullable',
            'portofolio' => 'nullable',
            'profile_picture' => 'nullable|mimes:jpg,jpeg,bmp,png,svg'
        ]);
        
        if(Auth::user()->role == 'mentee'){
            $profileDetail = Mentee::find($id);
            $profileDetail->name = $request->name;
            $profileDetail->address = $request->address;
            $profileDetail->phone = $request->phone;
            $profileDetail->birth_date = $request->birth_date;
            $profileDetail->birth_place = $request->birth_place;
            $profileDetail->gender = $request->gender;
            $userEmail = User::find(auth()->user()->id);
            $userEmail ->email = $request->email;
            if ($request->file('profile_picture') == null) {
                
            }else{
                $image_path = $request->file('profile_picture')->store('storage','public');
                $profileDetail->profile_picture = $image_path;
            }

            if ($request->file('portofolio') == null) {
                
            }else{
                $image_path = $request->file('portofolio')->store('portofolio','public');
                $profileDetail->portofolio = $image_path;
            }

            if ($request->file('cv') == null) {
                
            }else{
                $image_path = $request->file('cv')->store('cv','public');
                $profileDetail->cv = $image_path;
            }
            $profileDetail->save();
            $userEmail->update();
            
        }
        if(Auth::user()->role == 'mentor'){
            $profileDetail = Mentor::find($id);
            $profileDetail->name = $request->name;
            $profileDetail->address = $request->address;
            $profileDetail->phone = $request->phone;
            $profileDetail->birth_date = $request->birth_date;
            $profileDetail->birth_place = $request->birth_place;
            $profileDetail->gender = $request->gender;
            $userEmail = User::find(auth()->user()->id);
            $userEmail ->email = $request->email;
            if ($request->file('profile_picture') == null) {
                
            }else{
                $image_path = $request->file('profile_picture')->store('storage','public');
                $profileDetail->profile_picture = $image_path;
            }
            $profileDetail->save();
            $userEmail->update();
        }

        if(Auth::user()->role == 'admin'){
            
            $profileDetail = Admin::find($id);
            $profileDetail->name = $request->name;
            $profileDetail->address = $request->address;
            $profileDetail->phone = $request->phone;
            $profileDetail->birth_date = $request->birth_date;
            $profileDetail->birth_place = $request->birth_place;
            $profileDetail->gender = $request->gender;
            $userEmail = User::find(auth()->user()->id);
            $userEmail->email = $request->email;
            if ($request->file('profile_picture') == null) {
                
            }else{
                $image_path = $request->file('profile_picture')->store('storage','public');
                $profileDetail->profile_picture = $image_path;
            }
            $profileDetail->save();
            $userEmail->update();
        }
        
        return redirect('/dashboard')->with('status','Profile Updated Successfully');
    }
}
