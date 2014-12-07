<!--Message Modal -->
<div class="modal slide-down" id="messageModal" tabindex="-1" style="overflow-y:hidden;" role="dialog" aria-labelledby="messageModal" aria-hidden="true">
  <div class="modal-dialog .modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title Nassim Nassim700" id="myModalLabel">ارسال پیام </h4>
      </div>
      <div class="modal-body">
      	<p></p>
      </div>
      <div class="modal-footer">
		<button type="button" class="btn btn-sm btn-warning pull-left radius" style="margin-right: 10px;" onclick="Common.submitUpdateForm('#message_form')">ارسال پیام</button>
        <button type="button" class="btn btn-sm btn-default pull-left radius" data-dismiss="modal">بستن</button>
      </div>
    </div>
  </div>
</div>

<script type="text/template" class="lead-update-modal-form">
	{{Form::open(array(
    	'url'			=>	"#",
    	'method'		=>	'put',
    	'id'			=>	'message_form',
    	'class'			=>	'messageModal'
    ))}}
    	<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-12">
				<label class="control-label Nassim Nassim700 NassimTitle">نام یا شماره شرکت</label>
				<input name="name" type="text" class="form-control bulkable resettable" placeholder="نام شخص یا شرکت" value="<%= name %>">
			</div>
			<div class="col-lg-6 col-sm-12">
				<label class="control-label Nassim Nassim700 NassimTitle">شماره تماس</label>
				<input name="phone" type="text" class="form-control languageLeft bulkable resettable" value="<%= phone %>">
			</div>
		</div>
		<br>
		<div class="row">
    		<div class="col-lg-6 col-md-6 col-sm-12">
    			<label class="control-label Nassim Nassim700 NassimTitle">تاریخ به یاد آوری</label>
    			<input name="remind_at" class="form-control" type="text" placeholder="به یاد آوری در چند روز بعد؟">
    		</div>
		</div>
		<br>
	{{Form::close()}}
</script>
