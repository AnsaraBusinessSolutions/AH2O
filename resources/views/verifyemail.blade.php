@extends("layouts.app")

@section("content")
<div class='container'>
        <div class='col-md-12'>
                    <label class='form-label'>Your Email address is now sent with a pin for additional security.<br> Please put the same in the below box to verify the same,</label>
                    <input type='text' name='verifypin' class='form-control'>
                    <button type='button' name='verify_pin' id='verify_pin' class='btn btn-primary'>Verify PIN</button>
                    <input type='hidden' name='email' id='email' value="{{ $email }}">
        </div>
</div>
@endsection
@include("scripts.verifyemail_script")