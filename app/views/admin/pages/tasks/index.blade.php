@extends('admin.layouts.default')
@section('title')
	@parent | لیست وظایف
@stop
@section('breadcrumb')
	@parent
	<li class="active"><i class="fa fa-plus"></i> لیست وظایف</li>
@stop
@section('content')
<div class="row" style="margin-top: 30px">
<div class="col-md-8 col-md-offset-2 col-sm-12">
<div class="tabbable">
	<ul class="nav nav-tabs" id="myTab">
		<li class="active">
			<a data-toggle="tab" href="#recieve">
				<i class="green ace-icon fa fa-envelope bigger-120"></i>
				وظایف دریافتی
			</a>
		</li>
		@if(Sentry::getUser()->getGroups()->first()->name == 'admin')
			<li class="">
				<a data-toggle="tab" href="#sent">
	                <i class="red ace-icon fa fa-send bigger-120"></i>
	                وظایف ارسالی
	            </a>
			</li>

			<li class="">
                <a href="{{URL::to('task/create')}}" target="_blank">
                    <i class="blue ace-icon fa fa-plus-square bigger-120"></i>
                   ایجاد یک وظیفه جدید
                </a>
            </li>
		@endif
	</ul>

	<div class="tab-content">
		<div id="recieve" class="tab-pane fade active in">
			<div class="table-responsive">
                <table class="table table-hover table-bordered table-striped tableFontSize12">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th class="languageLeft">از طرف</th>
                            <th>شرح وظیفه</th>
                            <th>تاریخ ارسال</th>
                            <th>تاریخ اجرا</th>
                            <th>درجه اهمیت</th>
                            <th class="languageLeft">عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tasks['forMe'] as $task)
                            <tr @if($task->status == 0) class="warning" @elseif($task->status == 1) class="info" @else class="success" @endif>
                                <td>{{$task->id}}</td>
                                <td class="languageLeft">{{$task->creator->first_name}} {{$task->creator->last_name}}</td>
                                <td>{{empty($task->description) ? 'ندارد' : softTrim($task->description,50)}}</td>
                                <td>{{$task->diff_create_at()}}</td>
                                <td>{{$task->jalaliDate('todo_at')}}</td>
                                <td>
                                    @for($i=1;$i<=$task->priority;$i++)
                                          <i style="color:#CC9900" class="fa fa-star"></i>
                                      @endfor
                                </td>
                                <td>@include('admin.pages.tasks.partial._operation')</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

		</div>

		<div id="sent" class="tab-pane fade">
			    @if(Sentry::getUser()->getGroups()->first()->name == 'admin')
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-striped tableFontSize12">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="languageLeft">دریافت کننده</th>
                                    <th>شرح وظیفه</th>
                                    <th>تاریخ ارسال</th>
                                    <th>تاریخ اجرا</th>
                                    <th>درجه اهمیت</th>
                                    <th class="languageLeft">عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tasks['byMe'] as $task)
                                    <tr @if($task->status == 0) class="warning" @elseif($task->status == 1) class="info" @else class="success" @endif>
                                        <td>{{$task->id}}</td>
                                        <td class="languageLeft">{{$task->forWhom->first_name}} {{$task->forWhom->last_name}}</td>
                                        <td>{{empty($task->description) ? 'ندارد' : softTrim($task->description,50)}}</td>
                                        <td>{{$task->diff_create_at()}}</td>
                                        <td>{{$task->jalaliDate('todo_at')}}</td>
                                        <td>
                                            @for($i=1;$i<=$task->priority;$i++)
                                                  <i style="color:#CC9900" class="fa fa-star"></i>
                                              @endfor
                                        </td>
                                        <td>@include('admin.pages.tasks.partial._operation')</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                @endif

		</div>
	</div>
</div>
</div>
@stop