@section("footer-scripts")
<script type="application/javascript">
$(document).ready(function(){
    console.log("the data");
//alert("the data");
	let elm = localStorage.getItem("token");
	if(elm!=""){
		localStorage.removeItem("token");
	}
	$("button[name='login']").on("click",function(){
        console.log($("input[name='password']").is(":visible"));
        if($("input[name='password']").is(":visible") && $("input[name='password']").val()!=""){
		$.ajax({
			url : "/api/login",
			type:"POST",
			data:{
				email:$("#email").val(),
				password:$("#password").val(),
				_token:$("input[name='_token']").val(),
				loginBackendSetting:true	
	},
		success:function(response){
            let responseloop = "";
            console.log(response);
            
            for(var prop in response){
                console.log(prop);
                console.log(response[prop]);
                if(response[prop] && prop=="original"){
                    responseloop = response[prop];//''
                }
            }
            
            if(responseloop.length==0){
                /*console.log("coming to no response condition");
                if(response?.token?.original?.length>0){
                    responseloop = response.token.original;
                }
                else{*/
                responseloop = response;
                //}
            }
			if(responseloop.id==1 || responseloop?.access_token?.length>0){
                if(responseloop?.access_token?.length>0){
				localStorage.setItem("token",responseloop.access_token);
				if(responseloop.access_token!="" && $("#access_token").length){
					$("#access_token").val(responseloop.access_token);	
				}
                }
                else{
                    if(responseloop?.token.original?.access_token?.length>0){
                        localStorage.setItem("token",responseloop.token.original.access_token);
                        $("#access_token").val(responseloop.token.original.access_token);
                    }
                }
        /*        $.ajax({
                url: "/app/test",
                type: "GET",
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('Authorization', 'Basic ' + encoded);
                },
                success: function() {
                    window.location.href = '/app/test.html';
                }
                });
        */
        //callHome(responseloop.location);
					
                    //let responsehref = responseloop.location;
                    let url_param = window.location.pathname;
                    url_param = url_param.split("/");
                    let location = response.original.location;
                    if(response.original.location.includes("home")){
                        window.location.href = window.location.origin+"/home";
                    }
                    else{
                    console.log(window.location.origin);
					window.location.href = window.location.origin+location+("/"+(url_param[2].length?url_param[2]:""));	}

                }
				else{
					alert("Error Occured while Logging in");
				}
			},
			error:function(xhr,error,textstatus){
				alert("Error occured while logging in");
			}	
		});
        }
        else{
            $.ajax({
                url:"/api/verify-user",
                type:"POST",
                data:{
                    email:$("#email").val()
                },
                success:function(response){
                    if(response.result=="success"){
                            if(response?.redirect_url){
                            window.location.href=response.redirect_url;
                            }
                    }
                    else{
                            alert("user account is not exists");
                    }
                },
                error:function(xhr,textStatus,error){
                    alert("the error page exists");
                }
            });
        }
	});
});
function callHome(url_location){
    
    $.ajax({
        url : "/api/home",
        type:"GET",
        data:{  },
        beforeSend:function(xhr){
            xhr.setRequestHeader("Authorization",localStorage.getItem("token"));
        },
        success:function(response){
            return false;
            window.location.href = url_location;
        }
    })
}
</script>
@endsection