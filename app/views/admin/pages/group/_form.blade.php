{{Form::open([
	'url'        =>  empty($update) ? 'groups' : 'groups/' . $group->id,
	'method'        =>  empty($update) ? 'post' : 'put'
])}}
	<div class="form-group">
		{{Form::label('item[display_name]','نام  گروه')}}
		{{Form::text('item[display_name]',!empty($update) ? $group->display_name : null , ['class' => 'form-control'])}}
	</div>

	<div class="form-group">
		{{Form::label('item[name]','نام ماشینی گروه')}}
		{{Form::text('item[name]',!empty($update) ? $group->name : null , ['class' => 'form-control languageLeft'])}}
	</div>

	<div class="form-group">
    		{{Form::label('item[parent_id]','مرجع')}}<br>
			<select name="item[parent_id]" class="form-control">
				<option value="0">ریشه</option>
                {{ViewBuilder::GroupSelectView($groups)}}
            </select>
    	</div>

	{{Form::submit('ثبت',array('class' => 'btn btn-md btn-success radius Nassim'))}}
{{Form::close()}}