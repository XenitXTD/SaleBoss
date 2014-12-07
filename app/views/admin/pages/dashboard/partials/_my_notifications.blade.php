
                <li class="red @if(Session::get('TodayLeadsNotifications')) open @endif">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="ace-icon fa fa-phone"></i>
                        <span class="badge badge-important">@if(!($TodayLeadsNotificationsCount == 0)){{$TodayLeadsNotificationsCount}}@endif</span>
                    </a>
                    <ul class="dropdown-navbar dropdown-menu dropdown-caret"  style="width: 350px;">
                                      <li class="dropdown-header Nassim">
                                          <i class="ace-icon fa fa-phone"></i>
                                          وضعیت لیدهای به یادآوری من
                                      </li>

                                      <li class="dropdown-content" style="position: relative;" data-size="300">
                                          <ul class="dropdown-menu dropdown-navbar dropdown-menu-right">
                                            @if(!($TodayLeadsNotificationsCount == 0))
                                                    <li style="border-bottom: 1px solid #DDE9F2">
                                                        <span class="msg-body fontSize12">
                                                            <span class="msg-title">
                                                                <span class="blue">تعداد لیدهای امروز من: {{$TodayLeadsNotificationsCount}}</span>
                                                            </span>
                                                        </span>
                                                    </li>
                                            @else
                                                  <li class="text-center Nassim">موردی وجود ندارد</li>
                                            @endif
                                          </ul>
                                      </li>
                                      <li class="dropdown-footer Nassim">
                                             <a href="{{URL::route('LeadsUnreads')}}" style="font-size: 16px !important; font-weight: 700 !important">تمامی لیدهای امروز: <i class="ace-icon fa fa-arrow-left"></i></a>
                                      </li>
                                  </ul>
                </li>
                @if(Session::get('TodayLeadsNotifications')) {{Session::put('TodayLeadsNotifications', false)}} @endif

                <li class="grey ">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="ace-icon fa fa-tasks"></i>
                        <span class="badge badge-important">@if(!(count($tasks) == 0)){{count($tasks)}}@endif</span>
                    </a>
                    <ul class="dropdown-navbar dropdown-menu dropdown-caret"  style="width: 350px;">
                                      <li class="dropdown-header Nassim">
                                          <i class="ace-icon fa fa-tasks"></i>
                                          وضعیت وظایف من
                                      </li>

                                      <li class="dropdown-content" style="position: relative;" data-size="300">
                                          <ul class="dropdown-menu dropdown-navbar dropdown-menu-right">
                                            @if(!(count($tasks) == 0))
                                                @foreach($tasks as $task)
                                                    <li style="border-bottom: 1px solid #DDE9F2">
                                                        <span class="msg-body fontSize12">
                                                            <span class="msg-title">

                                                                <span class="blue"><a href="{{$task->url}}"><b>

                                                                @if($task->to_id == $task->from->creator->id)
                                                                    {{$task->from->forWhom->first_name}} {{$task->from->forWhom->last_name}}
                                                                @else
                                                                    {{$task->from->creator->first_name}} {{$task->from->creator->last_name}}
                                                                @endif

                                                                </b> {{$task->extra}}</a> </span>
                                                            </span>
                                                            <span class="msg-time">
                                                                <i class="ace-icon fa fa-clock-o"></i>
                                                                <span>{{$task->from->jalaliDate('todo_at')}}</span>
                                                            </span>
                                                        </span>
                                                    </li>
                                                @endforeach
                                            @else
                                                  <li class="text-center Nassim">موردی وجود ندارد</li>
                                            @endif
                                          </ul>
                                      </li>
                                  </ul>
                </li>

                 <li class="green">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="ace-icon fa fa-envelope"></i>
                        <span class="badge badge-important">@if(!(count($tasksMessages) == 0)){{count($tasksMessages)}}@endif</span>
                    </a>
                    <ul class="dropdown-navbar dropdown-menu dropdown-caret"  style="width: 350px;">
                                      <li class="dropdown-header Nassim">
                                          <i class="ace-icon fa fa-envelope"></i>
                                          پیام های من
                                      </li>

                                      <li class="dropdown-content" style="position: relative;" data-size="300">
                                          <ul class="dropdown-menu dropdown-navbar dropdown-menu-right">
                                            @if(!(count($tasksMessages) == 0))
                                                @foreach($tasksMessages as $tasksMessage)
                                                    <li style="border-bottom: 1px solid #DDE9F2">
                                                        <span class="msg-body fontSize12">
                                                            <span class="msg-title grey">
                                                                <span class="blue"><a href="{{$tasksMessage->url}}"><b>

                                                                    {{$tasksMessage->from->creator->first_name}} {{$tasksMessage->from->creator->last_name}}

                                                                </b></a>:</span> {{softTrim($tasksMessage->from->message,50)}}
                                                            </span>
                                                            <span class="msg-time">
                                                                <i class="ace-icon fa fa-clock-o"></i>
                                                                <span>{{$tasksMessage->jalaliTimeDate('created_at')}}</span>
                                                            </span>
                                                        </span>
                                                    </li>
                                                @endforeach
                                            @else
                                                  <li class="text-center Nassim">موردی وجود ندارد</li>
                                            @endif
                                          </ul>
                                      </li>
                                  </ul>
                </li>
