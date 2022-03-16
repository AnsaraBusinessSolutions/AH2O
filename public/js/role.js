
jQuery(function(){
    //alert("the role page alert for loading the content");
    $("[class*='role_edit']").on("click",function(){
            let classfor = $(this).attr("class");
             $("#dataAlterModal").modal({show:true});
    });//  ''  
    $("button[name='create_role']").on("click",function(){
        
        $.ajax({
            url : "/api/create-role",
            data : {},
            type:"GET",
            success:function(response){
                if(response.result=="success"){
                    let title = "";
                    let body = "";
                    title=response?.title;
                    body = response?.html;
                    $("#dataAlterModalLabel").html(title);
                    $("#dataAlterModal .modal-body").html(body);
                    //$("#dataAlterModal button[name='save']").attr("onclick",'');
                    //$("#dataAlterModal ").
                    $("#dataAlterModal").modal({show:true});
                }
                else{
                    alert("error loading content");
                }
            },
            error:function(xhr,textStatus,error){

            }
        }) 
        
    });
    $("input[id*=active_role_]").on("change",function(){
            let id = $(this).attr("id");
            id= id.replaceAll("active_role_","");
            $.ajax({
                url : "/api/update-status",
                type:"POST",
                data : {
                    id:id,
                    status:$(this).is(":checked")
                },
                success:function(response){
                    if(response.result == "success"){
                        alert(response.message);
                    }
                    else{
                        alert(response.message);
                    }
                },
                error:function(xhr,textStatus,error){
                    alert("error occured while updating");
                }

            });
    });//''
    $("button[name='save']").on("click",function(){
        $.ajax({
            url:"/api/store-role",
            type:"POST",
            data : {
                name:$("#role_name").val(),
                active:$("#role_active").is(":checked")
            },
            success:function(response){
                if(response.result=='success'){
                    $("#dataAlterModal").modal("hide");
                }
                else{
                    alert('failed to save the role');
                }
            },
            error:function(xhr,textStatus,error){
                alert("Failed to save the role");
            }
        })
    });
});
