@section("footer-scripts")


<script type="application/javascript">

$(document).ready(function(){
	
//	alert("the contetn");
	$.ajaxSetup({
	headers: {
	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
	'Authorization':"Bearer "+localStorage.getItem('token')	
        }
});
if($("#app > .app-container").length==0){
        // Set the URL to whatever it was plus "#".
        //url = document.URL+"#";
        //location = "#";
		//let data = localStorage.getItem("reload")?parseInt(localStorage.getItem("reload")):0;
		//localStorage.setItem("reload",(parseInt(data))+1);
        
		setTimeout(function(){
			$("main #app-content > .processing").css("display","block");
			$("main #app-content > .display_content_area").css("display","none");
		//Reload the page
        location.reload(true);
		//window.location.href = window.location.origin+"/home";
		}  ,1800);
    	}
setTimeout(function(){
	$.ajax({
	url : "{{ route('home_page_content') }}",
		//method:"POST",
		type:"POST",
	data:{
	_token : $("input[name='_token']").val(),
		user_id:$("#user_id").val(),
		access_token:$("#access_token").val()
},
	async:false,
		success:function(response){
			if(response.id==1){
				/*let html = "";
				html += "";
				html +=	"<div class='row' class='col-md-6'><p class='col-md-3' style='text-align:left;'>"+response.message+"</p>";
				console.log(response.permissions.create_and_update_user);
				if(response.permissions.create_and_update_user==true){
					html +=	"<p style='text-align:right' class='col-md-3'>";
					html += '<x-anchor-button href="./createuser" class="btn btn-primary" content="Create User" />';
					html += "</p>";
				
				}
				html +=	"</div>";
				$(".display_content_area").html(html);*/
				if(response.sidebar.update_sidebar){
					//$("#wrapper").html(response.sidebar.sidebar);
					console.log(response.sidebar)		;
					$(".nav-menu-dynamic").append(response.sidebar.sidebar);
					pageClick();
				}	
				if(response.topbar){
					//$("#navbarSupportedContent").find("ul.navbar-nav:last").html(response.topbar);
				}
				$(".processing").css("display","none");
				$(".display_content_area").css("display","block");
			}
			else{
				
				$(".display_content_area").html("<p>Some Error Occured,</p>");
				$(".processing").css("display","none");
				$(".display_content_area").css("display","block");
				if(response.result=='failed'){
					//localStorage.removeItem("token");
					window.location.href="/login";
				}
			}
		},
		error:function(xhr,error,textstatus){
			console.log("error occured");
			let status = xhr.status;
			console.log(status);
			if(status>=400 && status <=404){
				let url = "/login-page";
				console.log("in the condition");
				window.location.href=url;
			}
		}	
	})},1000);
	console.log(localStorage.getItem("reload"));
	
		
});
function editUser(user_id){
	$.ajax({
		url:"/api/edit-user",
		data:{
			edit_user:user_id
		},
		type:"POST",
		success:function(response){
			if(response.result=="success"){
				$("#dataAlterModal .modal-title").html(response.title);
				$("#dataAlterModal .modal-body").html(response.html);
				$("#dataAlterModal .modal-content button[name='save']").attr("onclick",response.saveUserfunction);
				$("#dataAlterModal").modal("show");
			}
		},
		error:function(xhr,textStatus,error){

		}
	});
}
function saveUser(user_id){
	$.ajax({
		url:"/api/save-user",
		data:{
			name:$("input[name='name']").val(),
			role:$("select[name='role']").val(),
			auth_method:$("select[name='auth_method']").val(),
			id:$("input[name='user_id']").val()
		},
		type:"POST",
		success:function(response){
			if(response.result=="success"){
				$("#dataAlterModal").modal("hide");
				alert("Saved the user records successfully");
			}
			else{
				alert(response.message);
			}
		},
		error:function(xhr,textStatus,error){
			alert("Unable to store the user");
		}
	})
}

</script>
@endsection
