jQuery(function(){
    $("input[name='form-option']").on("change",function(){
        let id = $(this).attr("id");
        //let classes = {};
        $("input[name='form-option']").each(function(){
                //classes.push()
                let value = $(this).attr("id");
                console.log(value);
                $("."+value).addClass("d-none");
            });
        if(id!=""){
            $("."+id).removeClass("d-none");
        }

    });
    $("#save_page").on("click",function(){
        $("div.app-main__outer > .processing").css("display","block");
        $("div.app-main__outer > .display_content_area").css("display","none");
        console.log()
        $.ajax({
            url: "/api/create-new-page",
            type:"POST",
            data:{
                title:$("input[name='title']").val(),
                form_linked:($("#form-option-content").val()==""?0:$("#form-option-content").val()),
                content:($("#form-option-content").val()==""?($("#form-content-data").val()==""?"":$("#form-content-data").val()):$("#form-option-content").val())
            },
            success:function(response){
                if(response.result=="success"){
                        $("div.app-main__outer > .processing").css("display","none");
                        $("div.app-main__outer > .display_content_area").css("display","block");

                }
                else{
                    $("div.app-main__outer > .processing").css("display","none");
                        $("div.app-main__outer > .display_content_area").css("display","block");
                }
            },
            error:function(xhr,textStatus,error){
                alert("failed to save the page");
                $("div.app-main__outer > .processing").css("display","none");
                $("div.app-main__outer > .display_content_area").css("display","block");
            }
        })
    });
    $("#preview_page").on("click",function(){
        let form_name = $("#form-option-content").val();
        if(form_name!=''){
            $(".app-main__outer > .processing").css("display","block");
            $(".app-main__outer > .display_content_area").css("display","none");
            $.ajax({
                url : "/api/get-generate-dynamic-form",
                data:{
                    formname:$("#form-option-content option:selected").text()
                },
                type:"GET",
                success:function(response){
                    $(".app-main__outer > .processing").css("display","none");
                    $(".app-main__outer > .display_content_area").css("display","block");
                    if(response.result=="success"){
                        if(response.html!=""){
                            $("#dataAlterModal .modal-title").html("Preview :"+$("#form-option-content option:selected").text());
                            $("#dataAlterModal .modal-body").html(response.html);//''
                        }
                        else{
                            alert("Failed to preview the form");
                        }
                    }
                    else{
                        alert("Failed to preview the form");
                    }
                },
                error:function(xhr,textStatus,error){
                    $(".app-main__outer > .processing").css("display","none");
                    $(".app-main__outer > .display_content_area").css("display","block");
                }
            });
        $("#dataAlterModal").modal("show");
        }
    });
});