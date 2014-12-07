@extends('admin.layouts.default')
@section('title')
	@parent | ویرایش وظیفه
@stop
@section('breadcrumb')
	@parent
	<li><a href="{{URL::to('task')}}"><i class="fa fa-envelope"></i> وظایف</a></li><li class="active"><i class="fa fa-edit"></i> ویرایش وظیفه</li>
@stop
@section('content')
@if($task)
<div class="row">

	<div class="col-md-8 col-md-offset-2 col-sm-12 tableFontSize12">
    {{Form::open(array(
                    'url'			=>	Request::path(),
                    'method'		=>	'put'
                ))}}
		<div class="row padding10">
			<div class="col-md-2 align-left">
				شماره:
			</div>
			<div class="col-md-10">
				{{$task->id}}
			</div>
		</div>
		<div class="row padding10">
			<div class="col-md-2 align-left">
				از طرف:
			</div>
			<div class="col-md-10">
				{{$task->creator->first_name}} {{$task->creator->last_name}}
			</div>
		</div>
		<div class="row padding10">
			<div class="col-md-2 align-left">
				تاریخ ارسال:
			</div>
			<div class="col-md-10">
				{{$task->diff_create_at()}}
			</div>
		</div>
		<div class="row padding10">
			<div class="col-md-2 align-left">
				تاریخ اجرا:
			</div>
			<div class="col-md-10">
				{{Form::text('todo_at',$task->jalaliDateEnglishNumbers('todo_at'),['id' => 'todo_at'])}}
			</div>
		</div>
		<div class="row padding10">
	        <div class="col-md-2 align-left">
	            وضعیت:
	        </div>
	        <div class="col-md-10">
	            {{Form::select( 'status',['0'=>'انجام نشده','1'=>'انجام شده','2'=>' بسته شده'], $task->status)}}
	        </div>
	    </div>
		<div class="row padding10">
	        <div class="col-md-2 align-left">
	            گروه وظایف:
	        </div>
	        <div class="col-md-10">
	            {{Form::select('category',SaleBoss\Models\TaskCategory::getCategoryList(),$task->cat->id)}}
	        </div>
	    </div>
	    <div class="row padding10">
            <div class="col-md-2 align-left">
                درجه اهمیت:
            </div>
            <div class="col-md-10">
                {{Form::select( 'priority',['1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5'], $task->priority)}}
            </div>
        </div>
		<div class="row padding10">
			<div class="col-md-2 align-left">
				شرح وظیفه:
			</div>
			<div class="col-md-6 justifyright">
				<textarea class="form-control" name="description" rows="5">{{$task->description}}</textarea>
			</div>
		</div>
		<input type="hidden" name="for_id" value="{{$task->for_id}}">
		<div class="row padding10">
        			<div class="col-md-2 align-left">

        			</div>
        			<div class="col-md-6 justifyright">
        			    <a href="{{URL::to('task/'. $task->id)}}"><button type="button" class="btn btn-xs margin-right operation-margin"><i class="ace-icon fa fa-arrow-right icon-on-right"></i> بازگشت</button></a>
        				<button class="btn btn-xs btn-info" type="submit"><i class="ace-icon fa fa-edit bigger-120"></i> ویرایش</button>
        			</div>
        		</div>
		{{Form::close()}}
	</div>

</div>

@else
	<div class="row">
		<div class="alert alert-danger">
		امکان ویرایش این وظیفه برای شما وجود ندارد
		</div>
	</div>
@endif

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