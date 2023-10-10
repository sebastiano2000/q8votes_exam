
@extends('admin.layout.master')
@section('content')
    <div class="main-wrapper">
        <!-- Page Wrapper -->
        <div class="page-wrapper">
            <div class="content container-fluid">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-7 col-auto">
                            <h3 class="page-title">{{ __('pages.buildings') }}</h3>
                        </div>
                        <div class="col-sm-5 col">
                            @if(Auth::user()->isSuperAdmin())<a href="{{ route('building.upsert') }}" class="btn btn-primary float-end mt-2"><i class="ti-plus"></i> {{ __('pages.add_building') }}</a>@endif
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <form class="form row" action="{{ route('building.filter') }}" method="get">
                                        <div class="form-group col-md-11 d-flex align-items-center">
                                            <input type="search" placeholder="{{ __('pages.search_by_name') }}" name="name" class="form-control d-block search_input w-50" value="{{request()->input('name')}}">
                                            <button class="btn btn-primary mx-2 btn-search">{{ __('pages.search') }}</button>
                                        </div>
                                        <a class="btn btn-sm bg-danger col-md-1 my-3" href="{{ route('building.buildings-pdf') }}">
                                            <i class="ti-print"></i> PDF
                                        </a>
                                    </form>
                                    <table id="example" class="display table table-hover table-center mb-0" filter="{{ route('building.filter') }}">
                                        <thead>
                                            <tr>
                                                <th>{{ __('pages.name') }}</th>
                                                <th>{{ __('pages.name_compound') }}</th>
                                                <th>عدد الوحدات</th>
                                                @if(Auth::user()->isSuperAdmin())<th class="text-end">{{ __('pages.actions') }}</th>@endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($buildings as $building)
                                                <tr class="record">
                                                    <td>
                                                        <a href="{{ route('building.edit.building', ['building' => $building->id]) }}">
                                                            {{ $building->name }}
                                                        </a>        
                                                    </td>
                                                    <td>
                                                        @foreach($building->compounds as $compound)
                                                            {{ $compound->name . ' - ' }}
                                                        @endforeach
                                                    </td>
                                                    <td>{{ count($building->apartments) }}</td>
                                                    @if(Auth::user()->isSuperAdmin())
                                                        <td class="text-end">
                                                            <div class="actions">
                                                                @if(Auth::user()->isSuperAdmin())
                                                                    @if(count($building->compounds) > 0)
                                                                        <a class="btn btn-sm bg-primary text-white"
                                                                            href="{{ route('maintenance.add', ['user' => $building->compounds->first()->user->id, 'compound' => $building->compounds->first()->id, 'building' => $building->id, 'apartment' => 0]) }}">
                                                                             اضف مصروفات في العقار +
                                                                        </a>
                                                                    @endif
                                                                
                                                                    <a href="#" onclick="edit_partner(this)"
                                                                        data-target="#edit_partner"
                                                                        data-toggle="modal"
                                                                        data-id="{{$building->id}}"
                                                                        data-name="{{$building->name}}"
                                                                        data-user_id="@if(count($building->compounds) > 0)@if($building->compounds->first()->user){{$building->compounds->first()->user->id}}@endif @endif"
                                                                        data-username="@if(count($building->compounds) > 0)@if($building->compounds->first()->user){{$building->compounds->first()->user->name}}@endif @endif"
                                                                        data-compound_name="@if(count($building->compounds) > 0){{$building->compounds->first()->name}}@endif"
                                                                        data-compound_id="@if(count($building->compounds) > 0){{$building->compounds->first()->id}}@endif"
                                                                        class="add-new-apartment btn btn-sm btn-primary"
                                                                    >
                                                                        اضف وحدة في العقار +
                                                                    </a>
                                                                    <a href="#" onclick="edit_building(this)"
                                                                        data-target="#edit_building"
                                                                        data-toggle="modal"
                                                                        data-id="{{$building->id}}"
                                                                        data-name="{{$building->name}}"
                                                                        class="btn btn-sm bg-success-light"
                                                                    >
                                                                        <i class="ti-pencil"></i> {{ __('pages.edit') }}
                                                                    </a>
                                                                @endif
                                                                <a data-bs-toggle="modal" href="#" class="btn btn-sm bg-danger-light btn_delete" route="{{ route('building.delete',['building' => $building->id])}}">
                                                                    <i class="ti-trash"></i> {{ __('pages.delete') }}
                                                                </a>
                                                            </div>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>  
                                    </table>

                                    <nav aria-label="Page navigation example" class="mt-2">
                                        <ul class="pagination">
                                            @for($i = 1; $i <= $buildings->lastPage(); $i++)
                                                <li class="page-item">
                                                    <a class="page-link" href="?page={{$i}}">{{$i}}</a>
                                                </li>
                                            @endfor
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="edit_partner" class="modal fade">   
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="modelHeading">اضافة وحدة في العقار</h4>
                            <span class="button" data-dismiss="modal" aria-label="Close"><i class="ti-close"></i></span>
                        </div>
                        <div class="modal-body">
                            <form method="post" enctype="multipart/form-data" action="{{ route('building.appartment') }}" class="ajax-form" swalOnSuccess="{{ __('pages.sucessdata') }}" title="{{ __('pages.opps') }}" swalOnFail="{{ __('pages.wrongdata') }}" redirect="{{ route('building') }}">
                                @csrf
                                <input type="hidden" name="id" id="id">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="mb-2">{{ __('pages.compound_owner') }}</label>
                                        <div class="form-group">
                                            <select class="form-control d-flex" id="user_id" placeholder="{{ __('pages.compound_owner') }}" disabled>
                                            </select>
                                            <input type="hidden" class="form-control" id="user_input" name="user_id" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="mb-2">{{ __('pages.compound') }}</label>
                                        <div class="form-group">
                                            <select class="form-control d-flex" id="compound_id" placeholder="{{ __('pages.compound_owner') }}" disabled>
                                            </select>
                                            <input type="hidden" class="form-control" id="compound_input" name="compound_id" value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="mb-2">{{ __('pages.building') }}</label>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <select class="form-control d-flex" id="building_id" placeholder="{{ __('pages.compound_owner') }}" disabled>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-md-6 col-sm-12 mb-2 control-label">اسم الوحدة</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="full_name" name="name" value="" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-6 mb-2 control-label">{{ __('pages.tenant') }}</label>
                                    <div class="col-sm-12">
                                        <select id="tenant_id" class="form-control selectTenant" route="{{ route('usersTenant') }}" name="tenant_id">
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6 col-sm-12 flex-column mb-3">
                                        <label for="name" class="col-md-6 col-sm-12 mb-2 control-label">تاريخ بدء الإيجار</label>
                                        <div class="row" style="justify-content: center;">
                                            <input type="date" style="width:92% !important;" class="form-control" name="tenant_date" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 flex-column mb-3">
                                        <label for="name" class="col-md-6 col-sm-12 mb-2 control-label">تاريخ انتهاء الإيجار</label>
                                        <div class="row" style="justify-content: center;">
                                            <input type="date" style="width:92% !important;" class="form-control" name="tenant_enddate" value="">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 flex-column">
                                        <label for="name" class="col-sm-12 mb-2 control-label">{{ __('pages.cost') }}</label>
                                        <input type="text" class="form-control" name="price" value="">
                                    </div>
                                </div>
                                <div id="image">
                                    <div class="form-group">
                                        <input type="file" class="dropify" src="" data-default-file="" name="picture"/>
                                        <p class="error error_picture"></p>
                                    </div>
                                </div>
                                <div class="col-sm-offset-2 col-sm-12 text-center">
                                    <button type="submit" class="btn btn-primary" id="saveBtn" value="create">
                                        {{ __('pages.save') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div id="edit_building" class="modal fade">   
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="modelHeading">تعديل اسم العقار</h4>
                            <span class="button" data-dismiss="modal" aria-label="Close"><i class="ti-close"></i></span>
                        </div>
                        <div class="modal-body">
                            <form method="post" enctype="multipart/form-data" action="{{ route('building.name') }}" class="ajax-form" swalOnSuccess="{{ __('pages.sucessdata') }}" title="{{ __('pages.opps') }}" swalOnFail="{{ __('pages.wrongdata') }}" redirect="{{ route('building') }}">
                                @csrf
                                <input type="hidden" name="id" id="building_id">
                                <div class="form-group">
                                    <label for="name" class="col-md-12 col-sm-12 mb-2 control-label">اسم العقار</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="building_name" name="name" value="" required>
                                    </div>
                                </div>
                                <div class="col-sm-offset-2 col-sm-12 text-center">
                                    <button type="submit" class="btn btn-primary" id="saveBtn" value="create">
                                        {{ __('pages.save') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('js')
<script>
    $(document).ready(function(){
        $(".selectTenant").select2({
            dropdownParent: $("#edit_partner"),
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
        
        $('.dropify').dropify();

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
    
    $(".select-tenants").on("select2:select", function (evt) {
        let tenant_value = $(this).attr("tenant_value").trim();
        let div_tenant = $(this).parent().parent();
        let apartment_id = $(div_tenant).attr("apartment_id");
        
        if(tenant_value != ""){
            Swal.fire({
                html: ` 
                    <h3 class="mb-2">تاريخ انتهاء الإيجار</h3>
                    <input type="date" class="form-control date_tenant mt-2 w-75" name="tenant_date" value="">
                    <div class="mt-3">في حالة الحفظ سيتم تغيير المستأجر</div>
                `,
                preConfirm: () => {
                    if (document.getElementsByClassName('date_tenant')[0].value) {
                        $(div_tenant).append(`
                            <input type="hidden" class="form-control date_tenant mt-2" name="tenant_date[${apartment_id}]" value="${document.getElementsByClassName('date_tenant')[0].value}">
                        `);
                    } else {
                        Swal.showValidationMessage('برجاء اختيار التاريخ');
                    }
                },
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ __("pages.confirm") }}',
                cancelButtonText: '{{ __("pages.cancel") }}'
            });
        }
    });
    
    function edit_partner(el) {
        var link = $(el);
        var modal = $("#edit_partner");
        var id = link.data('id');
        var name = link.data('name');
        var user_id = link.data('user_id');
        var username = link.data('username');
        var compound_name = link.data('compound_name');
        var compound_id = link.data('compound_id');
        modal.find('#id').val(id);
        modal.find('#user_input').val(user_id);
        modal.find('#compound_input').val(compound_id);

        modal.find('#user_id').append(`
            <option class="form-control" value="${user_id}" selected>${username}</option>
        `);
        modal.find('#compound_id').append(`
            <option class="form-control" value="${compound_id}" selected>${compound_name}</option>
        `);
        modal.find('#building_id').append(`
            <option class="form-control" value="${id}" selected>${name}</option>
        `);
    }
    
    function edit_building(el) {
        var link = $(el);
        var modal = $("#edit_building");
        var id = link.data('id');
        var building_name = link.data('name');
        modal.find('#building_id').val(id);
        modal.find('#building_name').val(building_name);
    }
    
    $(document).on('click','.add_tenant',function(){
        $(this).siblings().eq(1).removeClass('d-none');
        $(this).siblings().eq(2).removeClass('d-none');
        $(this).siblings().eq(3).removeClass('d-none');
        $(this).siblings().eq(4).removeClass('d-none');
        $(this).siblings().eq(5).removeClass('d-none');
        $(this).addClass('d-none');
    });
</script>

@endsection