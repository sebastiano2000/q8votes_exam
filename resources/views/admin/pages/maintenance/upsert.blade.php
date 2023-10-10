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
                                                    <label class="mb-2">{{ __('pages.compound_owner') }}</label>
                                                    <select class="form-control select2 d-flex" id="user_id" placeholder="{{ __('pages.compound_owner') }}" route="{{ route('users') }}"  name="user_id" disabled>
                                                        @if($maintenance->user)
                                                            <option class="form-control" value="{{ $maintenance->user->id }}" selected>{{ $maintenance->user->name }}</option>
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="mb-2">{{ __('pages.compound') }}</label>
                                                    <select class="form-control d-flex" id="compound_id" placeholder="{{ __('pages.compound_owner') }}" route="{{route('compounds')}}" name="compound_id" disabled>
                                                        @if($maintenance->building)
                                                            <option class="form-control" selected value="{{$maintenance->building->compounds->first()->id}}">{{ $maintenance->building->compounds->first()->name }}</option>
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mt-3">
                                                    <label class="mb-2">{{ __('pages.building') }}</label>
                                                    <select class="form-control select2 d-flex" id="building_id" placeholder="{{ __('pages.building') }}" route="{{route('buildings')}}" name="building_id">
                                                        @if($maintenance->building_id)
                                                            <option class="form-control" selected value="{{$maintenance->building->id}}">{{ $maintenance->building->name}}</option>
                                                        @endif
                                                    </select>
                                                </div>
                                                <div id="apartment" class="col-md-6 mt-3 @if(!$maintenance->id) d-none @endif">
                                                    <label class="mb-2">{{ __('pages.apartment') }}</label>
                                                    <select class="form-control d-flex" id="apartment_id" route="{{ route('maintenance.building') }}" placeholder="{{ __('pages.apartment') }}" name="apartment_id">
                                                        @if($maintenance->apartment_id)
                                                            <option class="form-control" selected value="{{$maintenance->apartment->id}}">{{ $maintenance->apartment->name}}</option>
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mt-3">
                                                    <label class="mb-2">{{ __('pages.maintenance_invoice_date') }}</label>
                                                    <input class="form-control" type="date" name="invoice_date" placeholder="{{ __('pages.maintenance_invoice_date') }}" value="{{date('Y-m-d')}}">
                                                    <p class="error error_date"></p>
                                                </div>
                                            </div>
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
                                                <div class="col-md-12">
                                                    <label class="mb-2">ملاحظات</label>
                                                    <textarea class="form-control" type="text" name="note" placeholder="{{ __('pages.enter_note') }}" value="" placeholder="{{ __('pages.enter_your_notes') }}">@isset($maintenance->id){{$maintenance->note}}@endisset</textarea>
                                                    <p class="error error_note"></p>
                                                </div>
                                            </div>
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
                        // term: term,
                        // compound_id: $("#compound_id").val()
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