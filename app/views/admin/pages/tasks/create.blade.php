@extends('admin.layouts.default')
@section('title')
	@parent | ایجاد یک وظیفه جدید
@stop
@section('breadcrumb')
	@parent
	<li><a href="{{URL::to('task')}}"><i class="fa fa-envelope"></i> وظایف</a></li><li class="active"><i class="fa fa-plus"></i> ایجاد یک وظیفه جدید</li>
@stop
@section('content')
<div class="row">

	<div class="col-md-8 col-md-offset-2 col-sm-12 tableFontSize12">
    {{Form::open(array(
                    'url'			=>	Request::path(),
                    'method'		=>	'put',
                    'files'         =>  true
                ))}}
		<div class="row padding10">
			<div class="col-md-2 align-left">
				دریافت کننده:
			</div>
			<div class="col-md-10">
				{{Form::select('item[for_id]',SaleBoss\Models\User::getUserList('sales'),['class' => 'form-control'])}}
			</div>
		</div>
		<div class="row padding10">
			<div class="col-md-2 align-left">
				تاریخ اجرا:
			</div>
			<div class="col-md-10">
				{{Form::text('item[todo_at]',Input::old('item[todo_at]'), ['id' => 'todo_at'])}}
			</div>
		</div>
		<div class="row padding10">
	        <div class="col-md-2 align-left">
	            وضعیت:
	        </div>
	        <div class="col-md-10">
				{{Form::select( 'item[status]',['0'=>'انجام نشده','1'=>'انجام شده','2'=>' بسته شده'],['class' => 'form-control'])}}
	        </div>
	    </div>
		<div class="row padding10">
	        <div class="col-md-2 align-left">
	            گروه وظایف:
	        </div>
	        <div class="col-md-10">
				{{Form::select('item[category]',SaleBoss\Models\TaskCategory::getCategoryList(),['class' => 'form-control'])}}
	        </div>
	    </div>
	    <div class="row padding10">
            <div class="col-md-2 align-left">
                درجه اهمیت:
            </div>
            <div class="col-md-10">
				{{Form::select( 'item[priority]',['1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5'],['class' => 'form-control'])}}
            </div>
        </div>
		<div class="row padding10">
			<div class="col-md-2 align-left">
				شرح وظیفه:
			</div>
			<div class="col-md-7 justifyright">
				{{ Form::textarea('item[description]', Input::old('item[description]'), ['rows' => 6 ]) }}
			</div>
		</div>
		<div class="row padding10">
			<div class="col-md-2 align-left">
				فایل:
			</div>
			<div class="col-md-4 justifyright">
				<label class="ace-file-input">
					<input name="file" type="file">
						<span class="ace-file-container" data-title="Choose">
							<span class="ace-file-name" data-title="انتخاب فایل ...">
							<i class="ace-icon fa fa-upload"></i>
							</span>
						</span>
						<a class="remove" href="#">
						<i class=" ace-icon fa fa-times"></i>
						</a>
				</label>
			</div>
		</div>
		<div class="row padding10">
        			<div class="col-md-2 align-left">

        			</div>
        			<div class="col-md-6 justifyright">
        			    <a href="{{URL::to('task')}}"><button type="button" class="btn btn-xs margin-right operation-margin"><i class="ace-icon fa fa-arrow-right icon-on-right"></i> بازگشت</button></a>
        				<button class="btn btn-xs btn-success" type="submit"><i class="ace-icon fa fa-check bigger-120"></i> ارسال</button>
        			</div>
        		</div>
		{{Form::close()}}
	</div>

</div>
@stop


@section('stylesheets')
	@parent
	<link type="text/css" rel="stylesheet" href="{{asset('assets/css/pdp.css')}}" />
@stop

@section('scripts')
	@parent
	<script type="text/javascript" src="{{asset('assets/admin/js/persianDatepicker.min.js')}}"></script>
	    <script type="text/javascript">
    		$(function() {
				$("#todo_at").persianDatepicker({
				  cellWidth:30,
				  formatDate: "YYYY-0M-DD",
				  cellHeight:30
			  });
			});
        </script>
@stop