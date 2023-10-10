@extends('admin.layout.master')
@section('content')
<div class="main-wrapper">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="content container-fluid">
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-10 col-auto">
                            <h3 class="page-title">اضافة وحدات العقار السكنية</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:(0);">وحدات العقار السكنية</a></li>
                            </ul>
                        </div>
                        <div class="col-sm-2 col">
                            @if(Auth::user()->isSuperAdmin())
                            <a href="#" onclick="edit_partner(this)" data-target="#edit_partner" data-toggle="modal"
                                data-id="{{$building->id}}" data-name="{{$building->name}}"
                                data-user_id="@if(count($building->compounds) > 0)@if($building->compounds->first()->user){{$building->compounds->first()->user->id}}@endif @endif"
                                data-username="@if(count($building->compounds) > 0)@if($building->compounds->first()->user){{$building->compounds->first()->user->name}}@endif @endif"
                                data-compound_name="@if(count($building->compounds) > 0){{$building->compounds->first()->name}}@endif"
                                data-compound_id="@if(count($building->compounds) > 0){{$building->compounds->first()->id}}@endif"
                                class="add-new-apartment btn btn-sm btn-primary float-end mt-2">
                                اضف وحدة في العقار +
                            </a>
                            <a class="btn btn-sm bg-primary text-white float-end mt-2"
                                href="{{ route('maintenance.add', ['user' => $building->compounds->first()->user->id, 'compound' => $building->compounds->first()->id, 'building' => $building->id, 'apartment' => 0]) }}">
                                اضف مصروفات في العقار +
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="col-sm-12 col-auto row pe-3">
                                <h3 class="page-title col-md-11 p-3 pe-0">{{ __('pages.building_name') . ': ' .
                                    $building->name }}</h3>
                                <h3 class="page-title col-md-11 p-3 pe-0">{{ __('pages.total_amount') . ': ' .
                                    $building->building_revenue }}</h3>
                                <h3 class="page-title col-md-11 p-3 pe-0">{{ __('pages.rental_total') . ': ' .
                                    $building->building_tenant }}</h3>
                                <h3 class="page-title col-md-11 p-3 pe-0">{{ __('pages.total_paid_amount') . ': ' .
                                    $building->building_paid }}</h3>
                                <h3 class="page-title col-md-11 p-3 pe-0">{{ __('pages.total_not_paid_amount') . ': ' .
                                    $building->building_tenant - $building->building_paid }}</h3>
                                <h3 class="page-title col-md-11 p-3 pe-0">{{ __('pages.occupied_count') . ': ' .
                                    $building->apartment_occupied }}</h3>
                                <h3 class="page-title col-md-11 p-3 pe-0">{{ __('pages.vacant_count') . ': ' .
                                    count($building->apartments) - $building->apartment_occupied }}</h3>
                                <h3 class="page-title col-md-11 p-3 pe-0">{{ __('pages.not_paid_count') . ': ' .
                                    $building->apartment_notpaid }}</h3>
                                <!--<a class="btn btn-primary col-md-2 my-3" href="{{ route('export-apartments', ['building_id' => $building->id]) }}">-->
                                <!--   تحميل بيانات العقار-->
                                <!--</a>-->
                                <a class="btn btn-sm bg-danger col-md-1 my-3"
                                    href="{{ route('building-pdf',['building' => $building->id]) }}">
                                    <i class="ti-print"></i> PDF
                                </a>

                                {{-- <label for="month"> تحصبلات شهر: </label> --}}
                                {{-- month input --}}
                                <input type="month" name="revenu_month" id="revenu_month"
                                    class="form-control col-md-2 my-3" value="{{ date('Y-m') }}"">
                                <a id="export-link" class="btn btn-export btn-primary col-md-2 my-3"
                                    href="{{ route('export-revenu',['building' => $building->id]) }}">
                                تحميل بيانات العقار
                                </a>

                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <div class="custom-edit-service">
                                        <div class="col-md-12">
                                            <!--<div class="compound-container">-->
                                            <!--    @foreach($building->compounds as $compound)-->
                                            <!--        <div class="row compound_row record">-->
                                            <!--            <div class="col-md-6 col-sm-12 mb-3" not_select="true">-->
                                            <!--                <label class="mb-2">{{ __('pages.compound_owner') }}</label>-->
                                            <!--                <select class="form-control select2" id="user_id" placeholder="{{ __('pages.compound_owner') }}" route="{{ route('users') }}" name="user_id[]" @if(Auth::user()->role_id == 2)disabled @endif>-->
                                            <!--                    <option class="form-control" value="@if($building->compounds)@if($building->compounds->first()->user){{$building->compounds[$loop->iteration - 1]->user->id }}@endif @endif" selected>@if($building->compounds)@if($building->compounds->first()->user){{ $building->compounds[$loop->iteration - 1]->user->name }}@endif @endif</option>-->
                                            <!--                </select>-->
                                            <!--            </div>-->
                                            <!--            <div class="col-md-5 col-sm-12 mb-3" not_select="true">-->
                                            <!--                <label class="mb-2">{{ __('pages.compound') }}</label>-->
                                            <!--                <select class="form-control compound_id" id="compound_id" placeholder="{{ __('pages.compound_owner') }}" route="{{route('compounds')}}" name="compound_id[]" @if(Auth::user()->role_id == 2)disabled @endif>-->
                                            <!--                    <option class="form-control" selected value="@if($building->compounds){{$building->compounds[$loop->iteration - 1]->id}}@endif">@if($building->compounds){{ $building->compounds[$loop->iteration - 1]->name }}@endif</option>-->
                                            <!--                </select>-->
                                            <!--            </div>-->
                                            <!--            <div class="col-md-1 col-sm-12 mb-3" style="padding-top: 30px !important;">-->
                                            <!--                @if(Auth::user()->isSuperAdmin())-->
                                            <!--                    <a class="btn btn-sm bg-danger-light btn_delete" style="height: 40px !important;">-->
                                            <!--                        <span class="remove_record btn text-danger"><i class="ti-trash"></i>{{ __('pages.delete') }}</span>-->
                                            <!--                    </a>-->
                                            <!--                @endif-->
                                            <!--            </div>-->
                                            <!--        </div>-->
                                            <!--    @endforeach-->
                                            <!--</div>-->
                                            <table id="example5" class="display table table-hover table-center mb-0"
                                                filter="{{ route('building.filter') }}">
                                                <thead>
                                                    <tr>
                                                        <th>اسم الوحدة</th>
                                                        <th>{{ __('pages.tenant') }}</th>
                                                        <th>تاريخ بدء العقد</th>
                                                        <th>تاريخ انتهاء العقد</th>
                                                        <th>{{ __('pages.cost') }}</th>
                                                        <th>مسدد</th>
                                                        @if(Auth::user()->isSuperAdmin())<th class="text-end">منع الدفع
                                                        </th>@endif
                                                        <th>الحالة</th>
                                                        @if(Auth::user()->isSuperAdmin())<th class="text-end">دفع نقدي
                                                        </th>@endif
                                                        @if(Auth::user()->isSuperAdmin())<th class="text-end">{{
                                                            __('pages.actions') }}</th>@endif
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($building->apartments as $apartment)
                                                    <tr class="compound_row mb-3 record"
                                                        apartment_id="{{$apartment->id}}">
                                                        <td>
                                                            {{ $apartment->name }}
                                                        </td>

                                                        <td>
                                                            @if($apartment->tenants)
                                                            @if($apartment->tenants()->latest()->first())
                                                            @if($apartment->tenants()->latest()->first()->tenant)
                                                            <a
                                                                href="{{ route('profile', ['user' => $apartment->tenants()->latest()->first()->tenant->id]) }}">
                                                                {{
                                                                $apartment->tenants()->latest()->first()->tenant->name
                                                                }}
                                                            </a>
                                                            @endif
                                                            @endif
                                                            @endif
                                                        </td>

                                                        <td>
                                                            @if($apartment->tenants)
                                                            @if($apartment->tenants()->latest()->first())
                                                            {{ $apartment->tenants()->latest()->first()->start_date }}
                                                            @endif
                                                            @endif
                                                        </td>

                                                        <td>
                                                            @if($apartment->tenants)
                                                            @if($apartment->tenants()->latest()->first())
                                                            {{ $apartment->tenants()->latest()->first()->end_date }}
                                                            @endif
                                                            @endif
                                                        </td>

                                                        <td>
                                                            @if($apartment->tenants)
                                                            @if($apartment->tenants()->first())
                                                            {{ $apartment->tenants()->latest()->first()->price }}
                                                            @endif
                                                            @endif
                                                        </td>

                                                        <td>
                                                            @if($apartment->tenants)
                                                            @if($apartment->tenants()->first())                                                                
                                                            <!--{{ $apartment->tenants()->latest()->first()->paid ? date('m-Y', strtotime($apartment->tenants()->latest()->first()->end_payment)) : 'غير مسدد' }}-->

                                                            {{ $apartment->tenants()->latest()->first()->paid && strtotime($apartment->tenants()->latest()->first()->end_payment) >= strtotime(date("Y-m")) ? 'مسدد' :
                                                            'غير مسدد' }}

                                                            @endif
                                                            @endif
                                                        </td>
                                                        @if(Auth::user()->isSuperAdmin())
                                                        <td>
                                                            <label
                                                                class="container container2 switch_prepration_status">
                                                                <input type="checkbox" class="block_status"
                                                                    @if($apartment->tenants()->latest()->first()->is_blocked)
                                                                value="1" checked @else value="0" @endif tenant_id="{{
                                                                $apartment->tenants()->latest()->first()->id }}"
                                                                name="approved" style="width: 25px; height: 25px;">
                                                                <span class="checkmark"></span>
                                                            </label>
                                                        </td>
                                                        @endif
                                                        <td>
                                                            @if(Auth::user()->isSuperAdmin())
                                                            <input type="radio" id="html" class="approved_status"
                                                                name="status[{{$apartment->id}}]"
                                                                apartment_id="{{ $apartment->id }}"
                                                                tenant_id="@if($apartment->tenants)@if($apartment->tenants()->first())@if($apartment->tenants()->latest()->first()){{$apartment->tenants()->latest()->first()->id}}@endif @endif @endif"
                                                                value="1" @if($apartment->status) checked @endif>
                                                            <label for="html">مستأجر</label><br>
                                                            <input type="radio" id="css" class="approved_status"
                                                                name="status[{{$apartment->id}}]"
                                                                apartment_id="{{ $apartment->id }}"
                                                                tenant_id="@if($apartment->tenants)@if($apartment->tenants()->first())@if($apartment->tenants()->latest()->first()){{$apartment->tenants()->latest()->first()->id}}@endif @endif @endif"
                                                                value="0" @if(!$apartment->status) checked @endif>
                                                            <label for="css">شاغر</label><br>
                                                            @else
                                                            @if($apartment->status)مستأجر @else شاغر @endif
                                                            @endif
                                                        </td>

                                                        @if(Auth::user()->isSuperAdmin())
                                                        @if(count($apartment->tenants) > 0 && $apartment->status)
                                                        <td class="text-end" style="vertical-align: text-top;">
                                                            <a href="#" onclick="payment(this)" data-target="#payment"
                                                                data-target="#payment" data-toggle="modal"
                                                                data-id="@if(count($apartment->tenants) > 0)@if($apartment->tenants()->latest()->first())@if($apartment->tenants()->latest()->first()->tenant){{$apartment->tenants()->latest()->first()->tenant->id}}@endif @endif @endif"
                                                                data-apartment_name="{{$apartment->name}}"
                                                                data-tenant_id="@if(count($apartment->tenants) > 0)@if($apartment->tenants()->latest()->first()){{$apartment->tenants()->latest()->first()->id}}@endif @endif"
                                                                data-user_id="{{$apartment->user_id}}"
                                                                data-apartment_id="{{$apartment->id}}"
                                                                data-cost="@if(count($apartment->tenants) > 0)@if($apartment->tenants()->latest()->first()){{$apartment->tenants()->latest()->first()->price}}@endif @endif"
                                                                data-building_name="{{$building->name}}"
                                                                class="btn bg-success-light">
                                                                <i class="ti-money">
                                                                    {{ __('pages.pay_now') }}
                                                                </i>
                                                            </a>
                                                        </td>
                                                        @else
                                                        <td class="text-end" style="vertical-align: text-top;">
                                                            لا يوجد مستاجرين
                                                        </td>
                                                        @endif
                                                        @endif

                                                        @if(Auth::user()->isSuperAdmin())
                                                        <td class="text-end" style="vertical-align: text-top;">
                                                            @if(count($apartment->tenants) > 0 && $apartment->status &&
                                                            $apartment->tenants()->latest()->first()->price &&
                                                            $apartment->tenants()->latest()->first()->end_payment <
                                                                date('M-Y') && $apartment->
                                                                tenants()->latest()->first()->tenant &&
                                                                !$apartment->tenants()->latest()->first()->expire_date
                                                                && $apartment->tenants()->latest()->first()->is_blocked)
                                                                <a class="copyval btn btn-primary"
                                                                    this_id="{{$apartment->tenants()->latest()->first()->tenant->id}}"
                                                                    building_id="{{$building->id}}"
                                                                    apartment_id="{{$apartment->id}}"
                                                                    user_id="{{$apartment->user_id}}"
                                                                    tenant_id="{{$apartment->tenants()->latest()->first()->id}}"
                                                                    cost="{{$apartment->tenants()->latest()->first()->price}}"
                                                                    value="">
                                                                    <i class="ti-file ms-2" aria-hidden="true"></i>نسخ
                                                                    رابط الدفع
                                                                </a>
                                                                @endif

                                                                <!--<a class="btn bg-primary text-white"-->
                                                                <!--    href="{{ route('maintenance.add', ['user' => $building->compounds->first()->user->id, 'compound' => $building->compounds->first()->id, 'building' => $building->id, 'apartment' => $apartment->id]) }}">-->
                                                                <!--     اضف صيانة في الوحدة +-->
                                                                <!--</a>-->
                                                                <a href="#" onclick="edit_apartment(this)"
                                                                    data-target="#edit_apartment" data-toggle="modal"
                                                                    data-id="{{$building->id}}"
                                                                    data-apartment_id="{{$apartment->id}}"
                                                                    data-apartment_name="{{$apartment->name}}"
                                                                    data-user_id="@if(count($building->compounds) > 0)@if($building->compounds->first()->user){{$building->compounds->first()->user->id }}@endif @endif"
                                                                    data-compound_id="@if(count($building->compounds) > 0){{$building->compounds->first()->id }}@endif"
                                                                    data-tenant_id="@if(count($apartment->tenants) > 0)@if($apartment->tenants()->latest()->first())@if($apartment->tenants()->latest()->first()->tenant){{$apartment->tenants()->latest()->first()->tenant->id}}@endif @endif @endif"
                                                                    data-tenant_name="@if(count($apartment->tenants) > 0)@if($apartment->tenants()->latest()->first())@if($apartment->tenants()->latest()->first()->tenant){{$apartment->tenants()->latest()->first()->tenant->name}}@endif @endif @endif"
                                                                    data-start_date="@if(count($apartment->tenants) > 0)@if($apartment->tenants()->latest()->first()){{$apartment->tenants()->latest()->first()->start_date}}@endif @endif"
                                                                    data-end_date="@if(count($apartment->tenants) > 0)@if($apartment->tenants()->latest()->first()){{$apartment->tenants()->latest()->first()->end_date}}@endif @endif"
                                                                    data-price="@if(count($apartment->tenants) > 0)@if($apartment->tenants()->latest()->first()){{$apartment->tenants()->latest()->first()->price}}@endif @endif"
                                                                    data-image="@if(count($apartment->tenants) > 0)@if($apartment->tenants()->latest()->first())@if($apartment->tenants()->latest()->first()->picture){{ asset('/tenants/'.$apartment->tenants()->latest()->first()->id.'/'.$apartment->tenants()->latest()->first()->picture->name) }}@endif @endif @endif"
                                                                    class="add-new-apartment btn bg-success-light">
                                                                    <i class="ti-pencil"></i> {{ __('pages.edit') }}
                                                                </a>
                                                                <a class="btn bg-danger-light btn_delete"
                                                                    route="{{ route('building.appartment.delete',['appartment' => $apartment->id]) }}">
                                                                    <i class="ti-trash"></i>{{ __('pages.delete')
                                                                    }}</span>
                                                                </a>
                                                        </td>


                                                        <!--<td class="add_tenant {{$apartment->tenants ? $apartment->tenants()->latest()->first() ? $apartment->tenants()->latest()->first()->tenant ? 'd-none' : '' : '' : '' }} text-end">-->
                                                        <!--    <a class="btn btn-primary">-->
                                                        <!--        <span class="btn text-white">اضف {{ __('pages.tenant') }} في الوحدة +</span>-->
                                                        <!--    </a>-->
                                                        <!--</td>-->
                                                        @endif
                                                        <!--@if(Auth::user()->isSuperAdmin())-->
                                                        <!--    <td class="col-md-12">-->
                                                        <!--        <input type="file" class="dropify" data-default-file="@if($apartment->tenants()->latest()->first()) @if($apartment->tenants()->latest()->first()->picture){{ asset('/tenants/'.$apartment->tenants()->latest()->first()->id.'/'.$apartment->tenants()->latest()->first()->picture->name) }}@endif @endif" name="picture[{{$apartment->id}}]"/>-->
                                                        <!--        <p class="error error_picture"></p>-->
                                                        <!--    </td>-->
                                                        <!--@else-->
                                                        <!--    @if($apartment->tenants()->latest()->first())-->
                                                        <!--        @if($apartment->tenants()->latest()->first()->picture)-->
                                                        <!--            <td class="col-md-12">-->
                                                        <!--                <input type="file" class="dropify" data-default-file="@if($apartment->tenants()->latest()->first()) @if($apartment->tenants()->latest()->first()->picture){{ asset('/tenants/'.$apartment->tenants()->latest()->first()->id.'/'.$apartment->tenants()->latest()->first()->picture->name) }}@endif @endif" name="picture[{{$apartment->id}}]"/>-->
                                                        <!--                <p class="error error_picture"></p>-->
                                                        <!--            </td>-->
                                                        <!--        @endif-->
                                                        <!--    @endif-->
                                                        <!--@endif-->
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                            <!--<input type="hidden" value="{{$building->id}}" name="id">-->
                                            <!--<div class="submit-section">-->
                                            <!--    <button class="btn btn-primary submit-btn" type="submit" name="form_submit" placeholder="submit">{{ __('pages.submit') }}</button>-->
                                            <!--</div>-->
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
    <div id="edit_partner" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading">اضافة وحدة في العقار</h4>
                    <span class="button" data-dismiss="modal" aria-label="Close"><i class="ti-close"></i></span>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data" action="{{ route('building.appartment') }}"
                        class="ajax-form" swalOnSuccess="{{ __('pages.sucessdata') }}" title="{{ __('pages.opps') }}"
                        swalOnFail="{{ __('pages.wrongdata') }}"
                        redirect="{{ route('building.edit.building', ['building' => $building->id]) }}">
                        @csrf
                        <input type="hidden" name="id" id="id">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="mb-2">{{ __('pages.compound_owner') }}</label>
                                <div class="form-group">
                                    <select class="form-control d-flex" id="user_id"
                                        placeholder="{{ __('pages.compound_owner') }}" disabled>
                                    </select>
                                    <input type="hidden" class="form-control" id="user_input" name="user_id" value="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="mb-2">{{ __('pages.compound') }}</label>
                                <div class="form-group">
                                    <select class="form-control d-flex" id="compound_id"
                                        placeholder="{{ __('pages.compound_owner') }}" disabled>
                                    </select>
                                    <input type="hidden" class="form-control" id="compound_input" name="compound_id"
                                        value="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="mb-2">{{ __('pages.building') }}</label>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <select class="form-control d-flex" id="building_id"
                                        placeholder="{{ __('pages.compound_owner') }}" disabled>
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
                                <select id="tenant_id" class="form-control selectTenant"
                                    route="{{ route('usersTenant') }}" name="tenant_id">
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6 col-sm-12 flex-column mb-3">
                                <label for="name" class="col-md-6 col-sm-12 mb-2 control-label">تاريخ بدء
                                    الإيجار</label>
                                <div class="row" style="justify-content: center;">
                                    <input type="date" style="width:92% !important;" class="form-control"
                                        name="tenant_date" value="">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 flex-column mb-3">
                                <label for="name" class="col-sm-12 mb-2 control-label">تاريخ انتهاء
                                    الإيجار</label>
                                <div class="row" style="justify-content: center;">
                                    <input type="date" style="width:92% !important;" class="form-control"
                                        name="tenant_enddate" value="">
                                </div>
                            </div>
                            <div class="col-sm-12 flex-column">
                                <label for="name" class="col-sm-12 mb-2 control-label">{{ __('pages.cost') }}</label>
                                <input type="text" class="form-control" name="price" value="">
                            </div>
                        </div>
                        <div id="image">
                            <div class="form-group">
                                <input type="file" class="dropify" src="" data-default-file="" name="picture" />
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
    <div id="edit_apartment" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading">تعديل بيانات الوحدة</h4>
                    <span class="button" data-dismiss="modal" aria-label="Close"><i class="ti-close"></i></span>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data" action="{{ route('building.edit') }}"
                        class="ajax-form" swalOnSuccess="{{ __('pages.sucessdata') }}" title="{{ __('pages.opps') }}"
                        swalOnFail="{{ __('pages.wrongdata') }}"
                        redirect="{{ route('building.edit.building', ['building' => $building->id]) }}">
                        @csrf
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" name="compound_id[0]" id="compound_id">
                        <input type="hidden" name="tenant_old" id="tenant_old">
                        <input type="hidden" name="user_id[0]" id="user_id">

                        <div class="row">
                            <div class="col-md-6">
                                <label class="mb-2">اسم الوحدة</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="full_name" name="name" value=""
                                        required>
                                    <p class="error error_name"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="mb-2">{{ __('pages.tenant') }}</label>
                                <div class="form-group">
                                    <select id="tenant_id" class="form-control selectTenantApartment"
                                        route="{{ route('usersTenant') }}" name="tenant_id">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="mb-2">تاريخ بدء العقد</label>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="">
                                    <p class="error error_price"></p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-md-6 col-sm-12 mb-2 control-label">تاريخ انتهاء العقد</label>
                            <div class="col-sm-12">
                                <input type="date" class="form-control" id="end_date" name="end_date" value="">
                                <p class="error error_price"></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 mb-2 control-label">{{ __('pages.cost') }}</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="price" name="price" value="">
                                <p class="error error_price"></p>
                            </div>
                        </div>
                        <div id="image">
                            <div class="form-group">
                                <input type="file" class="dropify" src="" data-default-file="" name="picture" />
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
    <div id="payment" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading">الدفع</h4>
                    <span class="button" data-dismiss="modal" aria-label="Close"> <i class="ti-close"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <form method="post" id="paymentForm" enctype="multipart/form-data"
                        swalOnSuccess="{{ __('pages.sucessdata') }}" title="{{ __('pages.opps') }}"
                        swalOnFail="{{ __('pages.wrongdata') }}">
                        @csrf
                        <input type="hidden" name="id" id="pay_id">
                        <input type="hidden" name="tenant_id" id="tenant_pay_id">
                        <input type="hidden" name="apartment_id" id="apartment_pay_id">
                        <input type="hidden" name="user_id" id="user_pay_id">
                        <input type="hidden" name="cost" id="cost">
                        <div class="form-group">
                            <label class="mb-2">اسم العقار</label>
                            <div class="col-md-12">
                                <input class="form-control text-start" id="building_pay_name" type="text" disabled
                                    name="building_name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="mb-2">اسم الوحدة</label>
                            <div class="col-md-12">
                                <input class="form-control text-start" id="apartment_pay_name" type="text" disabled
                                    name="apartment_name">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="mb-2">ملاحظات الدفع</label>
                            <div class="col-md-12">
                                <input class="form-control text-start" id="payment_notes" type="text"
                                    name="payment_notes">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="mb-2" for="flexSwitchCheckDefault">دفع شهور متتعدده</label>
                            <div class="col-md-12">
                                <input class="form-check-input" type="checkbox" role="switch"
                                    id="flexSwitchCheckDefault">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="mb-2">عدد الشهور المراد دفعها</label>
                            <div class="col-md-12">
                                <select class="form-control select-month" multiple name="quantity"
                                    route="{{ route('data') }}" id="quantity">
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-offset-2 col-sm-12 text-center">
                            <button type="submit" class="btn btn-primary" id="saveBtn" value="create">{{
                                __('pages.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="rent" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading">الدفع</h4>
                    <span class="button" data-dismiss="modal" aria-label="Close"> <i class="ti-close"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <form method="post" id="rentForm" enctype="multipart/form-data"
                        swalOnSuccess="{{ __('pages.sucessdata') }}" title="{{ __('pages.opps') }}"
                        swalOnFail="{{ __('pages.wrongdata') }}">
                        @csrf
                        <input type="hidden" name="id" id="pay_id">
                        <input type="hidden" name="tenant_id" id="tenant_rent_id">
                        <input type="hidden" name="apartment_id" id="apartment_rent_id">
                        <input type="hidden" name="status" id="rent_status">
                        <input type="hidden" name="cost" id="cost">

                        <div class="col-sm-12 flex-column mb-3">
                            <label for="name" class="col-md-6 col-sm-12 mb-2 control-label">تاريخ انتهاء الإيجار</label>
                            <div class="row" style="justify-content: center;">
                                <input type="date" id="expire_date" style="width:92% !important;" class="form-control"
                                    name="expire_date" value="">
                            </div>
                        </div>

                        <div class="col-sm-offset-2 col-sm-12 text-center">
                            <button type="submit" class="btn btn-primary" id="saveBtn" value="create">
                                {{__('pages.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    let table = new DataTable('#example5', {
            dom: 'Bfrtip',
            buttons: [
                'excel',
            ],
            responsive: true,
            paging: false,
            info: false,
            language: {
                "sProcessing": "جارٍ التحميل...",
                "sLengthMenu": "أظهر _MENU_ مدخلات",
                "sZeroRecords": "لم يعثر على أية سجلات",
                "sInfo": "إظهار _START_ إلى _END_ من أصل _TOTAL_ مدخل",
                "sInfoEmpty": "يعرض 0 إلى 0 من أصل 0 سجل",
                "sInfoFiltered": "(منتقاة من مجموع _MAX_ مُدخل)",
                "sInfoPostFix": "",
                "sSearch": "ابحث:",
                "sUrl": "",
                "oPaginate": {
                    "sFirst": "الأول",
                    "sPrevious": "السابق",
                    "sNext": "التالي",
                    "sLast": "الأخير"
                }
            },
    });
    
    $("#example_filter").css({ 'margin-bottom': "20px" });
    $(".buttons-excel").css({ 'background': "#0171dc", 'margin-right': "10px !important" });
    $(".dt-buttons").css({ 'padding-top': "15px" });
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
        
        $(".selectTenantApartment").select2({
            dropdownParent: $("#edit_apartment"),
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
    
    function edit_apartment(el) {
        var link = $(el);
        var modal = $("#edit_apartment");
        var id = link.data('id');
        var name = link.data('apartment_name');
        var apartment_id = link.data('apartment_id');
        var tenant_id = link.data('tenant_id');
        var tenant_name = link.data('tenant_name');
        var user_id = link.data('user_id');
        var compound_id = link.data('compound_id');
        var start_date = link.data('start_date');
        var end_date = link.data('end_date');
        var price = link.data('price');
        var image = link.data('image');

        modal.find('#id').val(id);
        modal.find('#user_id').val(user_id);
        modal.find('#compound_id').val(compound_id);
        modal.find('#full_name').val(name);
        modal.find('#full_name').attr('name', `apartment_name[${apartment_id}]`);
        modal.find('#start_date').val(start_date.trim());
        modal.find('#start_date').attr('name', `start_date[${apartment_id}]`);
        modal.find('#end_date').val(end_date.trim());
        modal.find('#end_date').attr('name', `end_date[${apartment_id}]`);
        modal.find('#price').val(price);
        modal.find('#price').attr('name', `price[${apartment_id}]`);
        
        console.log(tenant_id)
        if(tenant_id){
            modal.find('#tenant_old').val(tenant_id);
        }
        
        modal.find('#tenant_old').attr('name', `tenant_old[${apartment_id}]`);

        modal.find('#tenant_id').append(`
            <option class="form-control" value="${tenant_id}" selected>${tenant_name}</option>
        `);
        modal.find('#tenant_id').attr('name', `tenant_id[${apartment_id}]`);
        
        $("#image").children().remove();
        $("#image").append(`
            <div class="form-group">
                <input type="file" class="dropify" src="" data-default-file="${image}" name="picture[${apartment_id}]"/>
                <p class="error error_picture"></p>
            </div>
        `);
    }
    
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
    
    $(".approved_status").on("change", function(){
        if($(this).parent().siblings().eq(2).text().trim() == "" && $(this).val() == "1"){
            $(this).parent().siblings().eq(7).children().eq(0).click();
        }
        else if($(this).parent().siblings().eq(2).text().trim() != "" && $(this).val() == "0"){
            $('#tenant_rent_id').val($(this).attr("tenant_id").trim());
            $('#apartment_rent_id').val($(this).attr("apartment_id").trim());
            $('#rent_status').val($(this).val());
            $('#rent').modal('show');
        }
        else{
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                },
                method: 'post',
                data: {id: $(this).attr("tenant_id").trim(), status: $(this).val(), apartment_id: $(this).attr("apartment_id").trim()},
                url: '{{ route("building.status") }}',
                success: function (data) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        icon: 'success',
                        title: '{{ __("pages.sucessdata") }}'
                    });
                    
                    if(data['id'] ?? null){
                        window.location.reload();
                    }
                }
            });
        }
    });

    $(document).ready(function(){
        let index_select = 0;
        let isCheckboxChecked = false; // Track the checkbox state
        
        function route(){
            return $(this).attr('route');
        }

        function placeholder(){
            return $(this).attr('placeholder');
        }
        
        function formatRepo (item) {
            return item.name;
        }
        
        function updateSelect2Options() {
            let maximumSelectionLength = isCheckboxChecked ? null : 1;

            $(".select-month").select2({
                dropdownParent: $("#payment"),
                maximumSelectionLength: maximumSelectionLength,
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
                            tenant_id: $("#pay_id").val(),
                            apartment_id: $("#apartment_pay_id").val(),
                        };
                    },
                    processResults: function (response) {
                        if(index_select == 0){
                            for(let i = 0; i < response.length; i++){
                                if(response[i]['status'] == 'enabled'){
                                    index_select = i;
                                    break;
                                }
                            }
                        }
                            
                        return {
                            results: $.map(response, function(item, index) {
                                return {
                                    text: item['date'],
                                    disabled: item['status'] == 'enabled' ? index <= index_select ? "" : "disabled" : "disabled",
                                    id: item['date'],
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

        // Initialize Select2 options
        updateSelect2Options();

        // Checkbox change event
        $('#flexSwitchCheckDefault').on('change', function() {
            isCheckboxChecked = $(this).is(':checked');
            // reset the selection
            $('.select-month').val(null).trigger('change');
            index_select = 0;
            updateSelect2Options();
        });

        $('.select-month').on("select2:select", function(e) {
            index_select++;
            updateSelect2Options();
        });

        $('.select-month').on("select2:unselect", function(e) {
            $('.select-month').val(null).trigger('change');
            index_select = 0;
            updateSelect2Options();
        });
    });

    $('#paymentForm').submit(function(e) {
        e.preventDefault();

        if($('#quantity').val().length < 1){
            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                icon: 'error',
                title: '{{ __("pages.opps") }}'
            });
            return;
        }
        
        $.ajax({
            url: "{{route('payment.cash')}}",
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            },
            type: "POST",
            async: false,
            contentType: "application/json; charset=utf-8",
            data: JSON.stringify({ 
                quantity: $('#quantity').val(),
                user_id: $('#user_pay_id').val(),
                apartment_id: $('#apartment_pay_id').val(),
                id: $('#pay_id').val(),
                tenant_id: $('#tenant_pay_id').val(),
                cost: $('#cost').val(),
                notes: $('#payment_notes').val()
            }),
            success: function(data){            
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    icon: 'success',
                    title: '{{ __("pages.sucessdata") }}'
                });

                window.location.reload();
            },
        });
    });

    $('#rentForm').submit(function(e) {
        e.preventDefault();

        $.ajax({
            url: "{{route('building.status')}}",
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            },
            type: "POST",
            async: false,
            contentType: "application/json; charset=utf-8",
            data: JSON.stringify({ id: $('#tenant_rent_id').val(), status: $('#rent_status').val(), apartment_id: $('#apartment_rent_id').val(), expire_date: $("#expire_date").val()}),
            success: function(data){
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    icon: 'success',
                    title: '{{ __("pages.sucessdata") }}'
                });

                window.location.reload();
            },
        });
    });

    function payment(el) {
        var link = $(el);
        var modal = $("#payment");
        var id = link.data('id');
        var tenant_id = link.data('tenant_id');
        var cost = link.data('cost');
        var building_name = link.data('building_name');
        var apartment_name = link.data('apartment_name');
        var apartment_id = link.data('apartment_id');
        var user_id = link.data('user_id');

        modal.find('#pay_id').val(id.trim());
        modal.find('#tenant_pay_id').val(tenant_id);
        modal.find('#cost').val(cost.trim());
        modal.find('#user_pay_id').val(user_id);
        modal.find('#apartment_pay_id').val(apartment_id);
        modal.find('#building_pay_name').val(building_name);
        modal.find('#apartment_pay_name').val(apartment_name);
    }

    $(document).on('click', '.btn-export', function(e) {
        const date = new Date();

        var selectedMonth = $('#revenu_month').val();
        var exportUrl = "{{ route('export-revenu',['building_id' => $building->id, 'building_name' => $building->name]) }}";
        if(!selectedMonth){
            window.reload();
        }
        // Append the selectedMonth to the exportUrl
        if (exportUrl.indexOf('?') !== -1) {
            exportUrl += '&date=' + selectedMonth;
        } else {
            exportUrl += '?date=' + selectedMonth;
        }

        // remove the &amp;
        exportUrl = exportUrl.replace('&amp;', '&');
        
        // Update the href attribute
        $(this).attr('href', exportUrl + `?v=${date}`);
    });
    
    $('.copyval').on('click',function(e){
        let now = $(this)

        const date = new Date();
        const options = {
            year: 'numeric',
            month: 'short',
        };
                                
        let day = date.toLocaleString('en-IN', options);
        day = day.split(" ");

        $.ajax({
            url: "{{route('payment.rent')}}",
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            },
            type: "POST",
            async: false,
            contentType: "application/json; charset=utf-8",
            data: JSON.stringify({ 
                quantity: [day[0] + '-' + day[1]],
                user_id: now.attr("user_id"),
                apartment_id: now.attr("apartment_id"),
                id: now.attr("this_id"),
                tenant_id: now.attr("tenant_id"),
                cost: now.attr("cost"),
            }),
            success: function(data){   
                document.addEventListener('copy', function(e) {
                    e.clipboardData.setData('text/plain', data);
                    e.preventDefault();
                }, true);
                
                document.execCommand('copy');
                
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    icon: 'success',
                    title: 'تم نسخ رابط الدفع',
                });
            },
        });
    });
    
    $(".block_status").on("change", function(){   
        if ($(this).is(":checked"))
        {
            $(this).val(1);
        }   
        else {
            $(this).val(0);
        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            },
            method: 'post',
            data: {id: $(this).attr("tenant_id"), approved: $(this).val()},
            url: '{{ route("tenant.status") }}',
            success: function () {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    icon: 'success',
                    title: '{{ __("pages.sucessdata") }}'
                });
                window.location.reload();
            }
        });
    });

</script>
@endsection