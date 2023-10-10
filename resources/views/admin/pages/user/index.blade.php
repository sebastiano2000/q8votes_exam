
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
                            <h3 class="page-title">{{ __('pages.users') }}</h3>
                        </div>
                        <div class="col-sm-5 col">
                            <a href="{{ route('user.upsert') }}" class="btn btn-primary float-end mt-2">  <i class="ti-plus"></i> {{ __('pages.add_user') }}</a>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <form class="form" action="{{ route('user.filter') }}" method="get">
                                        <div class="form-group d-flex align-items-center">
                                            <input type="search" placeholder="{{ __('pages.search_by_name') }}" name="name" class="form-control d-block search_input w-50" value="{{request()->input('name')}}">
                                            <button class="btn btn-primary mx-2 btn-search">{{ __('pages.search') }}</button>
                                        </div>
                                    </form>
                                    <table id="example" class=" display  table table-hover table-center mb-0"  filter="{{ route('user.filter') }}">
                                        <thead>
                                            <tr>
                                                <th>{{ __('pages.name') }}</th>
                                                <th>{{ __('pages.mobile') }}</th>
                                                <th>{{ __('pages.email') }}</th>
                                                <th>رقم المدني</th>
                                                <th>{{ __('pages.role') }}</th>
                                                <th class="text-end">{{ __('pages.actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($users as $user)
                                                <tr class="record">
                                                    <td><a href="{{ route('profile', ['user' => $user->id]) }}">{{ $user->name }}</a></td>
                                                    <td>{{ $user->phone }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>{{ $user->national_id }}</td>
                                                    @if($user->role_id == '1')
                                                        <td>{{ __('pages.admin') }}</td>
                                                    @elseif($user->role_id == '2')
                                                        <td>{{ __('pages.owner') }}</td>
                                                    @elseif($user->role_id == '3')
                                                        <td>{{ __('pages.tenant') }}</td>
                                                    @endif
                                                    <td class="text-end">
                                                        <div class="actions">
                                                            <a href="#" onclick="edit_partner(this)"
                                                                data-target="#edit_partner"
                                                                data-toggle="modal"
                                                                data-id="{{$user->id}}"
                                                                data-full_name="{{$user->name}}"
                                                                data-email="{{$user->email}}"
                                                                data-national="{{$user->national_id}}"
                                                                data-role="{{$user->role_id}}"
                                                                data-phone="{{$user->phone}}"
                                                                data-image="@if($user->picture){{ asset('/users/'.$user->id.'/'.$user->picture->name) }}@endif"
                                                                data-contract="@if($user->contract){{ asset('/users/'.$user->id.'/'.$user->contract->name) }}@endif"
                                                                class="btn btn-sm bg-success-light"
                                                            >
                                                                <i class="ti-pencil"></i> {{ __('pages.edit') }}  
                                                            </a>
                                                            <a href="#" onclick="edit_password(this)"
                                                                data-target="#edit_password"
                                                                data-toggle="modal"
                                                                data-id="{{$user->id}}"
                                                                class="btn btn-sm bg-info-light"
                                                            >
                                                                <i class="ti-pencil"></i> تعديل كلمة السر 
                                                            </a>
                                                            @if($user->role_id != '1') 
                                                                <a data-bs-toggle="modal" href="#" class="btn btn-sm bg-danger-light btn_delete" route="{{ route('user.delete',['user' => $user->id])}}">
                                                                    <i class="ti-trash"></i> {{ __('pages.delete') }}
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>  
                                    </table>

                                    <nav aria-label="Page navigation example" class="mt-2">
                                        <ul class="pagination">
                                            @for($i = 1; $i <= $users->lastPage(); $i++)
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
                <div id="edit_partner" class="modal fade">   
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="modelHeading">{{ __('pages.edit_user_info') }}</h4>
                                <span class="button" data-dismiss="modal" aria-label="Close">   <i class="ti-close"></i> </span>
                            </div>
                            <div class="modal-body">
                                <form method="post" enctype="multipart/form-data" action="{{ route('user.modify') }}" class="ajax-form" swalOnSuccess="{{ __('pages.sucessdata') }}" title="{{ __('pages.opps') }}" swalOnFail="{{ __('pages.wrongdata') }}" redirect="{{ route('user') }}">
                                    @csrf
                                    <input type="hidden" name="id" id="id">
                                    <div class="form-group">
                                        <label for="name" class="col-sm-2 mb-2 control-label">{{ __('pages.name') }}</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" id="full_name" name="name" placeholder="Enter Name" value="" maxlength="50" required="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="mb-2">{{ __('pages.Phone') }}</label>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <input placeholder="{{ __('pages.Phone') }}" type="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" name="phone" value="">
                                                <p class="error error_phone"></p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <x-country-phone-code></x-country-phone-code>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 mb-2 control-label">{{ __('pages.email') }}</label>
                                        <div class="col-sm-12">
                                            <input placeholder="{{ __('pages.email') }}" type="phone" id="email" class="form-control @error('email') is-invalid @enderror" name="email" value="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 mb-2 control-label">رقم المدني</label>
                                        <div class="col-sm-12">
                                            <input class="form-control" type="text" id="national_id" name="national_id" value="" placeholder="رقم القومي">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 mb-2 control-label">{{ __('pages.role') }}</label>
                                        <div class="col-sm-12">
                                            <select placeholder="{{ __('pages.role') }}" id="role_id" type="phone" class="form-control @error('role') is-invalid @enderror" name="role_id">
                                                <option class="form-control" value="2">{{ __('pages.owner') }}</option>
                                                <option class="form-control" value="3">{{ __('pages.tenant') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="image">
                                    </div>
                                    <div id="contract">
                                    </div>
                                    <div class="col-sm-offset-2 col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary" id="saveBtn" value="create">{{ __('pages.save') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div> 
                </div>
                <div id="edit_password" class="modal fade">   
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="modelHeading">تغير كلمة السر</h4>
                                <span class="button" data-dismiss="modal" aria-label="Close">   <i class="ti-close"></i> </span>
                            </div>
                            <div class="modal-body">
                                <form method="post" enctype="multipart/form-data" action="{{ route('user.password') }}" class="ajax-form" swalOnSuccess="{{ __('pages.sucessdata') }}" title="{{ __('pages.opps') }}" swalOnFail="{{ __('pages.wrongdata') }}" redirect="{{ route('user') }}">
                                    @csrf
                                    <input type="hidden" name="id" id="id">
                                    <div class="form-group">
                                        <label class="mb-2">كلمة السر</label>
                                        <div class="col-md-12">
                                            <input class="form-control text-start" id="password" type="text" name="password" placeholder="كلمة السر" >
                                            <p class="error error_password"></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-2">تاكيد كلمة السر</label>
                                        <div class="col-md-12">
                                            <input class="form-control text-start" id="confirm_password" type="text" name="password_confirmation" placeholder="كلمة السر" >
                                            <p class="error error_email"></p>
                                        </div>
                                    </div>
                                    <div class="col-sm-offset-2 col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary" id="saveBtn" value="create">{{ __('pages.save') }}
                                        </button>
                                    </div>
                                </form>
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
    $('.copyval').on('click',function(e){
       let x=$(this).attr('value');
       e.preventDefault();
       document.addEventListener('copy', function(e) {
          e.clipboardData.setData('text/plain', x);
          e.preventDefault();
       }, true);
       document.execCommand('copy');  
    });

    function edit_partner(el) {
        var link = $(el);
        var modal = $("#edit_partner");
        var full_name = link.data('full_name');
        var id = link.data('id');
        var email = link.data('email');
        var role = link.data('role');
        var phone = link.data('phone');
        var national = link.data('national');
        var image = link.data('image');
        var contract = link.data('contract');
    
        modal.find('#full_name').val(full_name);
        modal.find('#id').val(id);
        modal.find('#email').val(email);
        modal.find('#phone').val(phone);
        modal.find('#role_id').val(role);
        modal.find('#national_id').val(national);
    
        $("#image").children().remove();
        $("#image").append(`
            <div class="form-group">
                <input type="file" class="dropify" src="" data-default-file="${image}" name="picture"/>
                <p class="error error_picture"></p>
            </div>
        `);
        
        $("#contract").children().remove();
        $("#contract").append(`
            <div class="form-group">
                <input type="file" class="dropify" src="" data-default-file="${contract}" name="contract"/>
                <p class="error error_contract"></p>
            </div>
        `);
        
        $('.dropify').dropify();
    }

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