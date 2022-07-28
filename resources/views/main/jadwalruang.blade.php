@extends('layouts.layout_main')

@section('title', 'Dashboard')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card mb-4 p-4">
<center>
                    <h4>Jadwal Ruangan</h4>
                    <a href="{{ asset('pdf/jadwaltk1.pdf') }}"> 
                    <button type="submit" class="btn btn-success">Download Jadwal Ruangan</button>
                </a>
                <img src = "{{asset ('images/jadwal tk1.jpg')}}" width="100%" >
</center>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
