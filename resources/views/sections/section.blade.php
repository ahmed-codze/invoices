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
    input {
        margin-bottom: 20px !important;
        font-weight: bold !important;
    }
</style>
@endsection
@section('title', 'الأقسام');
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">الاعدادات</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ الأقسام</span>
						</div>
					</div>
					<div class="d-flex my-xl-auto right-content">
						<div class="mb-3 mb-xl-0">
                                <a class="modal-effect btn btn-outline-primary btn-block" data-effect="effect-scale" data-toggle="modal" href="#modaldemo1">اضافة قسم</a>
						</div>
					</div>
				</div>
				<!-- breadcrumb -->
@endsection
@section('content')
				<!-- row -->

                    <div class="row">


                        <!-- show flash messages -->

                        <!-- add section error -->
                        @if($errors->any()) 
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                              حث خطأ أثناء الاضافة !!
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                        
                        @endif
                        <!-- success adding section -->
                        @if (Session::has('add-success')) 
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{Session::get('add-success')}}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                        @endif

                        <!-- success updating section -->

                        @if (Session::has('updating-success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{Session::get('updating-success')}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                        @endif
                        <!-- success deleting section -->

                        @if (Session::has('deleting-success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{Session::get('deleting-success')}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                        @endif
                        <div class="col-xl-12">
                            <div class="card mg-b-20">
                                <div class="card-header pb-0">
                                    <div class="d-flex justify-content-between">
                                        <h4 class="card-title mg-b-0">عرض الأقسام</h4>
                                        <i class="mdi mdi-dots-horizontal text-gray"></i>
                                    </div>
                                    
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        
                                        <table id="example1" class="table key-buttons text-md-nowrap">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0">#</th>
                                                    <th class="border-bottom-0"> اسم القسم</th>
                                                    <th class="border-bottom-0">الوصف </th>
                                                    <th class="border-bottom-0">قام بالانشاء</th>
                                                    <th class="border-bottom-0">العمليات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $count = 1;?>
                                                @foreach ($sections as $section)
                                                <tr class="section-{{$section['id']}}">
                                                    <td>{{$count++}}</td>
                                                    <td>{{$section['section_name']}}</td>
                                                    <td>{{$section['description']}}</td>
                                                    <td>{{$section['created_by']}}</td>
                                                    <td>
                                                        <a href="#" class="btn btn-danger delete-section-btn" data-name="{{$section['section_name']}}" data-id="{{$section['id']}}" data-target="#deleteModal" data-toggle="modal" href="">حذف</a>
                                                        <a data-id="{{$section['id']}}" data-name="{{$section['section_name']}}" data-description="{{$section['description']}}" class="open-edit-button modal-effect btn btn-primary" data-effect="effect-scale" data-toggle="modal" href="#edit_modal">تعديل</a>
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

            		<!-- Large Modal -->
		<div class="modal fade" id="modaldemo1">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content modal-content-demo">
					<div class="modal-header">
                    @can('اضافة قسم')
						<h6 class="modal-title">اضافة قسم</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
					@endcan
                    </div>
					<div class="modal-body">
						<form action="{{route('sections.store')}}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-12">
                                    <input type="text" class="form-control @error('section_name') is-invvalid @enderror" name="section_name" value="{{old("section_name")}}" placeholder="اسم القسم">
                                </div>
                                @error('section_name')
                                <div class="alert-danger">{{$message}}</div>                                    
                                @enderror

                                <div class="col-12">
                                    <textarea class="form-control @error("description") is-invalid @enderror" name="description" placeholder="وصف القسم"></textarea>
                                </div>
                                @error('description')
                                    <div class="alert-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn ripple btn-primary" type="button">تأكيد</button>
                                <button class="btn ripple btn-secondary" data-dismiss="modal" type="button">اغلاق</button>
                            </div>
                        </form>
					</div>

				</div>
			</div>
		</div>
		<!--End Large Modal -->

        
            		<!-- edit Modal -->
		<div class="modal fade" id="edit_modal">
			<div class="modal-dialog modal-lg" role="document">
                @can('تعديل قسم')
				<div class="modal-content modal-content-demo">
					<div class="modal-header">
						<h6 class="modal-title">تعديل القسم</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
					
                    </div>
					<div class="modal-body">
						<form action="sections/update" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" class="d-none edit-section-id" name="id" value="">
                            <div class="row">
                                <div class="col-12">
                                    <input type="text" class="form-control edit-section-name @error('section_name') is-invalid @enderror" name="section_name" placeholder="اسم القسم">
                                </div>

                                @error('section_name')
                                <div class="alert-danger">{{$message}}</div>
                                @enderror
                                <div class="col-12">
                                    <textarea class="form-control edit-section-description @error('description') is-invalid @enderror" name="description" placeholder="وصف القسم"></textarea>
                                </div>
                                
                                    @error('description')
                                    <div class="alert-danger">{{$message}}</div>
                                    @enderror
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn ripple btn-primary" type="button">تأكيد</button>
                                <button class="btn ripple btn-secondary" data-dismiss="modal" type="button">اغلاق</button>
                            </div>
                        </form>
					</div>

				</div>
                @endcan
			</div>
		</div>

        <!-- Delete Modal -->
		<div class="modal" id="deleteModal">
			<div class="modal-dialog" role="document">
				<div class="modal-content modal-content-demo">
                @can('حذف قسم')
					<div class="modal-header">
                    
						<h6 class="modal-title">حذف القسم</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
					</div>
					<div class="modal-body">
						<h6>هل أنت متأكد من حذف قسم <span class="section-name"></span></h6>
                        

					</div>
					<div class="modal-footer">
                        <form action="sections/destroy" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" class="d-none delete-section-id" name="id" value="">
                            <button class="btn ripple btn-danger" type="submit">حذف</button>
                            <button class="btn ripple btn-secondary" data-dismiss="modal" type="button">الغاء</button>
                        </form>

					</div>
                @endcan
				</div>
			</div>
		</div>
		<!--End Large Modal -->
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

    // edit modal 

    $('.open-edit-button').click(function () {
        $('.edit-section-id').val($(this).data('id'));    
        $('.edit-section-name').val($(this).data('name'));    
        $('.edit-section-description').val($(this).data('description'));    
    })

    // delete modal 
    $('.delete-section-btn').click(function () {
        $('#deleteModal .section-name').text($(this).data('name'));
        $('#deleteModal .delete-section-id').val($(this).data('id'));
    })

</script>
@endsection