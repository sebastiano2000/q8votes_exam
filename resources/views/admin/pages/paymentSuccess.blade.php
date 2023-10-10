@extends('admin.layout.master')

@section('content')

<!-- /Breadcrumb -->

<!-- Page Content -->
<div class="main-wrapper">
    <div class="page-wrapper" style="margin-top: 200px;">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="row justify-content-center">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6 mx-auto ">
                                    <div class="payment">
                                        <div class="payment_header ">
                                            <div class="check"><i class="fa fa-check" aria-hidden="true"></i></div>
                                        </div>
                                        <div class="content text-center">
                                            {{-- use the auth to get the financai trasnaction --}}
                                            <h2 class="text-success">تم الدفع بنجاح </h2>
                                            <h1 class="text-dark thanks_div">

                                            </h1>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
        if(window.location.href.includes('?data=')){

            $.ajax({
                url: '{{ route("payment.save") }}',
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                },
                method: 'POST',
                data: {data: window.location.href.split('?data=')[1]},
                success: function (data) {
                    window.location.href = window.location.href.split('?data=')[0];
                }
            });
        }
        else{
            $('.thanks_div').html(`
               شكرا لسدادك إيجار شهر
               @if (Auth::user())
                @if(count(Auth::user()->payments) > 0)
                    ({{Auth::user()->payments()->latest()->first()->pay_monthes}})
                @endif
                    يرجي الدخول علي سجل العمليات لتحميل وصل الإيجار
               @endif
            `);
            setTimeout(function () {
                window.location.href = "{{ route('home') }}";
            }, 3000);
        }
    });
</script>
@endsection