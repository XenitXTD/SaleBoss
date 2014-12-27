@extends('admin.layouts.default')
@section('title')
	@parent | بایگانی
@stop
@section('breadcrumb')
	@parent
	<li class="active"><i class="fa fa-plus"></i> بایگانی</li>
@stop
@section('content')

<div class="well">
    <h4 class="Nassim Nassim700"><strong>همیشه جستجو کنید!</strong></h4>
    <div class="row">
            {{Form::open(array(
                    'url'			=>	URL::route('FolderItemSearchList'),
                    'method'		=>	'post'
                ))}}
           @if(!Input::get('folderId'))
                <input type="hidden" name="for_id" value="{{$folderId}}">
           @endif
        <div class="col-sm-12 col-md-6">
            <div class="col-sm-12 col-md-4 form-group">
                <label class="control-label Nassim NassimTitle">شمارنده</label>
                {{Form::text('id',Input::get('id'),['class' => 'form-control'])}}
            </div>
            <div class="col-sm-12 col-md-4 form-group">
                <label class="control-label Nassim NassimTitle">نام</label>
                {{Form::text('name',Input::get('name'),['class' => 'form-control'])}}
            </div>
            <div class="col-sm-12 col-md-4 form-group">
                <label class="control-label Nassim NassimTitle">توضیحات</label>
                {{Form::text('description',Input::get('description'),['class' => 'form-control'])}}
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
                <i class="fa fa-list"></i> لیست آیتم ها
          </div>
	<div class="table-responsive">
        <table class="table table-striped table-bordered table-hover dataTable no-footertableFontSize12">
            <thead>
                <tr>
                    <th>شمارنده</th>
                    <th>نام</th>
                    <th>توضیحات</th>
                    <th>تاریخ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{$item->id}}</td>
                        <td><a target="_blank" href="{{URL::to('folder/item').'/'.$item->id}}"><span class="label panelColor radius">{{$item->name}}</span></a></td>
                        <td>
                            {{softTrim($item->description, 50)}}
                        </td>
                        <td>
                           {{$item->jalaliDate('created_at')}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@stop