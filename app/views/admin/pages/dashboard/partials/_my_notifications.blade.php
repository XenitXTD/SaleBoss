                <li class="red @if(Session::get('TodayLeadsNotifications')) open @endif">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="ace-icon fa fa-phone"></i>
                        <span class="badge badge-important">{{$TodayLeadsNotificationsCount->extra or ""}}</span>
                    </a>
                    <ul class="dropdown-navbar dropdown-menu dropdown-caret"  style="width: 350px;">
                                      <li class="dropdown-header Nassim">
                                          <i class="ace-icon fa fa-phone"></i>
                                          لیدهای من
                                      </li>

                                      <li class="dropdown-content" style="position: relative;">
                                          <ul class="dropdown-menu dropdown-navbar dropdown-menu-right">
                                            @if(!$TodayLeadsNotificationsList->isEmpty())
                                                @foreach($TodayLeadsNotificationsList as $notification)
                                                    <li style="border-bottom: 1px solid #DDE9F2">
                                                        <span class="msg-body fontSize12">
                                                            <span class="msg-title">
                                                                <span class="blue"><a href="{{URL::route('LeadsUnreads')}}"> تعداد لیدهای امروز من: {{$notification->extra}}</a></span>
                                                            </span>
                                                        </span>
                                                    </li>
                                                @endforeach
                                              @else
                                                  <li class="text-center Nassim">موردی وجود ندارد</li>
                                              @endif
                                          </ul>
                                      </li>
                                      <li class="dropdown-footer Nassim">
                                             <a href="{{URL::to('me/leads')}}" style="font-size: 16px !important; font-weight: 700 !important"> تمامی لیدهای من <i class="ace-icon fa fa-arrow-left"></i></a>
                                      </li>
                                  </ul>
                </li>
                @if(Session::get('TodayLeadsNotifications')) {{Session::put('TodayLeadsNotifications', false)}} @endif
