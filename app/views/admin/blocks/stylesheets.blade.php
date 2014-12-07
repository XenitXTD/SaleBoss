{{--<link href="{{asset('assets/admin/css/saleboss.min.css')}}" rel="stylesheet">--}}
{{--<link href="{{asset('assets/admin/css/custom.css')}}" rel="stylesheet">--}}

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="{{asset('assets/admin/css/bootstrap.min.css')}}" />
		<link rel="stylesheet" href="{{asset('assets/admin/font-awesome/css/font-awesome.min.css')}}">

		<!-- page specific plugin styles -->

		<!-- text fonts -->
		<link rel="stylesheet" href="{{asset('assets/admin/css/op-fonts.css')}}" />

		<!-- ace styles -->
		<link rel="stylesheet" href="{{asset('assets/admin/css/op.min.css')}}" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="{{asset('assets/admin/css/op-part2.min.css')}}" />
		<![endif]-->
		<link rel="stylesheet" href="{{asset('assets/admin/css/op-skins.min.css')}}" />
		<link rel="stylesheet" href="{{asset('assets/admin/css/op-rtl.min.css')}}" />
		<link rel="stylesheet" href="{{asset('assets/admin/css/style.css')}}" />

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="{{asset('assets/admin/css/op-ie.min.css')}}" />
		<![endif]-->

		@if(empty(Sentry::getUser()))
			<style> .panelColor { background-color: none; } </style>
		@elseif(Sentry::getUser()->getGroups()->first()->name == 'admin')
        <style> .panelColor { background-color: #5F6483 !important; } </style>
        @elseif(Sentry::getUser()->getGroups()->first()->name == 'sales')
        <style> .panelColor { background-color: #438EB9 !important; } </style>
        @elseif(Sentry::getUser()->getGroups()->first()->name == 'accounter')
        <style> .panelColor { background-color: #2E8965 !important; } </style>
        @elseif(Sentry::getUser()->getGroups()->first()->name == 'support')
        <style> .panelColor { background-color: #2C6AA0 !important; } </style>
        @endif
