@extends('layouts.master')
@section('css')
<!-- Internal Data table css -->
<link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
<style>
    input,
	select {
        margin-bottom: 20px !important;
        font-weight: bold !important;
    }
</style>
@endsection
@section('title', 'كل الفواتير');
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ الكل</span>
						</div>
					</div>
                    @can('اضافة فاتورة')
                        <div class="d-flex my-xl-auto right-content">
                            <div class="mb-3 mb-xl-0">
                                <a class="btn btn-primary btn-block" href="{{route('invoices.create')}}">اضافة فاتورة</a>
                            </div>
                        </div>  
                    @endcan
					
				</div>
				<!-- breadcrumb -->
@endsection
@section('content')

@if (session()->has('update'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>{{ session()->get('update') }}</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@if (session()->has('delete'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>{{ session()->get('delete') }}</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif
				<!-- row -->

                    <div class="row">


                        <div class="col-xl-12">
                            <div class="card mg-b-20">
                                <div class="card-header pb-0">
                                    <div class="d-flex justify-content-between">
                                        <h4 class="card-title mg-b-0">عرض الفواتير</h4>
                                        <i class="mdi mdi-dots-horizontal text-gray"></i>
                                        @can('تصدير EXCEL')
                                        <div class="mb-3 mb-xl-0">
                                            <a class="btn btn-outline-primary btn-block" href="{{url('export_invoices')}}">تصدير للاكسل</a>
                                        </div>
                                    @endcan
                                    </div>
                                    
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        
                                        <table id="example1" class="table key-buttons text-md-nowrap">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0">#</th>
                                                    <th class="border-bottom-0">رقم الفاتورة</th>
                                                    <th class="border-bottom-0">تاريخ القاتورة</th>
                                                    <th class="border-bottom-0">تاريخ الاستحقاق</th>
                                                    <th class="border-bottom-0">المنتج</th>
                                                    <th class="border-bottom-0">القسم</th>
                                                    <th class="border-bottom-0">الخصم</th>
                                                    <th class="border-bottom-0">نسبة الضريبة</th>
                                                    <th class="border-bottom-0">قيمة الضريبة</th>
                                                    <th class="border-bottom-0">الاجمالي</th>
                                                    <th class="border-bottom-0">الحالة</th>
                                                    <th class="border-bottom-0">ملاحظات</th>
                                                    <th class="border-bottom-0">العمليات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                $i = 0;
                                                @endphp
                                                @foreach ($invoices as $invoice)
                                                    @php
                                                    $i++
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $i }}</td>
                                                        <td>{{ $invoice->invoice_number }} </td>
                                                        <td>{{ $invoice->invoice_Date }}</td>
                                                        <td>{{ $invoice->Due_date }}</td>
                                                        <td>{{ $invoice->product }}</td>
                                                        <td><a
                                                                href="{{ url('InvoicesDetails') }}/{{ $invoice->id }}">{{ $invoice->section->section_name }}</a>
                                                        </td>
                                                        <td>{{ $invoice->Discount }}</td>
                                                        <td>{{ $invoice->Rate_VAT }}</td>
                                                        <td>{{ $invoice->Value_VAT }}</td>
                                                        <td>{{ $invoice->Total }}</td>
                                                        <td>
                                                            @if ($invoice->Value_Status == 1)
                                                                <span class="text-success">{{ $invoice->Status }}</span>
                                                            @elseif($invoice->Value_Status == 2)
                                                                <span class="text-danger">{{ $invoice->Status }}</span>
                                                            @else
                                                                <span class="text-warning">{{ $invoice->Status }}</span>
                                                            @endif
                
                                                        </td>
                
                                                        <td>{{ $invoice->note }}</td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <button aria-expanded="false" aria-haspopup="true"
                                                                    class="btn ripple btn-primary btn-sm" data-toggle="dropdown"
                                                                    type="button">العمليات<i class="fas fa-caret-down ml-1"></i></button>
                                                                <div class="dropdown-menu tx-13">
                                                                    @can('تعديل الفاتورة')
                                                                        <a class="dropdown-item"
                                                                            href=" {{ route('invoices.edit', $invoice->id)}}">
                                                                            <i class="text-success fas fa-pencil-alt"></i>&nbsp;&nbsp;
                                                                            تعديل
                                                                            الفاتورة</a>
                                                                    @endcan
                                                                    @can('حذف الفاتورة')
                                                                        <a class="dropdown-item delete_invoice" href="#" data-invoice_id="{{ $invoice->id }}"
                                                                            data-toggle="modal" data-target="#delete_invoice"><i
                                                                                class="text-danger fas fa-trash-alt"></i>&nbsp;&nbsp;حذف
                                                                            الفاتورة</a>
                                                                    @endcan

                                                                    @can('تغير حالة الدفع')
                                                                        <a class="dropdown-item"
                                                                            href="{{ route('invoices.show', $invoice->id) }}"><i
                                                                                class=" text-success fas                                                                                                                                                                                                                                                                                                                                                                                                                                                                          fa-money-bill"></i>&nbsp;&nbsp;تغير
                                                                            حالة
                                                                            الدفع</a>
                                                                    @endcan
                                                                    @can('ارشفة الفاتورة')
                                                                        <a class="dropdown-item archive_invoice" href="#" data-invoice_id="{{ $invoice->id }}"
                                                                            data-toggle="modal" data-target="#archive_invoice"><i
                                                                                class="text-warning fas fa-exchange-alt"></i>&nbsp;&nbsp;نقل الي
                                                                            الارشيف</a>
                                                                    @endcan
                                                                    @can('طباعةالفاتورة')
                                                                        <a class="dropdown-item" href="Print_invoice/{{ $invoice->id }}"><i
                                                                                class="text-success fas fa-print"></i>&nbsp;&nbsp;طباعة
                                                                            الفاتورة
                                                                        </a>
                                                                    @endcan
                                                                </div>
                                                            </div>
                
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
				
				<!-- row closed -->
			</div>

		<!--End Large Modal -->

        


        <!-- Delete Modal -->
		<div class="modal" id="delete_invoice">
			<div class="modal-dialog" role="document">
				<div class="modal-content modal-content-demo">
					<div class="modal-header">
						<h6 class="modal-title">حذف الفاتورة</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
					</div>
					<div class="modal-body">
						<h6>هل أنت متأكد من حذف </h6>
                        
					</div>
					<div class="modal-footer">
                            <a class="btn ripple btn-danger delete-invoice-btn" href="">حذف</a>
                            <button class="btn ripple btn-secondary" data-dismiss="modal" type="button">الغاء</button>
                        

					</div>
				</div>
			</div>
		</div>
		<!--End Large Modal -->

        <!-- Archive Modal -->
		<div class="modal" id="archive_invoice">
			<div class="modal-dialog" role="document">
				<div class="modal-content modal-content-demo">
					<div class="modal-header">
						<h6 class="modal-title">أرشفة الفاتورة</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
					</div>
					<div class="modal-body">
						<h6>هل أنت متأكد من الأرشفة</h6>
                        
					</div>
					<div class="modal-footer">
                            <a class="btn ripple btn-success archive-invoice-btn" href="">أرشفة</a>
                            <button class="btn ripple btn-secondary" data-dismiss="modal" type="button">الغاء</button>
                        

					</div>
				</div>
			</div>
		</div>
		<!--End Archive Modal -->

			<!-- Container closed -->
		</div>
		<!-- main-content closed -->
@endsection
@section('js')
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js')}}"></script>

<!--Internal  Datatable js -->
<script src="{{URL::asset('assets/js/table-data.js')}}"></script>
<!-- Internal Modal js-->
<script src="{{URL::asset('assets/js/modal.js')}}"></script>

<script>
    $("a.delete_invoice").click(function () {
        $('.delete-invoice-btn').attr('href',  "{{url('/delete_invoice')}}/" + $(this).data('invoice_id') );
    })

    $("a.archive_invoice").click(function () {
        $('.archive-invoice-btn').attr('href',  "{{url('/archive_invoice')}}/" + $(this).data('invoice_id') );
    })
</script>

@endsection