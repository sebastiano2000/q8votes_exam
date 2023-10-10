@extends('admin.layout.master')

@section('content')

     <div class="main-wrapper">
        <div class="page-wrapper" style="margin-top: 200px;">
            <div class="content container-fluid">
                <div class="page-header">
                    <div class="row">
                        <div class="row justify-content-center">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-6 mx-auto">
                                        <div class="payment">
                                            <div class="payment_header payment_header1 ">
                                                <div class="check"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></div>
                                            </div>
                                            <div class="content text-center">
                                                <h2 class="text-danger"> لقد حدث خطأ في عملية الدفع</h1>
                                                <h1 class="text-dark">يرجى المحاولة مرة أخرى </h2>
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
                        window.setTimeout(function () {
                            location.href = "{{ route('home') }}";
                        }, 3000);
                    }
                });
            }
        });
    </script>
@endsection