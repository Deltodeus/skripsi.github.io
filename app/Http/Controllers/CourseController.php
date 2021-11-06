<?php

namespace App\Http\Controllers;

use App\Course;
use App\Mentor;
use App\Module;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function getCourseList(){
        if(Auth::user()->role == 'admin'){
            $userData = DB::table('users')
            ->join('admins','users.id','=','admins.user_id')
            ->select('users.username','admins.profile_picture')
            ->get();
        }
        $auth = Auth::check();
        $course = Course::paginate(3);
        return view('admin/view_course',compact('auth','course','userData'));
    }

    public function getProductbySearch(Request $request){ //buat nampilin hasil searching sesuai keyword yang diinput user (keyword akan dicocokkan dengan nama product)
        if(Auth::user()->role == 'admin'){
            $userData = DB::table('users')
            ->join('admins','users.id','=','admins.user_id')
            ->select('users.username','admins.profile_picture')
            ->get();
        }
        $auth = Auth::check();
        $course = Course::where('name','like',"%{$request->keyword}%")->paginate(3);
        return view('admin/view_course',compact('userData','course','auth'));
    }

    public function goEditPage($id){ //buat nampilin page EditProduct 
        if(Auth::user()->role == 'admin'){
            $userData = DB::table('users')
            ->join('admins','users.id','=','admins.user_id')
            ->select('users.username','admins.profile_picture')
            ->get();
        }
        $auth = Auth::check();
        $courseDetail = Course::find($id);
        $mentorList = Mentor::all();
        $moduleList = Module::where('course_id','=',$id)->get();
        return view('admin/edit_course',compact('courseDetail','auth','userData','moduleList','mentorList'));
    }

    public function editCourseDetail(Request $request, $id){ //berisi validasi inputan dan buat melakukan editProduct yang akan mengupdate semua data produk yang diklik sesuai inputan admin
        $this->validate($request,[
            'name' => 'required|unique:courses|min:5',
            'category' => 'required|min:10',
            'description' => 'required|min:10',
            'price' => 'required|integer|min:5001',
            'weeks' => 'required|integer|min:1',
            'kkm' => 'required|integer|min:2'
        ]);
        
        $courseDetail = Course::find($id);
        $courseDetail->name = $request->name;
        $courseDetail->category = $request->category;
        $courseDetail->description = $request->description;
        $courseDetail->price = $request->price;
        $courseDetail->weeks = $request->weeks;
        $courseDetail->kkm = $request->kkm;
        $courseDetail->update();
        return redirect('/dashboard')->with('status','Course Updated Successfully');
    }

    public function deleteCourse($id){ //buat menghapus product sesuai dengan product yang diklik
        $courseDetail = Course::find($id);
        $courseDetail->delete();

        return redirect('/dashboard')->with('status','Course Deleted Successfully');
    }

    public function getAddCoursePage(){ //buat nampilin page AddProduct dan list semua stationary_type
        if(Auth::user()->role == 'admin'){
            $userData = DB::table('users')
            ->join('admins','users.id','=','admins.user_id')
            ->select('users.username','admins.profile_picture')
            ->get();
        }
        $auth = Auth::check();

        $mentorList = Mentor::all();
        return view('admin/add_course',compact('auth','userData','mentorList'));
    }

    public function addCourse(Request $request){ //buat validasi inputan dan untuk menambahkan produk baru kedalam database sesuai inputan admin
        $auth = Auth::check();
        $this->validate($request,[
            'name' => 'required|unique:courses|min:5',
            'category' => 'required',
            'mentor' => 'required',
            'description' => 'required|min:10',
            'price' => 'required|integer|min:5001',
            'weeks' => 'required|integer|min:1',
            'kkm' => 'required|integer|min:2'
        ]);
        $course = new Course();    
        $course->name = $request->name;
        $course->category = $request->category;
        $course->description = $request->description;
        $course->price = $request->price;
        $course->weeks = $request->weeks;
        $course->kkm = $request->kkm;

        $mentor_id = Mentor::select('id')->where('name',$request->mentor)->get();
        $course->mentor_id = $mentor_id[0]->id;
        $course->save();
        
        return redirect('/dashboard')->with('status','Course Added Successfully');
    }
}