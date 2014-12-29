@extends('admin.layouts.default')
@section('title')
	@parent | ویرایش پوشه
@stop
@section('breadcrumb')
	@parent
	<li><a href="{{URL::route('FolderIndex')}}"><i class="fa fa-envelope"></i> بایگانی</a></li><li class="active"><i class="fa fa-folder"></i> ویرایش پوشه</li>
@stop
@section('content')
<div class="row">
	<div class="col-sm-12 col-lg-offset-4 col-lg-4 col-md-6 col-md-offset-2">
		<h3 class="text-center Nassim"> ویرایش پوشه {{$folder->name}}</h3>
		{{Form::open([
        	'url'        =>  Request::path(),
        	'method'        =>  'put'
        ])}}
        	<div class="form-group">
        		{{Form::label('item[name]','نام  گروه')}}
        		{{Form::text('item[name]',$folder->name , ['class' => 'form-control'])}}
        	</div>

        	<div class="form-group">
            		{{Form::label('item[parent_id]','مرجع')}}<br>
        			<select name="item[parent_id]" class="form-control">
        			    @if($folder->parent_id == 0)
        			        <option value="0">ریشه</option>
        			        {{ViewBuilder::FolderSelectView($folders)}}
        			    @else
        			        <option value="{{$folder->parent_id}}">{{$folder->parent()->first()->name}}</option>
	                        <option value="0">ریشه</option>
	                         {{ViewBuilder::FolderSelectView($folders)}}
                        @endif
                    </select>
            	</div>

        	{{Form::submit('ثبت',array('class' => 'btn btn-md btn-success radius Nassim'))}}
        {{Form::close()}}
	</div>
</div>
@stop