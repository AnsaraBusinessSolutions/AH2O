@extends('layouts.app')

<style type="text/css">
.loader {
  border: 16px solid #f3f3f3; /* Light grey */
  border-top: 16px solid #3498db; /* Blue */
  border-radius: 50%;
  width: 120px;
  height: 120px;
  animation: spin 2s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
.processing{
padding-left:54%;
padding-top:25%;
}
</style>
@section('content')
<div class="container" id="app-content">
	<div class="processing">
	<div class="loader">&nbsp;</div>
	<input type="hidden" name="user_id" id="user_id" value="">
	@csrf
	</div>
	<div class="display_content_area" style="display:none">
		
	</div>
	
	<x-button name-button="simpleButton" class="btn btn-primary" content="Simple View for the Template"></x-button>
</div>
@endsection
<!-- @include("scripts.home_script")-->
