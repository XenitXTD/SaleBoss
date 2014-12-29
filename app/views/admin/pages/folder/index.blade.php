@extends('admin.layouts.default')
@section('title')
	@parent | بایگانی
@stop
@section('breadcrumb')
	@parent
	<li class="active"><i class="fa fa-plus"></i> بایگانی</li>
@stop
@section('content')

<div class="col-md-6">
	<div class="widget-box ui-sortable-handle">
		<div class="widget-header">
			<h5 class="widget-title Nassim NassimTitle Nassim700">بایگانی سازمانی</h5>
			<div class="widget-toolbar">
                    <button
                                    type="button"
                                    class="btn btn-xs margin-right btn-info"
                                    delete-url="{{URL::route('FolderCreate')}}"
                                    onclick="Common.setDeleteURL(this,'#add_group_form')"
                                    data-toggle="modal"
                                    data-target="#groupModal"><i class="ace-icon fa fa-folder icon-only"></i> ایجاد پوشه جدید
                                </button>
                    <!--Message Modal -->
                            <div class="modal slide-down" id="groupModal" tabindex="-1" style="overflow-y:hidden;" role="dialog" aria-labelledby="groupModal" aria-hidden="true">
                              <div class="modal-dialog .modal-lg">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title Nassim Nassim700" id="myModalLabel">ایجاد پوشه جدید</h4>
                                  </div>
                                  <div class="modal-body">
                                            {{Form::open(array(
                                                    'url'			=>	'#',
                                                    'method'		=>	'put',
                                                    'id'			=>	'add_group_form',
                                                    'class'			=>	'add_group_form'
                                                ))}}
                                               <input type="hidden" name="item[for_id]" value="{{$userGroupId}}">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label class="control-label Nassim Nassim700 NassimTitle">نام پوشه:</label>
                                                    {{Form::text('item[name]',Input::old('item[name]'), ['id' => 'subject', 'class' => 'input-sm'])}}
                                                </div>
                                            </div>
											<div class="row">
                                                <div class="col-md-12">
                                                    <label class="control-label Nassim Nassim700 NassimTitle">مرجع: </label>
                                                    <input type="hidden" name="item[for_type]" value="SaleBoss\Models\Group">
                                                    <select name="item[parent_id]">
                                                         {{ViewBuilder::FolderSelectView($groupFolders)}}
                                                    </select>
{{--                                                    {{Form::select('item[parent_id]',SaleBoss\Models\Folder::getFolderList('SaleBoss\Models\Group', $userGroupId),['class' => 'form-control'])}}--}}
                                                </div>
                                            </div>
                                            <br>
                                        {{Form::close()}}
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-sm btn-warning pull-left radius" style="margin-right: 10px;" onclick="Common.submitUpdateForm('#add_group_form')">ایجاد</button>
                                    <button type="button" class="btn btn-sm btn-default pull-left radius" data-dismiss="modal">بستن</button>
                                  </div>
                                </div>
                              </div>
                            </div>
            </div>
            <div class="widget-toolbar no-border">
                    <a class="btn btn-xs btn-success tableFontSize12" href="{{URL::route('LetterSearchList')}}"> جتسجو</a>
            </div>

		</div>

		<div class="widget-body">
			<div class="widget-main">
				<div class="dd dd-nodrag">
					{{ViewBuilder::FolderTreeView($folders['Group'])}}
	            </div>
			</div>
		</div>
	</div>
</div>

<div class="col-md-6">
	<div class="widget-box ui-sortable-handle">
		<div class="widget-header">
			<h5 class="widget-title Nassim NassimTitle Nassim700">بایگانی شخصی</h5>
			<div class="widget-toolbar">
                                <button
                                    type="button"
                                    class="btn btn-xs margin-right btn-info"
                                    delete-url="{{URL::route('FolderCreate')}}"
                                    onclick="Common.setDeleteURL(this,'#add_user_form')"
                                    data-toggle="modal"
                                    data-target="#userModal"><i class="ace-icon fa fa-folder icon-only"></i> ایجاد پوشه جدید
                                </button>
                                <!--Message Modal -->
                                        <div class="modal slide-down" id="userModal" tabindex="-1" style="overflow-y:hidden;" role="dialog" aria-labelledby="userModal" aria-hidden="true">
                                          <div class="modal-dialog .modal-lg">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title Nassim Nassim700" id="myModalLabel">ایجاد پوشه جدید</h4>
                                              </div>
                                              <div class="modal-body">
                                                        {{Form::open(array(
                                                                'url'			=>	'#',
                                                                'method'		=>	'put',
                                                                'id'			=>	'add_user_form',
                                                                'class'			=>	'add_user_form'
                                                            ))}}
                                                             <input type="hidden" name="item[for_id]" value="{{$userId}}">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label class="control-label Nassim Nassim700 NassimTitle">نام پوشه:</label>
                                                                {{Form::text('item[name]',Input::old('item[name]'), ['id' => 'subject', 'class' => 'input-sm'])}}
                                                            </div>
                                                        </div>
            											<div class="row">
                                                            <div class="col-md-12">
                                                                <label class="control-label Nassim Nassim700 NassimTitle">مرجع: </label>
                                                                <input type="hidden" name="item[for_type]" value="SaleBoss\Models\User">
                                                                <select name="item[parent_id]">
                                                                     {{ViewBuilder::FolderSelectView($userFolders)}}
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <br>
                                                    {{Form::close()}}
                                              </div>
                                              <div class="modal-footer">
                                                <button type="button" class="btn btn-sm btn-warning pull-left radius" style="margin-right: 10px;" onclick="Common.submitUpdateForm('#add_user_form')">ایجاد</button>
                                                <button type="button" class="btn btn-sm btn-default pull-left radius" data-dismiss="modal">بستن</button>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
            </div>
            <div class="widget-toolbar no-border">

                    <a class="btn btn-xs btn-yellow tableFontSize12" href="{{URL::route('FolderItemCreate')}}"> ایجاد یک بایگانی جدید</a>
                    <a class="btn btn-xs btn-success tableFontSize12" href="{{URL::route('FolderItemSearchList')}}"> جتسجو</a>

            </div>
		</div>

		<div class="widget-body">
			<div class="widget-main">
				<div class="dd dd-nodrag">
	              {{ViewBuilder::FolderUserTreeView($folders['User'])}}
	            </div>
			</div>
		</div>
	</div>
</div>

@stop