@extends("layouts.app")
<script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>
<script>
$(document).ready(function(){
    $.ajaxSetup({
	headers: {
	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
	'Authorization':"Bearer "+localStorage.getItem('token')	
        }
});
    window.location.href=window.location.origin+"/home";
});//''
    </script>