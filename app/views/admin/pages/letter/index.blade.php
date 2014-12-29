@extends('admin.layouts.default')
@section('title')
	@parent | نامه ها
@stop
@section('breadcrumb')
	@parent
	<li class="active"><i class="fa fa-plus"></i>  نامه های من</li>
@stop
@section('content')

<div class="well">
    <h4 class="Nassim Nassim700"><strong>همیشه جستجو کنید!</strong></h4>
    <div class="row">
            {{Form::open(array(
                    'url'			=>	URL::route('LetterSearchList'),
                    'method'		=>	'post'
                ))}}
             <input type="hidden" name="destination" value="{{$userGroupId}}">
        <div class="col-sm-12 col-md-6">
            <div class="col-sm-12 col-md-4 form-group">
                <label class="control-label Nassim NassimTitle">شماره نامه</label>
                {{Form::text('id',Input::get('id'),['class' => 'form-control'])}}
            </div>
            <div class="col-sm-12 col-md-4 form-group">
                <label class="control-label Nassim NassimTitle">موضوع</label>
                {{Form::text('subject',Input::get('subject'),['class' => 'form-control'])}}
            </div>
            <div class="col-sm-12 col-md-4 form-group">
                <label class="control-label Nassim NassimTitle">متن</label>
                {{Form::text('message',Input::get('message'),['class' => 'form-control'])}}
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="col-sm-12 col-md-6">
                <a href="{{URL::to(Request::path())}}" class="btn btn-default btn-lg btn-block radius Nassim">ریست کن!</a>
            </div>
            <div class="col-sm-12 col-md-6">
                {{Form::submit('بگرد!',['class' => 'btn btn-info btn-block btn-lg radius Nassim'])}}
            </div>
        </div>
        {{Form::close()}}
    </div>
</div>

<div class="table-header Nassim Nassim700 NassimTitle panelColor" style="padding-right: 10px;">
                <i class="fa fa-list"></i> نامه های من
          </div>
	<div class="table-responsive">
        <table class="table table-striped table-bordered table-hover dataTable no-footertableFontSize12">
            <thead>
                <tr>
                    <th>شماره نامه</th>
                    <th>موضوع نامه</th>
                    <th>ارسال کننده</th>
                    <th>پوشه بایگانی</th>
                    <th>تاریخ ارسال</th>
                </tr>
            </thead>
            <tbody>
                @foreach($letters as $letter)
                    <tr>
                        <td>{{$letter->id}}</td>
                        <td><a target="_blank" href="{{URL::to('letters').'/'.$letter->id.'/to/'.$letter->destination}}"><span class="label panelColor radius">{{$letter->subject}}</span></a></td>
                        <td>
                           {{$letter->path()->first()->startP->display_name}}
                        </td>
                        <td>
                           @if(!is_null($letter->folder_id)){{$letter->folder()->first()->name}} @else
                            از بایگانی خارج شده است
                            @endif
                        </td>
                        <td>
                           {{$letter->jalaliTimeDate('created_at')}}
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