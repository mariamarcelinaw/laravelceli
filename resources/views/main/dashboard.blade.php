@extends('layouts.layout_main')

@section('title', 'Dashboard')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4" style="height: 500px">
                <div class="card-header pb-0">
                    <h6><marquee> Halo Selamat Datang {{ auth()->user()->email }}</marquee></h6>
                </div>
<center>
                <img src = "{{asset ('images/gblogin.png')}}" width="80%" >
 
</center>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

