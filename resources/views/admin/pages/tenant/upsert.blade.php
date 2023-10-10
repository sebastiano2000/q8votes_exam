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
                            <h3 class="page-title">{{ __('pages.add_tenant') }}</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:(0);">{{ __('pages.tenants') }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body custom-edit-service">                 
                                <form method="post" enctype="multipart/form-data" action="{{ route('tenant.modify') }}" class="ajax-form" swalOnSuccess="{{ __('pages.sucessdata') }}" title="{{ __('pages.opps') }}" swalOnFail="{{ __('pages.wrongdata') }}" redirect="{{ route('tenant') }}">
                                    @csrf
                                    <div class="service-fields mb-3">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 mb-2">
                                                    <label class="mb-2">{{ __('pages.tenant') }}</label>
                                                    <select class="form-control select2 d-flex" style="height: 100px !important;" placeholder="{{ __('pages.tenant') }}" route="{{route('usersTenant')}}" name="tenant_id">
                                                        @if($tenant->tenant)
                                                            <option class="form-control" selected value="{{$tenant->tenant->id}}">{{ $tenant->tenant->name }}</option>
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="mb-2">{{ __('pages.compound_owner') }}</label>
                                                    <select class="form-control select2 d-flex" id="user_id" placeholder="{{ __('pages.compound_owner') }}" route="{{ route('users') }}"  name="user_id">
                                                        @if($tenant->user)
                                                            <option class="form-control" value="{{ $tenant->user->id }}" selected>{{ $tenant->user->name }}</option>
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="mb-2">{{ __('pages.compound') }}</label>
                                                    <select class="form-control d-flex" id="compound_id" placeholder="{{ __('pages.compound_owner') }}" route="{{route('compounds')}}" name="compound_id">
                                                        @if($tenant->building)
                                                            <option class="form-control" selected value="{{$tenant->building->compounds->first()->id}}">{{ $tenant->building->compounds->first()->name }}</option>
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label class="mb-2">{{ __('pages.building') }}</label>
                                                    <select class="form-control select2 d-flex" id="building_id" placeholder="{{ __('pages.building') }}" route="{{route('buildings')}}" name="building_id">
                                                        @if($tenant->building_id)
                                                            <option class="form-control" selected value="{{$tenant->building->id}}">{{ $tenant->building->name }}</option>
                                                        @endif
                                                    </select>
                                                </div>
                                                <div id="apartment" class="col-md-6">
                                                    <label class="mb-2">{{ __('pages.apartment') }}</label>
                                                    <select class="form-control d-flex" id="apartment_id" route="{{ route('maintenance.building') }}" placeholder="{{ __('pages.apartment') }}" name="apartment_id">
                                                        @if($tenant->apartment_id)
                                                            <option class="form-control" selected value="{{$tenant->apartment->id}}">{{ $tenant->apartment->name }}</option>
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="mb-2">{{ __('pages.cost') }}</label>
                                                    <input class="form-control text-start" type="text" name="price" value="@isset($tenant->id){{$tenant->price}}@endisset" placeholder="{{ __('pages.cost') }}" >
                                                    <p class="error error_price"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @isset($tenant->id)
                                        <input type="hidden" value="{{$tenant->id}}" name="id">
                                    @endisset
                                    <div class="submit-section">
                                        <button class="btn btn-primary submit-btn" type="submit" name="form_submit" placeholder="submit">{{ __('pages.submit') }}</button>
                                    </div>
                                </form>
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