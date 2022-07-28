@extends('layouts.layout_main')

@section('title', 'Daftar Ruangan')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card mb-4 p-4">
<center>
                    <h4>Daftar Ruangan</h4>
                    <a href="{{ asset('pdf/gbruang.pdf') }}"> 
                    <button type="submit" class="btn btn-success">Download Daftar Ruangan</button>
                </a>
                <img src = "{{asset ('images/gbruang.png')}}" width="100%" >
</center>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
