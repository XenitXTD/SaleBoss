@extends('admin.layouts.default')
@section('title')
	@parent | نمایش وظیفه
@stop
@section('breadcrumb')
	@parent
	<li><a href="{{URL::to('task')}}"><i class="fa fa-envelope"></i> وظایف</a></li><li class="active"><i class="fa fa-folder"></i> ویرایش وظیفه</li>
@stop
@section('content')
@if($task)
<div class="row">
	<div class="col-md-8 col-md-offset-2 col-sm-12 tableFontSize12">
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
				{{$task->jalaliDate('todo_at')}}
			</div>
		</div>
		<div class="row padding10">
	        <div class="col-md-2 align-left">
	            وضعیت:
	        </div>
	        <div class="col-md-10">
	            @if($task->status == 0)
	                انجام نشده
	            @elseif($task->status == 1)
	                انجام شده
	            @elseif($task->status == 2)
	                بسته شده
	            @endif
	        </div>
	    </div>
		<div class="row padding10">
	        <div class="col-md-2 align-left">
	            گروه وظایف:
	        </div>
	        <div class="col-md-10">
	            {{$task->cat->name}}
	        </div>
	    </div>
	    <div class="row padding10">
            <div class="col-md-2 align-left">
                درجه اهمیت:
            </div>
            <div class="col-md-10">
                @for($i=1;$i<=$task->priority;$i++)
	                  <i style="color:#CC9900" class="fa fa-star"></i>
	              @endfor
            </div>
        </div>
		<div class="row padding10">
			<div class="col-md-2 align-left">
				شرح وظیفه:
			</div>
			<div class="col-md-10 justifyright">
				{{$task->description}}
			</div>
		</div>
		<div class="row padding10">
            <div class="col-md-2 align-left">
                فایل پیوست:
            </div>
            <div class="col-md-10 justifyright">
                @if(!empty($task->file->first()->name))<a href="{{asset($task->file->first()->path .'/'. $task->file->first()->name)}}">{{$task->file->first()->name}}</a>@endif
            </div>
        </div>
		<div class="row padding10">
            <div class="col-md-2 align-left">
				عملیات
            </div>
            <div class="col-md-10">
                    @include('admin.pages.tasks.partial._show_operation')
            </div>
        </div>
        <div class="row padding10">
            <div class="col-md-2 align-left">
                شرح مکالمه:
            </div>
            <div class="col-md-10 justifyright">
            <div class="timeline-container timeline-style2">
                <div class="timeline-items">
		            @foreach($task->message as $message)
		                    <div class="timeline-item clearfix">
		                        <div class="timeline-info">
		                            <span class="timeline-date">{{$message->jalaliTimeDate('created_at')}}</span>

		                            <i class="timeline-indicator btn btn-success"  @if($message->read) style="background-color: #87B87F !important" title="خوانده شده" @endif></i>
		                        </div>

		                        <div class="widget-box transparent">
		                            <div class="widget-body">
		                                <div class="widget-main no-padding">
		                                    <span class="bigger-110">
		                                        <span class="red bolder">{{$message->creator->first_name}} {{$message->creator->last_name}}</span><br>
		                                        {{$message->message}}
		                                    </span>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		            @endforeach
				</div>
            </div>
            		            <div class="row">
            		               	<button
                                   		type="button"
                                   		class="btn btn-xs margin-right btn-info operation-margin"
                                   		delete-url="{{URL::to('task/' . $task->id)}}"
                                   		onclick="Common.setDeleteURL(this,'#message_form')"
                                   		data-toggle="modal"
                                   		data-target="#messageModal"><i class="ace-icon fa fa-reply icon-only"></i> ارسال پیام
                                   	</button>
                                </div>
        </div>
	</div>
</div>

<!--Message Modal -->
<div class="modal slide-down" id="messageModal" tabindex="-1" style="overflow-y:hidden;" role="dialog" aria-labelledby="messageModal" aria-hidden="true">
  <div class="modal-dialog .modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title Nassim Nassim700" id="myModalLabel">ارسال پیام </h4>
      </div>
      <div class="modal-body">
      	{{Form::open(array(
            	'url'			=>	"#",
            	'method'		=>	'put',
            	'id'			=>	'message_form',
            	'class'			=>	'message_form'
            ))}}
                <input type="hidden" name="taskId" value="{{$task->id}}">
                <input type="hidden" name="for_id" value="@if($user->getId() == $task->creator_id){{$task->for_id}}@else{{$task->creator_id}}@endif">
        		<div class="row">
            		<div class="col-lg-12 col-md-12 col-sm-12">
            			<label class="control-label Nassim Nassim700 NassimTitle">متن پیام: </label>
            			<textarea class="form-control" name="message" rows="10"></textarea>
            		</div>
        		</div>
        		<br>
        	{{Form::close()}}
      </div>
      <div class="modal-footer">
		<button type="button" class="btn btn-sm btn-warning pull-left radius" style="margin-right: 10px;" onclick="Common.submitUpdateForm('#message_form')">ارسال پیام</button>
        <button type="button" class="btn btn-sm btn-default pull-left radius" data-dismiss="modal">بستن</button>
      </div>
    </div>
  </div>
</div>
@else
	<div class="row">
		<div class="alert alert-danger">
		امکان نمایش این وظیفه برای شما وجود ندارد.
		</div>
	</div>
@endif

@stop