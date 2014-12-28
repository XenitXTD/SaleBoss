@extends('admin.layouts.default')
@section('title')
	@parent | ایجاد یک آیتم بایگانی جدید
@stop
@section('breadcrumb')
	@parent
	<li><a href="{{URL::to('folders')}}"><i class="fa fa-envelope"></i> پوشه ها</a></li><li class="active"><i class="fa fa-plus"></i> ایجاد یک آیتم بایگانی جدید</li>
@stop
@section('content')
<div class="row">

	<div class="col-md-8 col-md-offset-2 col-sm-12 tableFontSize12">
        {{Form::open(array(
                    'url'			=>	Request::path(),
                    'method'		=>	'put',
                    'files'         =>  true
                ))}}
		<div class="row padding10">
			<div class="col-md-2 align-left">
				نام:
			</div>
			<div class="col-md-10">
				{{Form::text('item[name]',Input::old('item[name]'), ['id' => 'subject'])}}
			</div>
		</div>
		<div class="row padding10">
			<div class="col-md-2 align-left">
				پوشه بایگانی
			</div>
			<div class="col-md-6">
				<div class="dd dd-nodrag">
					{{ViewBuilder::FolderTreeViewWithCheckBox($folders['User'])}}
				</div>
			</div>
		</div>
		<div class="row padding10">
			<div class="col-md-2 align-left">
				توضیحات
			</div>
			<div class="col-md-7 justifyright">
				{{ Form::textarea('item[description]', Input::old('item[description]'), ['rows' => 6 ]) }}
			</div>
		</div>
		<div class="row padding10">
            <div class="col-md-2 align-left">
                فایل:
            </div>
            <div class="col-md-4 justifyright">
                <label class="ace-file-input">
                    <input name="file" type="file" id="inputFile">
                        <span class="ace-file-container" data-title="Choose">
                            <span class="ace-file-name" data-title="Change">
                            <i class="ace-icon fa fa-upload"></i>
                            </span>
                        </span>
                        <a class="remove" href="#">
                        <i class=" ace-icon fa fa-times"></i>
                        </a>
                </label>
            </div>
        </div>
		<div class="row padding10">
        			<div class="col-md-2 align-left">
        			</div>
        			<div class="col-md-6 justifyright">
        				<button class="btn btn-xs btn-success" type="submit"><i class="ace-icon fa fa-check bigger-120"></i>ایجاد</button>
        			</div>
        		</div>
		{{Form::close()}}
	</div>
</div>
@stop