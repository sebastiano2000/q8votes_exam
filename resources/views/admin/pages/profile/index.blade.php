@extends('admin.layout.master')
@section('content')
<div class="main-wrapper">
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-7 col-auto">
                        <h3 class="page-title">{{ __('pages.personal_details') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item active">{{ __('pages.personal_details') }}</li>
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
                            <!--<form method="post" enctype="multipart/form-data" action="{{ route('user.modify') }}" class="ajax-form" swalOnSuccess="{{ __('pages.sucessdata') }}" title="{{ __('pages.opps') }}" swalOnFail="{{ __('pages.wrongdata') }}" redirect="{{ route('user') }}">-->
                            <!--    @csrf-->

                            <div class="tab-content profile-tab-cont">
                                <div class="tab-pane fade show active" id="per_details_tab">
                                    <div class="row">
                                        <div class="row">
                                            <p class="col-sm-2 text-black mb-2 mb-sm-3 font-bold">{{ __('pages.name')}}
                                            </p>
                                            <p class="col-sm-10 text-black">{{ $user->name }}</p>
                                        </div>
                                        <div class="row">
                                            <p class="col-sm-2 text-black mb-2 mb-sm-3 font-bold">{{ __('pages.email')}}
                                            </p>
                                            <p class="col-sm-10 text-black">{{ $user->email }}</p>
                                        </div>
                                        <div class="row">
                                            <p class="col-sm-2 text-black mb-2 mb-sm-3 font-bold">{{ __('pages.Phone')}}
                                            </p>
                                            <p class="col-sm-10 text-black">{{ $user->phone }}</p>
                                        </div>
                                        <div class="row">
                                            <p class="col-sm-2 text-black mb-2 mb-sm-3 font-bold">رقم المدني</p>
                                            <p class="col-sm-10 text-black user_phone">{{ $user->national_id }}</p>
                                        </div>
                                        @if(Auth::user()->isSuperAdmin() || Auth::user()->role_id == 2)
                                        <div class="row col-6">
                                            <p class="col-sm-12 text-black mb-3 font-bold">صورة البطاقة المدنية</p>
                                            <img class="col-sm-12 mb-0 text-black" style="width: 300px;"
                                                src="@if($user->picture){{ asset('/users/'.$user->id.'/'.$user->picture->name) }}@endif" />
                                        </div>
                                        <div class="row col-6">
                                            <p class="col-sm-12 text-black mb-3 font-bold">صورة عقد الزواج</p>
                                            <img class="col-sm-12 mb-0 text-black" style="width: 300px;"
                                                src="@if($user->contract){{ asset('/users/'.$user->id.'/'.$user->contract->name) }}@endif" />
                                        </div>
                                        @endif
                                        <div class="row">
                                            <a href="#" onclick="edit_password(this)" data-target="#edit_password"
                                                data-toggle="modal" data-id="{{$user->id}}"
                                                class="btn btn-sm bg-info-light">
                                                <i class="ti-pencil"></i> تعديل كلمة السر
                                            </a>
                                            {{-- @dd($user->id) --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--<div class="service-fields mb-3">-->
                            <!--    <div class="form-group">-->
                            <!--        <div class="row">-->

                            <!--            <div class="form-group">-->
                            <!--                <label class="mb-2">صورة البطاقة المدنية</label>-->
                            <!--                <input type="file" class="dropify" data-default-file="@if($user->picture){{ asset('/users/'.$user->id.'/'.$user->picture->name) }}@endif" name="picture"/>-->
                            <!--                <p class="error error_picture"></p>-->
                            <!--            </div>-->
                            <!--            <div class="form-group">-->
                            <!--                <label class="mb-2">صورة عقد الزواج</label>-->
                            <!--                <input type="file" class="dropify" user_id="{{Auth::user()->id}}" data-default-file="@if($user->contract){{ asset('/users/'.$user->id.'/'.$user->contract->name) }}@endif" name="contract"/>-->
                            <!--                <p class="error error_contract"></p>-->
                            <!--            </div>-->
                            <!--        </div>-->
                            <!--    </div>-->
                            <!--</div>-->
                            <!--    @isset($user->id)-->
                            <!--        <input type="hidden" value="{{$user->id}}" name="id">-->
                            <!--    @endisset-->
                            <!--    <div class="submit-section">-->
                            <!--        <button class="btn btn-primary submit-btn" type="submit" name="form_submit" placeholder="submit">{{ __('pages.submit') }}</button>-->
                            <!--    </div>-->
                            <!--</form>-->
                            <!-- /Add Blog -->
                        </div>
                    </div>
                </div>
            </div>
            <div id="edit_password" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="modelHeading">تغير كلمة السر</h4>
                            <span class="button" data-dismiss="modal" aria-label="Close"> <i class="ti-close"></i>
                            </span>
                        </div>
                        <div class="modal-body">
                            <form method="post" enctype="multipart/form-data" action="{{ route('user.password') }}"
                                class="ajax-form" swalOnSuccess="{{ __('pages.sucessdata') }}"
                                title="{{ __('pages.opps') }}" swalOnFail="{{ __('pages.wrongdata') }}"
                                redirect="{{ route('profile') }}">
                                @csrf
                                <input type="hidden" name="id" id="id">
                                <div class="form-group">
                                    <label class="mb-2">كلمة السر</label>
                                    <div class="col-md-12">
                                        <input class="form-control text-start" id="password" type="text" name="password"
                                            placeholder="كلمة السر">
                                        <p class="error error_password"></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="mb-2">تاكيد كلمة السر</label>
                                    <div class="col-md-12">
                                        <input class="form-control text-start" id="confirm_password" type="text"
                                            name="password_confirmation" placeholder="كلمة السر">
                                        <p class="error error_email"></p>
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
        </div>

    </div>
    <!-- /Page Wrapper -->

</div>
@endsection

@section('js')
<script>
    function edit_password(el) {
        var link = $(el);
        var modal = $("#edit_password");
        var password = link.data('password');
        var id = link.data('id');
        var confirm_password = link.data('confirm_password');
    
        modal.find('#password').val(password);
        modal.find('#id').val(id);
        modal.find('#confirm_password').val(confirm_password);
    }
</script>

@endsection