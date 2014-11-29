<div id="navbar" class="navbar navbar-default navbar-fixed-top panelColor" style="-webkit-box-shadow: 0 2px 4px 0 rgba(0,0,0,.25);box-shadow: 0 2px 4px 0 rgba(0,0,0,.25)">
     <div id="navbar-container" class="navbar-container">

        <!-- toggle buttons are here or inside brand container -->

        <div class="navbar-header pull-right">
          <a href="http://www.opilo.com" target="_blank" class="navbar-brand">
            <img src="{{asset('assets/admin/img/images/logo-white.png')}}">
          </a>
        </div><!-- /.navbar-header -->

        <div class="navbar-buttons navbar-header pull-left">
          <ul class="nav ace-nav">
                @include('admin.pages.dashboard.partials._my_notifications')
                <!-- #section:basics/navbar.user_menu -->
                <li class="light-blue profile-dropmenu">
                    <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                        <img class="nav-user-photo" src="{{asset('assets/admin/img/avatars/4.gif')}}" alt="Jason's Photo" />
                        <span class="user-info">
                            {{Sentry::getUser()->getIdentifier()}}
                        </span>
                        <i class="ace-icon fa fa-caret-down"></i>
                    </a>

                    <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-light dropdown-caret dropdown-close">
                        <li class="center">
                            <span class="profile-picture">
                                <img class="img-responsive" src="{{asset('assets/admin/img/avatars/4.gif')}}" alt="تصویر {{Sentry::getUser()->getIdentifier()}}"  id="avatar"/>
                            </span>
                        </li>
                        <li>
                            <a href="{{URL::to('stats/user/' . Sentry::getUser()->id)}}">
                                <i class="ace-icon fa fa-line-chart"></i>آمار
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <i class="ace-icon fa fa-user"></i>
                                پروفایل
                            </a>
                        </li>

                        <li class="divider"></li>

                        <li>
                            <a href="{{URL::to('auth/logout')}}">
                                <i class="ace-icon fa fa-power-off"></i>
                                خروج
                            </a>
                        </li>
                    </ul>
                </li>


                <!-- /section:basics/navbar.user_menu -->

          </ul>
        </div><!-- /.navbar-buttons -->

     </div><!-- /.navbar-container -->
    </div><!-- /.navbar -->