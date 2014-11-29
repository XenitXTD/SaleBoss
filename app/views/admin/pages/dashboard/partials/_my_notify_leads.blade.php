
              <ul class="dropdown-navbar dropdown-menu dropdown-caret"  style="width: 350px;">
                  <li class="dropdown-header Nassim">
                      <i class="ace-icon fa fa-bell-o"></i>
                      لیست لیدهای امروز من
                  </li>

                  <li class="dropdown-content" style="position: relative;">
                      <ul class="dropdown-menu dropdown-navbar dropdown-menu-right">
                        @if(! $remindingLeadsNotify->isEmpty())
                                @foreach($remindingLeadsNotify as $lead)
                                <li style="border-bottom: 1px solid #DDE9F2">
                                    <span class="pull-left">
                                        @for($i=1;$i<=$lead->priority;$i++)
                                             <i style="color:#CC9900" class="fa fa-star"></i>
                                         @endfor
                                    </span>
                                    <span class="msg-body fontSize12">
                                        <span class="msg-title">
                                            <span class="blue">{{$lead->phones->first()->number}}</span>
                                        </span>
                                        <span class="msg-time">
                                            <span>{{$lead->name}}</span>
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



