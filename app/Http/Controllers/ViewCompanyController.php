<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ViewCompanyController extends Controller
{
    public function getCompanyList(){
        if(Auth::user()->role == 'admin'){
            $userData = DB::table('users')
            ->join('admins','users.id','=','admins.user_id')
            ->select('users.username','admins.profile_picture')
            ->get();
        }
        $auth = Auth::check();
        $company = Company::paginate(3);
        return view('admin/view_company',compact('auth','company','userData'));
    }

    public function getProductbySearch(Request $request){ //buat nampilin hasil searching sesuai keyword yang diinput user (keyword akan dicocokkan dengan nama product)
        if(Auth::user()->role == 'admin'){
            $userData = DB::table('users')
            ->join('admins','users.id','=','admins.user_id')
            ->select('users.username','admins.profile_picture')
            ->get();
        }
        $auth = Auth::check();
        $company = Company::where('name','like',"%{$request->keyword}%")->paginate(3);
        //$company_picture = DB::table('companies')->select('profile_picture');
        return view('admin/view_company',compact('userData','company','auth'));
    }

    public function goEditPage($id){ //buat nampilin page EditProduct
        if(Auth::user()->role == 'admin'){
            $userData = DB::table('users')
            ->join('admins','users.id','=','admins.user_id')
            ->select('users.username','admins.profile_picture')
            ->get();
        }
        $auth = Auth::check();
        $companyDetail = Company::find($id);
        return view('admin/edit_company',compact('companyDetail','auth','userData'));
    }

    public function editCompanyDetail(Request $request, $id){ //berisi validasi inputan dan buat melakukan editProduct yang akan mengupdate semua data produk yang diklik sesuai inputan admin
        $this->validate($request,[
            'user_id' => 'required|integer|min:1',
            'name' => 'required|min:5',
            'address' => 'required',
            'phone' => 'required',
            'profile_picture' => 'required'
        ]);

        $companyDetail = Company::find($id);
        $companyDetail->user_id = $request->user_id;
        $companyDetail->name = $request->name;
        $companyDetail->address = $request->address;
        $companyDetail->phone = $request->phone;
        $companyDetail->profile_picture = $request->profile_picture;
        $companyDetail->update();
        return redirect('/dashboard')->with('status','Company Updated Successfully');
    }

    public function deleteCompany($id){ //buat menghapus product sesuai dengan product yang diklik
        $courseDetail = Company::find($id);
        $courseDetail->delete();

        return redirect('/dashboard')->with('status','Company Deleted Successfully');
    }

    public function getAddCompanyPage(){ //buat nampilin page AddProduct dan list semua stationary_type
        if(Auth::user()->role == 'admin'){
            $userData = DB::table('users')
            ->join('admins','users.id','=','admins.user_id')
            ->select('users.username','admins.profile_picture')
            ->get();
        }
        $auth = Auth::check();

        //function buat fitur class schedule
        // $today = new Carbon('2020-06-11'); // isi date today (Carbon::now())
        // $weekStartDate = $today->startOfWeek()->format('Y-m-d');
        // $date = Carbon::createFromDate($weekStartDate);
        // $daysToAdd = 7; // -> nanti lempar 0 - 5 (yg dimana dapat dari db) -> monday itu 0 -> sunday itu 6
        // // note : harusny datany cmn simpan day_of_week ( 0 - 5 -> monday - saturday )
        // $date = $date->addDays($daysToAdd)->format('Y-m-d');
        // dd($date);
        //

        return view('admin/add_company',compact('auth','userData'));
    }

    public function addCompany(Request $request){ //buat validasi inputan dan untuk menambahkan produk baru kedalam database sesuai inputan admin
        $auth = Auth::check();
        $this->validate($request,[
            'user_id' => 'required|integer|min:1',
            'name' => 'required|min:5',
            'address' => 'required',
            'phone' => 'required',
            'profile_picture' => 'required'
        ]);
        $company = new Company();
        $company->user_id = $request->user_id;
        $company->name = $request->name;
        $company->address = $request->address;
        $company->phone = $request->phone;
        $company->profile_picture = $request->profile_picture;

        $company->save();

        return redirect('/dashboard')->with('status','Company Added Successfully');
    }
}
