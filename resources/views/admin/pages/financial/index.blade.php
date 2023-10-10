@extends('admin.layout.master')
@section('content')
<div class="main-wrapper">
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-7 col-auto">
                        <h3 class="page-title">سجل العمليات</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item active">سجل العمليات</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <form class="form row" action="{{ route('financial_transaction.filter') }}"
                                    method="get">
                                    <div class="form-group col-md-11 d-flex align-items-center">
                                        <input type="search" placeholder="{{ __('pages.search_by_name') }}" name="name"
                                            class="form-control d-block search_input w-25"
                                            value="{{request()->input('name')}}">
                                        <button class="btn btn-primary mx-2 btn-search">{{ __('pages.search')
                                            }}</button>
                                    </div>
                                    <a class="btn btn-sm bg-danger col-md-1 my-3"
                                        href="{{ route('financial_transaction.financial-pdf') }}">
                                        <i class="ti-print"></i> PDF
                                    </a>
                                </form>
                                @if(Auth::user()->isSuperAdmin())
                                <div class="form-group d-flex align-items-center">
                                    <div id="excel-form" class="form row">
                                        <input type="month" name="transaction_month" id="transaction_month"
                                            class="form-control d-block" value="{{ date('Y-m') }}">
                                        <a id="export-link" class="btn btn-export btn-primary mt-2"
                                            href="{{ route('export-transactions') }}">
                                            تحميل سجل العمليات
                                        </a>
                                    </div>
                                </div>
                                @endif
                                <table id="exampleTable" class="table display table-hover table-center mb-0"
                                    filter="{{ route('financial_transaction.filter') }}">
                                    <thead>
                                        <tr>
                                            <th>اسم المستأجر</th>
                                            <th>اسم العقار</th>
                                            <th>اسم الوحدة</th>
                                            <th>تاريخ الدفع</th>
                                            <th>شهر السداد</th>
                                            <th>حالة الدفع</th>
                                            <th>المبلغ الإجمالي</th>
                                            <th>الإيصال</th>
                                            @if(Auth::user()->isSuperAdmin())
                                            <th class="text-end">تراجع</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($financial_transactions as $financial_transaction)
                                        <tr class="record">
                                            <td>{{ $financial_transaction->tenant->name }}</td>
                                            <td>{{ $financial_transaction->tenancy->building->name }}</td>
                                            <td>{{ $financial_transaction->tenancy->apartment->name }}</td>
                                            <td>{{ $financial_transaction->created_at}} </td>
                                            <td>{{ $financial_transaction->payment->pay_monthes ?? ' '}} </td>
                                            <td>{{ $financial_transaction->resultCode == 'CAPTURED' ? 'ناجح' : 'غير
                                                ناجح' }}</td>
                                            <td>{{ $financial_transaction->total_amount }}</td>
                                            {{-- link to view the recipt --}}
                                            <td>
                                                <a class='btn btn-primary'
                                                    href="{{ route('financial_transaction-pdf', $financial_transaction->id)}}">
                                                    {{__('pages.invoice')}}
                                                </a>
                                            </td>
                                            @if(Auth::user()->isSuperAdmin())
                                            <td>
                                                <a data-bs-toggle="modal" href="#"
                                                    class="btn btn-sm bg-danger-light btn_delete"
                                                    route="{{ route('financial_transaction.delete',['financial_transaction' => $financial_transaction->id])}}">
                                                    <i class="ti-trash"> {{ __('pages.delete') }}</i>
                                                </a>
                                            </td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation example" class="mt-2">
                                    <ul class="pagination">
                                        @for($i = 1; $i <= $financial_transactions->lastPage(); $i++)
                                            <li class="page-item"><a class="page-link" href="?page={{$i}}">{{$i}}</a>
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

    </div>
    <!-- /Page Wrapper -->

</div>
@endsection

@section('js')
<script>
    // sort table by paidOn
    let table = new DataTable('#exampleTable', {
        order: [[3, 'desc']],
        dom: 'Bfrtip',
        buttons: [],
        searching: false,
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
        }
    });


    $(document).on('click', '.btn-export', function(e) {
        const date = new Date();

        var selectedMonth = $('#transaction_month').val();
        var exportUrl = "{{ route('export-transactions') }}";
        // Append the selectedMonth to the exportUrl
        if (exportUrl.indexOf('?') !== -1) {
            exportUrl += '&transaction_month=' + selectedMonth;
        } else {
            exportUrl += '?transaction_month=' + selectedMonth;
        }
        $(this).attr('href', exportUrl + `&?v=${date}`);
    });

</script>

@endsection