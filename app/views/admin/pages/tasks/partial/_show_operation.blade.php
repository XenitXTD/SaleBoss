@if(Sentry::getUser()->getGroups()->first()->name == 'admin')

    {{Form::open(array(
                    'url'			=>	URL::to('task/action/' . $task->id),
                    'method'		=>	'put',
                    'style'         =>  'float: right; margin-left: 10px;'
                ))}}
                <input type="hidden" name="taskId" value="{{$task->id}}">
                <input type="hidden" name="actionType" value="2">
                <button class="btn btn-xs btn-danger" type="submit" alt="بستن وظیفه" title="بستن وظیفه"><i class="ace-icon fa fa-close bigger-120"></i> بستن وظیفه</button>
    {{Form::close()}}
    {{Form::open(array(
                    'url'			=>	URL::to('task/action/' . $task->id),
                    'method'		=>	'put',
                    'style'         =>  'float: right; margin-left: 10px;'
                ))}}
                <input type="hidden" name="taskId" value="{{$task->id}}">
                <input type="hidden" name="actionType" value="1">
                <input type="hidden" name="for_id" value="@if($user->getId() == $task->creator_id){{$task->for_id}}@else{{$task->creator_id}}@endif">
                <button class="btn btn-xs btn-success" type="submit" alt="اتمام وظیفه" title="اتمام وظیفه"><i class="ace-icon fa fa-check bigger-120"></i> اتمام وظیفه</button>
    {{Form::close()}}
    {{Form::open(array(
                    'url'			=>	URL::to('task/action/' . $task->id),
                    'method'		=>	'put',
                    'style'         =>  'float: right; margin-left: 10px;'
                ))}}
                <input type="hidden" name="taskId" value="{{$task->id}}">
                <input type="hidden" name="actionType" value="0">
                <button class="btn btn-xs" type="submit" alt="انجام نشده" title="در جریان"><i class="ace-icon fa fa-undo bigger-120"></i> در جریان</button>
    {{Form::close()}}
                <a href="{{URL::to('task/edit/'. $task->id)}}"><button type="button" class="btn btn-xs margin-right btn-info operation-margin"><i class="ace-icon fa fa-reply icon-only"></i> ویرایش وظیفه</button></a>


@else
       {{Form::open(array(
                       'url'			=>	URL::to('task/action/' . $task->id),
                       'method'		=>	'put'
                   ))}}
                   <input type="hidden" name="taskId" value="{{$task->id}}">
                   <input type="hidden" name="actionType" value="1">
                   <input type="hidden" name="for_id" value="@if($user->getId() == $task->creator_id){{$task->for_id}}@else{{$task->creator_id}}@endif">
                   <button type="submit" class="btn btn-xs btn-success" alt="اتمام وظیفه" title="اتمام وظیفه" ><i class="ace-icon fa fa-check bigger-120"></i> اتمام وظیفه</button>
       {{Form::close()}}
@endif