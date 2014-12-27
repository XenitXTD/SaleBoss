@extends('admin.layouts.default')
@section('title')
	@parent | بایگانی
@stop
@section('breadcrumb')
	@parent
	<li class="active"><i class="fa fa-plus"></i> بایگانی {{$folder->name}}</li>
@stop
@section('content')
<div class="table-header Nassim Nassim700 NassimTitle panelColor" style="padding-right: 10px;">
                <i class="fa fa-list"></i> لیست تمام نامه های بایگانی {{$folder->name}}
          </div>
	<div class="table-responsive">
        <table class="table table-striped table-bordered table-hover dataTable no-footertableFontSize12">
            <thead>
                <tr>
                    <th>شماره نامه</th>
                    <th>موضوع نامه</th>
                    <th>ارسال کننده</th>
                    <th>دریافت کننده</th>
                    <th>تاریخ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($letters as $letter)
                    <tr>
                        <td>{{$letter->id}}</td>
                        <td><a target="_blank" href="{{URL::to('letters').'/'.$letter->id.'/to/'.$letter->destination}}"><span class="label panelColor radius">{{$letter->subject}}</span></a></td>
                        <td>
                           {{$letter->startP->display_name}}
                        </td>
                        <td>
                           {{$letter->destinationP->display_name}}
                        </td>
                        <td>
                           {{$letter->jalaliDate('created_at')}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="row">
            {{$letters->appends(Input::except('page'))->links()}}
        </div>
    </div>

@stop