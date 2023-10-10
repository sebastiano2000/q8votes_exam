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
                            <h3 class="page-title">{{ __('pages.add_building') }}</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:(0);">{{ __('pages.buildings') }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- /Page Header -->        
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class=" custom-edit-service">                 
                                <!-- Add Blog -->
                                <form method="post" enctype="multipart/form-data" action="{{ route('building.modify') }}" class="ajax-form" swalOnSuccess="{{ __('pages.sucessdata') }}" title="{{ __('pages.opps') }}" swalOnFail="{{ __('pages.wrongdata') }}" redirect="{{ route('building') }}">
                                    @csrf
                                    <div class="service-fields mb-3">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-2 m-3">
                                                    <a name="" class="add-new btn btn-primary d-block"> {{ __('pages.add_drug') }} +</a>
                                                </div>
                                                <div class="compound-container">
                                                    @if(count($building->compounds) > 0)
                                                        @foreach($building->compounds as $compound)
                                                            <div class="row compound_row record m-2">
                                                                <div class="col-md-5">
                                                                    <label class="mb-2">{{ __('pages.compound_owner') }}</label>
                                                                    <select class="form-control select2 d-flex" id="user_id" placeholder="{{ __('pages.compound_owner') }}" route="{{ route('users') }}" name="user_id[]">
                                                                        <option class="form-control" value="{{ $building->compounds[$loop->iteration - 1]->user->id }}" selected>{{ $building->compounds[$loop->iteration - 1]->user->name }}</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <label class="mb-2">{{ __('pages.compound') }}</label>
                                                                    <select class="form-control d-flex compound_id" id="compound_id" placeholder="{{ __('pages.compound_owner') }}" route="{{route('compounds')}}" name="compound_id[]">
                                                                        <option class="form-control" selected value="{{$building->compounds[$loop->iteration - 1]->id}}">{{ $building->compounds[$loop->iteration - 1]->name }}</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-2" style="padding-top: 26px; !important">
                                                                    <a class="btn btn-sm bg-danger-light btn_delete">
                                                                        <span class=" remove_record btn text-danger"><i class="ti-trash"></i>{{ __('pages.delete') }}</span>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <div class="compound_row row record m-2" index="${getUniqueIndex()}">
                                                            <div class="col-md-6">
                                                                <label class="mb-2">{{ __('pages.compound_owner') }}</label>
                                                                <select class="form-control d-flex select2" id="user_id" placeholder="{{ __('pages.compound_owner') }}" route="{{ route('users') }}" name="user_id[1]">
                                                                    <option class="form-control"></option>
                                                                </select>
                                                                <p class="error error_user_id_1"></p>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <label class="mb-2">{{ __('pages.compound') }}</label>
                                                                <select class="form-control d-flex compound_id" id="compound_id" placeholder="{{ __('pages.compound_owner') }}" route="{{route('compounds')}}" name="compound_id[1]">
                                                                    <option class="form-control"></option>
                                                                </select>
                                                                <p class="error error_compound_id_1"></p>
                                                            </div>
                                                            <div class="col-md-1" style="padding-top: 26px; !important">
                                                                <a class="btn btn-sm bg-danger-light btn_delete">
                                                                    <span class="remove_record btn text-danger"><i class="ti-trash"></i>{{ __('pages.delete') }}</span>
                                                                </a>                
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-12 me-3" style="padding-left: 90px !important;">
                                                    <label class="mb-2">اسم العقار</label>
                                                    <input class="form-control" type="text" name="name" placeholder="اسم العقار" value="@isset($building->id){{$building->name}}@endisset">
                                                    <p class="error error_name"></p>
                                                </div>
                                                <div class="apartment_compound-container">
                                                    
                                                </div>
                                                <div class="col-md-2 m-3">
                                                    <a name="apartment_" class="add-new-apartment btn btn-primary d-block">اضف وحدة في العقار +</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @isset($building->id)
                                        <input type="hidden" value="{{$building->id}}" name="id">
                                    @endisset
                                    <div class="submit-section mb-3">
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
    
    function getUniqueIndex(name, with_start) {
        let drugContainerLength = (with_start) ? with_start : (name) ? $('.apartment_compound-container').children().length : $('.compound-container').children().length;
        let index = drugContainerLength + 1;
        let count = $(`.${name}compound_row[index="${index}"]`).length;

        if ( count ) {
            return getUniqueIndex(name, index);
        }

        return index
    }

    $('.add-new').on('click',function(e){
        let name = $(this).attr("name");
        let childrens = $(`.compound-container`).children();
      
        $(`.compound-container`).append(`
            <div class="compound_row row record m-2" index="${getUniqueIndex()}">
                <div class="col-md-6">
                    <label class="mb-2">{{ __('pages.compound_owner') }}</label>
                    <select class="form-control d-flex select_owner" id="user_id" placeholder="{{ __('pages.compound_owner') }}" route="{{ route('users') }}" name="user_id[${getUniqueIndex(name)}]">
                        <option class="form-control"></option>
                    </select>
                    <p class="error error_user_id_${getUniqueIndex(name)}"></p>
                </div>
                <div class="col-md-5">
                    <label class="mb-2">{{ __('pages.compound') }}</label>
                    <select class="form-control d-flex select_compound" id="compound_id" placeholder="{{ __('pages.compound_owner') }}" route="{{route('compounds')}}" name="compound_id[${getUniqueIndex(name)}]">
                        <option class="form-control"></option>
                    </select>
                    <p class="error error_compound_id_${getUniqueIndex(name)}"></p>
                </div>
                <div class="col-md-1" style="padding-top: 26px; !important">
                    <a class="btn btn-sm bg-danger-light btn_delete">
                        <span class=" remove_record btn text-danger"><i class="ti-trash"></i>{{ __('pages.delete') }}</span>
                    </a>                
                </div>
            </div>
        `);
        
        function placeholder(){
            return $(this).attr('placeholder');
        }

        $(".select_owner").select2({
            ajax: {
                url: "{{ route('users') }}",
                type: "post",
                dataType: 'json',
                delay: 250,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: function (term) {
                    return {
                        term: term
                    };
                },
                processResults: function (response) {
                    return {
                        results: $.map(response, function(item) {
                            return {
                                text: item.name,
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

        $(".select_compound").select2({
            ajax: {
                url: "{{ route('compounds') }}",
                type: "post",
                dataType: 'json',
                delay: 250,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: function (term) {
                    return {
                        term: term,
                        user_id: $(this).parent().siblings().eq(0).children().eq(1).val()
                    };
                },
                processResults: function (response) {
                    return {
                        results: $.map(response, function(item) {
                            return {
                                text: item.name,
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

        $(".select_owner").on("select2:select", function (evt) {
            id = evt.params.data.id;

            function route(){
                return $(this).attr('route');
            }

            function placeholder(){
                return $(this).attr('placeholder');
            }
            
            function formatRepo (item) {
                return item.name;
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                },
                url: "{{ route('buildings.users') }}",
                method: 'get',
                data: {id: id},
                success: (data) => {
                    $(this).append(`
                        <option value="${data['id']}" name="${data['name']}" selected>
                            ${data['name']}
                        </option>
                    `).select2({
                        ajax: {
                            url: "{{ route('users') }}",
                            type: "post",
                            dataType: 'json',
                            delay: 250,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: function (term) {
                                return {
                                    term: term
                                };
                            },
                            processResults: function (response) {
                                return {
                                    results: $.map(response, function(item) {
                                        return {
                                            text: item.name,
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
                }
            });
        });

        $(".select_compound").on("select2:select", function (evt) {
            id = evt.params.data.id;
            
            function route(){
                return $(this).attr('route');
            }

            function placeholder(){
                return $(this).attr('placeholder');
            }
            
            function formatRepo (item) {
                return item.name;
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                },
                url: "{{ route('buildings.compounds') }}",
                method: 'get',
                data: {id: id},
                success: (data) => {
                    $(this).children().remove();

                    $(this).append(`
                        <option value="${data['id']}" name="${data['name']}" selected>
                            ${data['name']}
                        </option>
                    `).select2({
                        ajax: {
                            url: "{{ route('compounds') }}",
                            type: "post",
                            dataType: 'json',
                            delay: 250,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: function (term) {
                                return {
                                    term: term
                                };
                            },
                            processResults: function (response) {
                                return {
                                    results: $.map(response, function(item) {
                                        return {
                                            text: item.name,
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
                }
            });
        });
    });
        
    $('.add-new-apartment').on('click',function(e){
        let name = $(this).attr("name");
        let childrens = $(`.compound-container`).children();
      
        $(`.apartment_compound-container`).append(`
            <div class="compound_row row record m-2" index="${getUniqueIndex(name)}">
                <div class="col-md-3">
                    <label class="mb-2">اسم الوحدة</label>
                    <input class="form-control" type="text" name="apartment_name[${getUniqueIndex(name)}]" placeholder="اسم الوحدة">
                    <p class="error error_apartment_name"></p>
                </div>
                <div class="col-md-2 d-none">
                    <label class="mb-2">{{ __('pages.tenant') }}</label>
                    <select class="form-control select_tenant d-flex" style="height: 100px !important;" placeholder="{{ __('pages.tenant') }}" route="{{route('usersTenant')}}" name="tenant_id[${getUniqueIndex(name)}]">
                    </select>
                </div>
                <div class="col-md-2 d-none">
                    <label class="mb-2">تاريخ بدء العقد</label>
                    <input type="date" class="form-control" name="tenant_date[${getUniqueIndex(name)}]" value="">
                    <p class="error error_price"></p>
                </div>
                <div class="col-md-2 d-none">
                    <label class="mb-2">تاريخ انتهاء العقد</label>
                    <input type="date" class="form-control" name="tenant_enddate[${getUniqueIndex(name)}]" value="">
                    <p class="error error_price"></p>
                </div>
                <div class="col-md-2 d-none">
                    <label class="mb-2">{{ __('pages.cost') }}</label>
                    <input class="form-control text-start" type="text" name="price[${getUniqueIndex(name)}]" placeholder="{{ __('pages.cost') }}" >
                    <p class="error error_price"></p>
                </div>
                <div class="col-md-1 d-none" style="padding-top: 26px; !important">
                    <a class="btn btn-sm bg-danger-light btn_delete">
                        <span class=" remove_record btn text-danger"><i class="ti-trash"></i>{{ __('pages.delete') }}</span>
                    </a>                
                </div>
                <div class="col-md-3 add_tenant" style="padding-top: 28px; !important">
                    <a class="btn btn-primary" style="height: 45px !important;">
                        <span class="btn text-white">اضف {{ __('pages.tenant') }} في الوحدة +</span>
                    </a>
                </div>
                <div class="col-md-12 ps-5">
                    <input type="file" class="dropify" data-default-file="" name="picture[${getUniqueIndex(name)}]"/>
                    <p class="error error_picture"></p>
                </div>
            </div>
        `);
        
        $('.dropify').dropify();

        function placeholder(){
            return $(this).attr('placeholder');
        }

        $(".select_tenant").select2({
            ajax: {
                url: "{{ route('usersTenant') }}",
                type: "post",
                dataType: 'json',
                delay: 250,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: function (term) {
                    return {
                        term: term
                    };
                },
                processResults: function (response) {
                    return {
                        results: $.map(response, function(item) {
                            return {
                                text: item.name,
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

        $(".select_owner").on("select2:select", function (evt) {
            id = evt.params.data.id;

            function route(){
                return $(this).attr('route');
            }

            function placeholder(){
                return $(this).attr('placeholder');
            }
            
            function formatRepo (item) {
                return item.name;
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                },
                url: "{{ route('buildings.users') }}",
                method: 'get',
                data: {id: id},
                success: (data) => {
                    $(this).append(`
                        <option value="${data['id']}" name="${data['name']}" selected>
                            ${data['name']}
                        </option>
                    `).select2({
                        ajax: {
                            url: "{{ route('users') }}",
                            type: "post",
                            dataType: 'json',
                            delay: 250,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: function (term) {
                                return {
                                    term: term
                                };
                            },
                            processResults: function (response) {
                                return {
                                    results: $.map(response, function(item) {
                                        return {
                                            text: item.name,
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
                }
            });
        });
    });

    $(document).ready(function(){
        function route(){
            return $(this).attr('route');
        }

        function placeholder(){
            return $(this).attr('placeholder');
        }
        
        $(".compound_id").select2({
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
                        user_id: $(this).parent().siblings().eq(0).children().eq(1).val()
                    };
                },
                processResults: function (response) {
                    return {
                        results: $.map(response, function(item) {
                            return {
                                text: item.name,
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
    
    $(document).on('click','.remove_record',function(){
        $(this).closest('.record').remove();
    });
    
    $(document).on('click','.add_tenant',function(){
        $(this).siblings().eq(1).removeClass('d-none');
        $(this).siblings().eq(2).removeClass('d-none');
        $(this).siblings().eq(3).removeClass('d-none');
        $(this).siblings().eq(4).removeClass('d-none');
        $(this).siblings().eq(5).removeClass('d-none');
        $(this).siblings().eq(6).removeClass('d-none');
        $(this).addClass('d-none');
    });
</script>
@endsection