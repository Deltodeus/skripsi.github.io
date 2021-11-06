@extends('layout.layout')
@section('content')
<div class="col-md-12">
    <div class="container-fluid">
        <div class="row flex-nowrap">
            @include('layout.sidebar')
            <main class="col ps-md-2 pt-2">
                <div class="col-md-12" style="display: flex; margin-top:10px">
                    <a href="#" data-bs-target="#sidebar" data-bs-toggle="collapse"
                        class="border rounded-3 p-1 text-decoration-none" style="display: table;"><i class="fa fa-bars"
                            aria-hidden="true"></i>
                        Menu</a>
                    <div class="col-md-10">
                        <center>
                            <h4 style="margin-top: 2px;text-align: center"><a class="title" href="{{'/'}}">Starlink</a>
                            </h4>
                        </center>
                    </div>
                </div>
                <div class="col-md-2" style="display: flex; position: absolute;right: 0;top: 0; padding-left: 64px">
                    <img src="{{url('storage/'.$userData[0]->profile_picture)}}"
                        style="height:60px; border-radius: 50%; border: 6px solid #218EED;margin-top:10px; width: 50px;">
                    <div class="dropdown" style="margin-top: 20px">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdown"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                            style="margin-left: 20px; color: black; background-color: white">{{$userData[0]->username}}</button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="/dashboard/logout">Logout</a>
                            <a class="dropdown-item" href="/home/logout">View Profile</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-12" id="background">
                    <div class="container-fluid" style="padding-top: 50px">
                        <form action="{{'/course/'}}">
                        <div style="display:flex;">
                            <h5 style="color: white; margin-left: 30px; margin-top:5px">Course</h5>
                            <input type="text" name="keyword" class="form-control" placeholder="Search for Product"
                                style="width: 500px; margin-left: 40px; padding-bottom: 5px; position: absolute; right: 140px;">
                            <input type="submit" class="btn btn-primary" value="Search"
                                style="margin-left: 10px; margin-bottom: 5px; position: absolute; right: 50px; background-color: #27353F">
                        </div>
                        </form>
                        <hr style="color: #FFFFFF;height: 3px">
                        <a href="{{'/addCourse/'}}">
                            <button class="btn btn-primary" style="background-color: #27353F; margin-left: 30px;">Create</button>
                        </a>
                        <div style="display: flex; margin-top :20px">
                            @foreach ($course as $item)
                            <div class="col-md-2 card">
                                <div style="margin-top: 20px">
                                    <h4>{{$item->name}}</h4>
                                    <p class="price">Rp.{{$item->price}}</p>
                                    <div style="height: 100px;">
                                        <p class="cardText" style="overflow: hidden">{{$item->description}}</p>
                                    </div>
                                    <div style="display: flex">
                                        <a href="{{'/editCourse/'.$item->id}}">
                                            <button class="btn btn-primary" style="margin-left: 30px; margin-top: 20px">Edit</button>
                                        </a>
                                        <form action="{{'/editCourse/delete/'.$item->id}}" method="post">
                                            {{csrf_field()}}
                                            {{method_field('post')}}
                                            <button class="btn btn-danger" onclick="return confirm('Are you sure to Delete this Course?')" style="margin-left: 30px;margin-top: 20px">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div style="margin-left: 30px; margin-top:20px">
                            {{$course->links()}}
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>
@endsection