
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
                            <h3 class="page-title">{{ __('pages.apartments') }}</h3>
                        </div>
                        <div class="col-sm-5 col">
                            <a href="{{ route('apartment.upsert') }}" class="btn btn-primary float-end mt-2">  <i class="ti-plus"></i> {{ __('pages.add_apartment') }}</a>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <form class="form" action="{{ route('apartment.filter') }}" method="get">
                                        <div class="form-group d-flex align-items-center">
                                            <input type="search" placeholder="{{ __('pages.search_by_name') }}" name="name" class="form-control d-block search_input w-50" value="{{request()->input('name')}}">
                                            <button class="btn btn-primary mx-2 btn-search">{{ __('pages.search') }}</button>
                                        </div>
                                    </form>
                                    <table id="example" class=" display  table table-hover table-center mb-0"  filter="{{ route('apartment.filter') }}">
                                        <thead>
                                            <tr>
                                                <th>{{ __('pages.name') }}</th>
                                                <th>{{ __('pages.building_name') }}</th>
                                                <th>{{ __('pages.paid') }}</th>
                                                <th class="text-end">{{ __('pages.actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($apartments as $apartment)
                                            <tr class="record">
                                                <td>{{ $apartment->name }}</td>
                                                <td>{{ $apartment->building->name }}</td>
                                                <td>@if($apartment->tenant){{ $apartment->tenant->paid ? __('pages.is_paid') : __('pages.is_not_paid') }} @else {{ __('pages.is_not_paid') }} @endif</td>
                                                <td class="text-end">
                                                    <div class="actions">
                                                        <a href="{{ route('apartment.upsert',['apartment' => $apartment->id]) }}" class="btn btn-sm bg-success-light" >
                                                            <i class="ti-pencil"></i> {{ __('pages.edit') }}
                                                        </a>
                                                        <a  data-bs-toggle="modal" href="#" class="btn btn-sm bg-danger-light btn_delete" route="{{ route('apartment.delete',['apartment' => $apartment->id])}}">
                                                            <i class="ti-trash"></i> {{ __('pages.delete') }}
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>  
                                    </table>
                                    <div id="edit_partner" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="modelHeading">{{ __('pages.edit_apartment_info') }}</h4>
                                                    <span class="button" data-dismiss="modal" aria-label="Close">   <i class="ti-close"></i> </span>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="post" enctype="multipart/form-data" action="{{ route('apartment.modify') }}" class="ajax-form" swalOnSuccess="{{ __('pages.sucessdata') }}" title="{{ __('pages.opps') }}" swalOnFail="{{ __('pages.wrongdata') }}" redirect="{{ route('apartment') }}">
                                                        @csrf
                                                        <input type="hidden" name="id" id="id">
                                                        <div class="form-group">
                                                            <label for="name" class="col-sm-2 control-label">{{ __('pages.name') }}</label>
                                                            <div class="col-sm-12">
                                                                <input type="text" class="form-control" id="full_name" name="name" placeholder="Enter Name" value="" maxlength="50" required="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
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
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-2 control-label">{{ __('pages.email') }}</label>
                                                            <div class="col-sm-12">
                                                                <input placeholder="{{ __('pages.email') }}" type="phone" id="email" class="form-control @error('email') is-invalid @enderror" name="email" value="">
                                                            </div>
                                                        </div>
                                                        <div id="image">
                                                        </div>
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                         <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes
                                                         </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div> 
                                    </div>

                                    <nav aria-label="Page navigation example" class="mt-2">
                                        <ul class="pagination">
                                            @for($i = 1; $i <= $apartments->lastPage(); $i++)
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
})
function edit_partner(el) {
    var link = $(el) //refer `a` tag which is clicked
    var modal = $("#edit_partner") //your modal
    var full_name = link.data('full_name')
    var id = link.data('id')
    var email = link.data('email')
    var phone = link.data('phone')
    var image = link.data('image')

    modal.find('#full_name').val(full_name);
    modal.find('#id').val(id);
    modal.find('#email').val(email);
    modal.find('#phone').val(phone);
    $("#image").children().remove();
    $("#image").append(`
        <div class="form-group">
            <input type="file" class="dropify" src=""  data-default-file="${image}" name="picture"/>
            <p class="error error_picture"></p>
        </div>
    `);
    $('.dropify').dropify();
}

</script>

@endsection