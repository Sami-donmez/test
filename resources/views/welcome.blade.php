@extends('layouts.auth')

@section('content')
    <div class="col-lg-12" style="box-shadow: 10px 10px  ">
        <div class="white_box mb_30" >
            <div class="row justify-content-center mt-3 ">
                <div class="col-6 ">
                    <div class="card box">
                        <a href="/login/bid"><img src="{{asset('img/company/3.png')}}" class="img-fluid"></a>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card box">
                    <a href="/login/lagaranta"><img src="{{asset('img/company/2.png')}}" class="img-fluid"></a>
                    </div>
                </div>

                <div class="col-6 mt-3">
                    <div class="card  px-2 box">
                        <a href="/login/nederhofje"><img src="{{asset('img/company/1.png')}}" class="img-fluid"></a>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
