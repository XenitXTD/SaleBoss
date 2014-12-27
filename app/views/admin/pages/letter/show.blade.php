@extends('admin.layouts.default')
@section('title')
	@parent | نمایش نامه
@stop
@section('breadcrumb')
	@parent
	<li><a href="{{URL::to('me/letters')}}"><i class="fa fa-envelope"></i> نامه ها</a></li><li class="active"><i class="fa fa-folder"></i> نمایش نامه</li>
@stop
@section('content')
@if($letter)
<div class="row">
	<div class="col-md-8 col-md-offset-2 col-sm-12 tableFontSize12">
		<div class="row padding10">
			<div class="col-md-2 align-left">
				شماره نامه:
			</div>
			<div class="col-md-10">
				{{$letter->letter_id}}
			</div>
		</div>
		<div class="row padding10">
			<div class="col-md-2 align-left">
				از:
			</div>
			<div class="col-md-10">
				{{$letter->startP->display_name}} ( {{$letter->creator->first_name}} {{$letter->creator->last_name}} )
			</div>
		</div>
		<div class="row padding10">
			<div class="col-md-2 align-left">
				به:
			</div>
			<div class="col-md-10">
				{{$letter->destinationP->display_name}}
			</div>
		</div>
		<div class="row padding10">
			<div class="col-md-2 align-left">
				تاریخ ارسال:
			</div>
			<div class="col-md-10">
				{{$letter->jalaliTimeDate('created_at')}}
			</div>
		</div>
		<div class="row padding10">
            <div class="col-md-2 align-left">
                موضوع:
            </div>
            <div class="col-md-10">
                {{$letter->subject}}
            </div>
        </div>
		<div class="row padding10">
			<div class="col-md-2 align-left">
				متن نامه
			</div>
			<div class="col-md-10 justifyright">
				{{$letter->message}}
			</div>
		</div>
		<div class="row padding10">
            <div class="col-md-2 align-left">
                فایل پیوست:
            </div>
            <div class="col-md-10 justifyright">
                @if($files)
                    @foreach($files as $file)
                        <div class="row"><a href="{{asset($file->path .'/'. $file->name)}}">{{$file->name}}</a></div>
                    @endforeach
                @endif
            </div>
        </div>

@if(Sentry::getUser()->getGroups()->first()->id == $letter->current_place)
		<div class="row padding10">
            <div class="col-md-2 align-left">
				عملیات
            </div>
            <div class="col-md-10">
                    @include('admin.pages.letter.partial._show_operation')
            </div>
        </div>
@endif
        <div class="row padding10">
            <div class="col-md-2 align-left">
                هامش:
            </div>
            <div class="col-md-10 justifyright">
            <div class="timeline-container timeline-style2">
                <div class="timeline-items">
		            @foreach($logs as $log)
		                    <div class="timeline-item clearfix">
		                        <div class="timeline-info">
		                            <span class="timeline-date">{{$log->jalaliTimeDate('created_at')}}</span>

		                            <i class="timeline-indicator btn btn-success"></i>
		                        </div>

		                        <div class="widget-box transparent">
		                            <div class="widget-body">
		                                <div class="widget-main no-padding">
		                                    <span class="bigger-110">
		                                        <span class="red bolder">{{$log->creator->first_name}} {{$log->creator->last_name}}</span><br>
		                                        {{$log->message}}
		                                    </span>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		            @endforeach
				</div>
            </div>
        </div>
	</div>
</div>

@else
	<div class="row">
		<div class="alert alert-danger">
		امکان نمایش این نامه برای شما وجود ندارد.
		</div>
	</div>
@endif

@stop