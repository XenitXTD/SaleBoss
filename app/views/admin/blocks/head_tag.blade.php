@section('meta')
    @include('admin.blocks.meta')
@show

@section('page_title')
    @include('admin.blocks.page_title')
@show

@section('stylesheets')
    @include('admin.blocks.stylesheets')
@show
   		<script src='{{asset('assets/admin/js/jquery.min.js')}}'></script>
   		<script src="{{asset('assets/admin/js/raphael-min.js')}}"></script>
   		<script src="{{asset('assets/admin/js/morris.min.js')}}"></script>
   		<script src="{{asset('assets/admin/js/saleboss.js')}}"></script>
<script type="text/javascript">
	var baseUrl = "{{URL::to('/')}}";
</script>
