@extends('admin.layouts.default')
@section('title')
	@parent |لیست لیدهای
@stop

@section('breadcrumb')
	@parent
	<li class="active"><i class="fa fa-list"></i> لیست لیدهای
@stop

@section('intro')

@stop

@section('content')
<div class="panel">
	<div class="panel-body">
		<div class="row" style="margin-right: 5px; margin-bottom: 15px">
			<div class="col-md-12 Nassim700">
                <a href="{{URL::to('leads/bulk')}}" class="btn btn-success btn-lg btn-block radius Nassim">وارد نمودن انبوه لید</a>
            </div>
		</div>
	</div>
</div>
<div class="table-header Nassim Nassim700 NassimTitle panelColor" style="padding-right: 10px;" >
                <i class="fa fa-list"></i> لیست تمام لیدها
          </div>
<div class="table-responsive">
    <table class="table table-hover table-stripped my-lead-table tableFontSize12">
        <thead>
        <tr>
            <th style="padding: 13px; width: 90px"><i class="fa fa-flag"></i> شناسه</th>
            <th>ثبت کننده</th>
            <th><i class="fa fa-user"></i> نام شخص یا شرکت</th>
            <th class="text-center"><i class="fa fa-mobile"></i> شماره تماس</th>
            <th class="text-center"><i class="fa fa-tag"></i> زمینه فعالیت</th>
            {{--<th class="text-center"><i class="fa fa-star"></i> اهمیت </th>--}}
            <th>وضعیت</th>
            <th>تاریخ ثبت</th>
            {{--<th>به یاد آوری در</th>--}}
        </tr>
        </thead>
        <tbody>
       @foreach($leads as $lead)
          <tr class="inline-form-tr">
              <td>#{{$lead->id}}</td>
              <td><a href="{{URL::to('leads/user/' . $lead->creator->id)}}" target="_blank"><span class="label panelColor radius">{{$lead->creator->first_name}} {{$lead->creator->last_name}}</span></a></td>
              <td>{{$lead->name}}</td>
              <td class="text-center" style="direction: ltr">{{$lead->phones->first()->number}}</td>
              <td class="text-center" style="direction: ltr">{{$lead->tags->first()->name}}</td>
              {{--<td class="text-center">--}}
                  {{--@for($i=1;$i<=$lead->priority;$i++)--}}
                      {{--<i style="color:#CC9900" class="fa fa-star"></i>--}}
                  {{--@endfor--}}
              {{--</td>--}}
              <td>
                  <span class="label arrowed label-<?php print statusClass($lead->status)?>">
                      {{$opiloConfig['lead_status'][$lead->status]}}
                  </span>
              </td>
              <td>{{$lead->jalaliDate('created_at')}}</td>
              {{--<td>--}}
                  {{--@if(!is_null($lead->remind_at))--}}
                      {{--<i class="fa fa-calendar"></i> {{$lead->jalaliDate('remind_at')}} ({{$lead->jalaliAgoDate('remind_at')}})--}}
                  {{--@else--}}
                    {{--ندارد--}}
                  {{--@endif--}}
              {{--</td>--}}
          </tr>
      @endforeach
    </table>

    <div class="row">
    	{{$leads->appends(Input::except('page'))->links()}}
    </div>

@stop


@section('footer')
    @include('admin.blocks.delete_modal')
    @include('admin.blocks.update_modal')
    @parent
@stop