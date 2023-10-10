@extends('admin.layout.master')
@section('content')
<div class="main-wrapper">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="content container-fluid">		
                <!-- Page Header -->

                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="page-title">{{ __('pages.add_maintenance') }}</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:(0);">{{ __('pages.maintenances') }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->        
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body custom-edit-service">                 
                                <!-- Add Blog -->
                                <form method="post" enctype="multipart/form-data" action="{{ route('maintenance.modify') }}" class="ajax-form" swalOnSuccess="{{ __('pages.sucessdata') }}" title="{{ __('pages.opps') }}" swalOnFail="{{ __('pages.wrongdata') }}" redirect="{{ route('maintenance') }}">
                                    @csrf
                                    <div class="service-fields mb-3">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="mb-2">{{ __('pages.name') }}</label>
                                                    <input class="form-control" type="text" name="name" placeholder="{{ __('pages.name') }}" value="@isset($maintenance->id){{$maintenance->name}}@endisset">
                                                    <p class="error error_name"></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="mb-2">{{ __('pages.maintaince_cost') }}</label>
                                                    <input class="form-control text-start" type="text" name="cost" value="@isset($maintenance->id){{$maintenance->cost}}@endisset" placeholder="{{ __('pages.maintaince_cost') }}" >
                                                    <p class="error error_cost"></p>
                                                </div>
                                                <div class="col-md-6 mt-3">
                                                    <label class="mb-2">{{ __('pages.maintenance_invoice_date') }}</label>
                                                    <input class="form-control" type="date" name="invoice_date" placeholder="{{ __('pages.maintenance_invoice_date') }}" value="{{date('Y-m-d')}}">
                                                    <p class="error error_date"></p>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="mb-2">ملاحظات</label>
                                                    <textarea class="form-control" type="text" name="note" placeholder="{{ __('pages.enter_note') }}" value="" placeholder="{{ __('pages.enter_your_notes') }}">@isset($maintenance->id){{$maintenance->note}}@endisset</textarea>
                                                    <p class="error error_note"></p>
                                                </div>
                                            </div>
                               
                                            <input type="hidden" value="{{Request::segment(4)}}" name="user_id">
                                            <input type="hidden" value="{{Request::segment(5)}}" name="compound_id">
                                            <input type="hidden" value="{{Request::segment(6)}}" name="building_id">
                                            <input type="hidden" Request::segment(7) != 0 ? value="{{Request::segment(7)}}" : value="" name="apartment_id">
                                        </div>
                                    </div>
                                    @isset($maintenance->id)
                                        <input type="hidden" value="{{$maintenance->id}}" name="id">
                                    @endisset
                                    <div class="submit-section">
                                        <button class="btn btn-primary submit-btn" type="submit" name="form_submit" placeholder="submit">{{ __('pages.submit') }}</button>
                                    </div>
                                </form>
                                <!-- /Add Blog -->
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
<script>
    $('.dropify').dropify();

    $("#building_id").on("select2:select", function (evt) {
        $('#apartment').removeClass('d-none');
    });

    $(document).ready(function(){
        function route(){
            return $(this).attr('route');
        }

        function placeholder(){
            return $(this).attr('placeholder');
        }
        
        $("#compound_id").select2({
            ajax: {
                url: route,
                type: "post",
                dataType: 'json',
                delay: 250,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: function (term) {
                    return {
                        term: term,
                        user_id: $("#user_id").val()
                    };
                },
                processResults: function (response) {
                    return {
                        results: $.map(response, function(item) {
                            return {
                                text: item.name ,
                                id: item.id,
                            }
                        })
                    }
                },
                cache: true,
                templateResult: formatRepo,
                placeholder: placeholder,
            },
        });
        
        $("#building_id").select2({
            ajax: {
                url: route,
                type: "post",
                dataType: 'json',
                delay: 250,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: function (term) {
                    return {
                        term: term,
                        compound_id: $("#compound_id").val()
                    };
                },
                processResults: function (response) {
                    return {
                        results: $.map(response, function(item) {
                            return {
                                text: item.name ,
                                id: item.id,
                            }
                        })
                    }
                },
                cache: true,
                templateResult: formatRepo,
                placeholder: placeholder,
            },
        });
        
        $("#apartment_id").select2({
            ajax: {
                url: route,
                type: "post",
                dataType: 'json',
                delay: 250,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: function (term) {
                    return {
                        term: term,
                        building_id: $("#building_id").val()
                    };
                },
                processResults: function (response) {
                    return {
                        results: $.map(response, function(item) {
                            return {
                                text: item.name ,
                                id: item.id,
                            }
                        })
                    }
                },
                cache: true,
                templateResult: formatRepo,
                placeholder: placeholder,
            },
        });

        function formatRepo (item) {
            return item.name;
        }
    });
</script>
@endsection