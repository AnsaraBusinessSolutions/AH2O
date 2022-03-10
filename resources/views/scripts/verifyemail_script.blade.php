@section("footer-scripts")
<script type='application/javascript'>
jQuery(function(){
        $("button[name='verify_pin']").on("click",function(){
                $.ajax({
                    url:"/api/verify-pin",
                    type:"POST",
                    data:{
                        verifypin:$("input[name='verifypin']").val(),
                        email:$("#email").val()
                    },
                    success:function(response){
                        if(response.result=="success"){
                            window.location.href = response.location;
                        }
                    },
                    error:function(xhr,textStatus,error){
                        alert("the error occured while loading the data");
                    }
                })
        });//''
    //$("*").on("click")
    });
</script>
@endsection