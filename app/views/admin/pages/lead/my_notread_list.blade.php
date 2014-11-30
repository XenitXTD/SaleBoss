@extends('admin.layouts.default')
@section('title')
	@parent | لیست لیدهای امروز {{$user->getIdentifier()}}
@stop

@section('breadcrumb')
	@parent
	<li class="active"><i class="fa fa-list"></i>  لیست لیدهای امروز {{$user->getIdentifier()}}
@stop

@section('intro')

@stop

@section('content')
  <div class="table-header Nassim Nassim700 NassimTitle panelColor" style="padding-right: 10px;" >
        <i class="fa fa-phone"></i> لیست لیدهای امروز
  </div>
<div class="table-responsive">
    <table class="table table-hover table-stripped my-lead-table tableFontSize12">
        <thead>
        <tr>
            <th><i class="fa fa-flag"></i> شناسه</th>
            <th><i class="fa fa-user"></i> نام شخص یا شرکت</th>
            <th class="text-center"><i class="fa fa-mobile"></i> شماره تماس</th>
            <th class="text-center"><i class="fa fa-tag"></i> زمینه فعالیت</th>
            <th><i class="fa fa-file-text"></i> توضیحات</th>
            <th class="text-center"><i class="fa fa-star"></i> اهمیت </th>
            <th>وضعیت</th>
        </tr>
        </thead>
        <tbody>
      @foreach($leads as $lead)
          <tr class="inline-form-tr" @if ($lead->new_lead === 1) style="background-color: #F4726D" @endif>
              <td>#{{$lead->id}}</td>
              <td>{{$lead->name}}</td>
              <td class="text-center" style="direction: ltr">{{$lead->phones->first()->number}}</td>
              <td class="text-center" style="direction: ltr">{{$lead->tags->first()->name}}</td>
              <td>{{empty($lead->description) ? 'ندارد' : softTrim($lead->description,50)}}</td>
              <td class="text-center">
                  @for($i=1;$i<=$lead->priority;$i++)
                      <i style="color:#CC9900" class="fa fa-star"></i>
                  @endfor
              </td>
              <td>
                  <span class="label arrowed label-<?php print statusClass($lead->status)?>">
                      {{$opiloConfig['lead_status'][$lead->status]}}
                  </span>
              </td>
          </tr>
      @endforeach
    </table>

@stop