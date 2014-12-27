@extends('admin.layouts.default')
@section('title')
	@parent | نمایش وظیفه
@stop
@section('breadcrumb')
	@parent
	<li><a href="{{URL::to('folder')}}"><i class="fa fa-envelope"></i> بایگانی</a></li><li class="active"><i class="fa fa-folder"></i> نمایش آیتم</li>
@stop
@section('content')
@if($item)
<div class="row">
	<div class="col-md-8 col-md-offset-2 col-sm-12 tableFontSize12">
		<div class="row padding10">
			<div class="col-md-2 align-left">
				شماره:
			</div>
			<div class="col-md-10">
				{{$item->id}}
			</div>
		</div>
		<div class="row padding10">
			<div class="col-md-2 align-left">
				توسط:
			</div>
			<div class="col-md-10">
				{{$item->creator->first_name}} {{$item->creator->last_name}}
			</div>
		</div>
		<div class="row padding10">
			<div class="col-md-2 align-left">
				نام:
			</div>
			<div class="col-md-10">
				{{$item->name}}
			</div>
		</div>
		<div class="row padding10">
			<div class="col-md-2 align-left">
				تاریخ ثبت:
			</div>
			<div class="col-md-10">
				{{$item->jalaliTimeDate('created_at')}}
			</div>
		</div>
		<div class="row padding10">
			<div class="col-md-2 align-left">
				توضیحات
			</div>
			<div class="col-md-10 justifyright">
				{{$item->description}}
			</div>
		</div>
		<div class="row padding10">
            <div class="col-md-2 align-left">
                فایل پیوست:
            </div>
            <div class="col-md-10 justifyright">
                @if(!empty($item->file->first()->name))<a href="{{asset($item->file->first()->path .'/'. $item->file->first()->name)}}">{{$item->file->first()->name}}</a>@endif
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