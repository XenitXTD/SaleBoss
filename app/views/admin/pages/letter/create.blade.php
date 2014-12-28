@extends('admin.layouts.default')
@section('title')
	@parent | ایجاد یک نامه جدید
@stop
@section('breadcrumb')
	@parent
	<li><a href="{{URL::to('me/letters')}}"><i class="fa fa-envelope"></i> نامه ها</a></li><li class="active"><i class="fa fa-plus"></i> ایجاد یک نامه جدید</li>
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
				دریافت کننده:
			</div>
			<div class="col-md-6">
				<div class="dd dd-nodrag">
					{{ViewBuilder::LetterDestinationTreeViewWithCheckBox($groups)}}
				</div>
			</div>
		</div>
		<div class="row padding10">
			<div class="col-md-2 align-left">
				پوشه بایگانی
			</div>
			<div class="col-md-10">
				<select name="item[folder]">
					{{ViewBuilder::FolderSelectView($folders['Group'])}}
				</select>
			</div>
		</div>
		<div class="row padding10">
			<div class="col-md-2 align-left">
				موضوع
			</div>
			<div class="col-md-10">
				{{Form::text('item[subject]',Input::old('item[subject]'), ['id' => 'subject'])}}
			</div>
		</div>
		<div class="row padding10">
			<div class="col-md-2 align-left">
				متن پیام:
			</div>
			<div class="col-md-7 justifyright">
				{{ Form::textarea('item[message]', Input::old('item[message]'), ['rows' => 6 ]) }}
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
        			    <a href="{{URL::route('MyLetterList')}}"><button type="button" class="btn btn-xs margin-right operation-margin"><i class="ace-icon fa fa-arrow-right icon-on-right"></i> بازگشت</button></a>
        				<button class="btn btn-xs btn-success" type="submit"><i class="ace-icon fa fa-check bigger-120"></i> ارسال</button>
        			</div>
        		</div>
		{{Form::close()}}
	</div>

</div>
@stop