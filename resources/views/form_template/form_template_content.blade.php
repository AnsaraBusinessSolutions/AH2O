@extends("layouts.app")

@section("content")

<div class='container'>
        <div class='col-md-12'>
            <div class='login_form'>
                @if(empty($html)==false)
                {!! $html !!}
                @else
                <p>Unable to load Content from server</p>
                @endIf
            </div>
        </div>
        <input type='hidden' name='template_form_url' id="template_form_url" value="{{ $template_form_url }}">
</div>

@endsection
@section("footer-scripts")
<script type='application/javascript'>
    jQuery(function(){
        let url = $("#template_form_url").val();
        let parameters = getParameters();
        $.ajax({
            url : url,
            type:"POST",
            data:{},
            success:function(response){
                if(response.result=="success"){
                    alert("the content is now displaying");
                }
            },
            error:function(xhr,textStatus,error){

            }
        });

    });
    function getParameters(){

    }
    </script>
@endsection