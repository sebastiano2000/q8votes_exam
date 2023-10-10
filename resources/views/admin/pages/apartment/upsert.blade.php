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
                            <h3 class="page-title">{{ __('pages.add_apartment') }}</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:(0);">{{ __('pages.apartments') }}</a></li>
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
                                <form method="post" enctype="multipart/form-data" action="{{ route('apartment.modify') }}" class="ajax-form" swalOnSuccess="{{ __('pages.sucessdata') }}" title="{{ __('pages.opps') }}" swalOnFail="{{ __('pages.wrongdata') }}" redirect="{{ route('apartment') }}">
                                    @csrf
                                    <div class="service-fields mb-3">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="mb-2">{{ __('pages.name') }}</label>
                                                    <input class="form-control" type="text" name="name" placeholder="{{ __('pages.name') }}" value="@isset($apartment->id){{$apartment->name}}@endisset">
                                                    <p class="error error_name"></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="mb-2">{{ __('pages.compound_owner') }}</label>
                                                    <select class="form-control select2 d-flex" id="user_id" placeholder="{{ __('pages.compound_owner') }}" route="{{ route('users') }}"  name="user_id">
                                                        @if($apartment->user)
                                                            <option class="form-control" value="{{ $apartment->user->id }}" selected>{{ $apartment->user->name }}</option>
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="mb-2">{{ __('pages.compound') }}</label>
                                                    <select class="form-control d-flex" id="compound_id" placeholder="{{ __('pages.compound_owner') }}" route="{{route('compounds')}}" name="compound_id">
                                                        @if($apartment->building)
                                                            <option class="form-control" selected value="{{$apartment->building->compounds->first()->id}}">{{ $apartment->building->compounds->first()->name }}</option>
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="mb-2">{{ __('pages.building') }}</label>
                                                    <select class="form-control d-flex" id="building_id" placeholder="{{ __('pages.buildings') }}" route="{{route('buildings')}}"  name="building_id">
                                                        @if($apartment->building)
                                                            <option class="form-control" selected value="{{$apartment->building->id}}">{{ $apartment->building->name }}</option>
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                   
                                        </div>
                                    </div>
                                    @isset($apartment->id)
                                        <input type="hidden" value="{{$apartment->id}}" name="id">
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

        function formatRepo (item) {
            return item.name;
        }
    });
</script>
@endsection