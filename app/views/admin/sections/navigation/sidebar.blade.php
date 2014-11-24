<div class="sidebar responsive sidebar-fixed" id="sidebar">
     <div class="sidebar-shortcuts-large center" id="sidebar-shortcuts-large">

       <button class="btn">
            <i class="ace-icon fa fa-signal"></i></a>
        </button>

        <button class="btn">
            <i class="ace-icon fa fa-pencil"></i>
        </button>

        <!-- #section:basics/sidebar.layout.shortcuts -->
        <button class="btn">
            <i class="ace-icon fa fa-users"></i>
        </button>

        <button class="btn">
            <i class="ace-icon fa fa-cogs"></i>
        </button>
     </div>

     <ul class="nav nav-list">
        <li class="{{!empty($menu['active']) ? 'active' : ''}}"><a href="{{URL::to('dash')}}">داشبورد</a></li>
        @if(Sentry::getUser()->hasAnyAccess(['menu.admin']))
            @foreach(MenuBuilder::fetch('admin') as $menu_side)
                {{View::make('admin.blocks.menu_dropdown',['menu' => $menu_side])->render()}}
            @endforeach
        @elseif(Sentry::getUser()->hasAnyAccess(['menu.accounter']))
            @foreach(MenuBuilder::fetch('accounter') as $menu_dash)
                {{View::make('admin.blocks.menu_dropdown',['menu' => $menu_dash])->render()}}
            @endforeach
        @elseif(Sentry::getUser()->hasAnyAccess(['menu.support']))
            @foreach(MenuBuilder::fetch('support') as $menu_dash)
                {{View::make('admin.blocks.menu_dropdown',['menu' => $menu_dash])->render()}}
            @endforeach
        @elseif(Sentry::getUser()->hasAnyAccess(['menu.sales']))
            @foreach(MenuBuilder::fetch('sales') as $menu_dash)
                {{View::make('admin.blocks.menu_dropdown',['menu' => $menu_dash])->render()}}
            @endforeach
        @endif
     </ul>
</div><!-- /.sidebar -->