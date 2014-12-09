<div class="hidden-sm hidden-xs btn-group">
	<button
		type="button"
		class="btn btn-xs pull-left margin-right btn-danger operation-margin"
		delete-url="{{URL::to('me/leads/' . $lead->id)}}"
		onclick="Common.setDeleteURL(this,'#delete_form')"
		data-toggle="modal"
		data-target="#removeModal"><i class="fa fa-trash"></i>
	</button>
	<button
		type="button"
		class="btn btn-xs pull-left btn-warning operation-margin"
		id="{{$lead->name}}"
		name="{{$lead->name}}"
		tag="{{$lead->tags->last()->id}}"
		phone="{{$lead->phones->first()->number}}"
		status="{{$lead->status}}"
		remind_at="{{$lead->remind_at}}"
		description="{{$lead->description}}"
		priority="{{$lead->priority}}"
		update-url="{{URL::to('me/leads/' . $lead->id)}}"
		onclick="Common.setUpdateURL(this, '#update_form',leadUpdateClosure(this))"
		data-toggle="modal"
		data-target="#updateModal">
		<i class="fa fa-pencil-square-o"></i>
	</button>
	<a target="_blank" href="{{URL::to('customers/create?lead_id='. $lead->id)}}" class="btn btn-info btn-xs"><i class="fa fa-user"></i></a>
</div>


