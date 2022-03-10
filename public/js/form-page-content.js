var nodeNamesWithContent = ["label","button"];
var nodeNamesWithValue = ["input","textarea"];
jQuery(function(){
    var nodeNames = [];
    
    $(".list-group-item").on("click",function(){
            let html = "";
            html = getHtml($(this));      
    }); //''
    $(".clear-all").on("click",function(){
            $(".form-creation-area").html("");
            $("#dataAlterModal > .modal-dialog > .modal-content > .modal-footer > button[name='save']").attr("onclick","");
            $("#dataAlterModal > .modal-dialog > .modal-content > .modal-body").html("");
    });//''
    $(".edit-button").on("click",function(){
        //.edit-buttoin
        let modal_data = $(this).data("recent");//''
        if(modal_data!=""){
            $("#dataAlterModal").modal("show");
            $(this).css("display","none");
        }
    });
    $("#save_form_template").on("click",function(){
        let template_name = $("#form_template_name").val();
        let html_to_update = $(".template_url_content_get").html();
        $("#dataAlterModal").find(".modal-dialog > .modal-content > .modal-body").html(html_to_update);
        $("#dataAlterModal").find(".modal-dialog > .modal-content > .modal-footer > button[name='save']").attr("onclick","save_template_with_url('template_form_creation')");
        $("#dataAlterModal").modal("show");
        //let form_template = save_form("template_form_creation");
        /*
        $.ajax({
            url:"/api/form-template-save",
            type:"POST",
            data:{name:template_name,template_data:form_template},
            success:function(response){
                if(response.result=="success"){
                    console.log("form stored successfully");
                }
            },
            error:function(xhr,textStatus,error){
                alert("An error occured while storing the template");
            }
        });
        */
    });
    $(".delete-button").on("click",function(){
        let element = $(this).data("element");
        if(element!=""){
            if($("[name='"+element+"']").length){
                $("[name='"+element+"']").parent().remove();
                alert("Element removed Successfully");
            }
            else{
                alert("Difficulty in removing the element");
            }
        }
        else{
            alert("Difficulty in removing the element");
        }
    });
    getEvents();
});
function getHtml(element){
    let name = $(element).data("element")+"_"+Math.ceil(Math.random() * 1000000);
    $.ajax({
        url : "/api/get-element-from-list",
        type:"GET",        
        data:{name:name,class:$(element).data("class"),content:$(element).data("placeholder"),input_type:$(element).data("element"),element_type:$(element).data("element-type")},
        success:function(response){
            if(response.result=="success"){
                $(".form-creation-area").append(response.html);
            }
            else{
                $(".form-creation-area").append(response.html);
            }
            getEvents();
        }
    });
}
function getEvents(){
    let disabled_ap=["name","type"];
    var nodeNamesWithContent = ["label","button"];
    var nodeNamesWithVal = ["input","textarea"];
    $("div.added_item_div").each(function(){
      //  $(this).on("click",function(){
        //console.log("the added item div");
      //  });
    });
    console.log("get events triggered");
    $("div.added_item_div").off("click").on("click",function(e){
        console.log("div item clickedd");
        console.log(e.target.nodeName);
        console.log(e);//''
        if(e.target.nodeName=="Div" || e.target.nodeName=='div' || e.target.nodeName=='DIV'){
            $(".edit-button").css("display:none");
            $(".delete-button").css("display:none");
        }
        else{
            let x = 0,y=0,top=0;
            let position = e.target.getBoundingClientRect();
            x = (position.x + position.width)+"px";y=position.y+"px";
            top = position.top +(window.scrollY);//)
            //$(".edit-button").insertAfter(e.target.nodeName+"[name='"+e.target.name+"']");
            let nodename = e.target.nodeName.toLowerCase();
            console.log($(e.target));
            $("#dataAlterModal > .modal-dialog > .modal-content > .modal-footer > button[name='save']").attr("onclick","saveElement('"+e.target.getAttribute("name")+"','"+nodename+"')");
            let html = editElement(nodename,e.target.getAttribute("name"),disabled_ap );
            $("#dataAlterModal > .modal-dialog > .modal-content > .modal-body").html("").html(html);
            $(".edit-button").css({ "left":x, "right":y, "top":top, "display":"block"  ,"position":"absolute"});//s
            $(".delete-button").data("element",e.target.getAttribute("name")).css({"left":(position.x + position.width + 80), "right":y, "top":top, "display":"block", "position":"absolute"});
            console.log($(".edit-button"));
        }
    });
}
function previewHtml(form_name="template_form_creation"){
    if(form_name!=""){
        let html = $("form[name='"+form_name+"'] > .form-creation-area").html();
        $("#dataAlterModal > div.modal-dialog > div.modal-content > div.modal-header > div.modal-title").html("Preview Form");
        html = "<div class='container'>"+html+"</div>";//s
        $("#dataAlterModal > div.modal-dialog > div.modal-content > div.modal-body").html(html);
        $("#dataAlterModal").modal("show");
    }
}
function editElement(node, input_name="",disabled_ap=[]){
    
    let attributes = $(node+"[name='"+input_name+"']")[0].attributes;
    
    let html = "<div class='container'>";
    for(let i=0;i<attributes.length;i++){
        console.log(attributes[i].nodeName +" "+attributes[i].nodeValue);
        if(attributes[i].nodeName=="for"){
            html += getFor(attributes[i],input_name);
        }
        else{
        html+="<div class='row'>";
        html+="<label class='lbl-primary form-label'>"+attributes[i].nodeName+" : </label>";
        html +="<input type='text' class='form-control' name='"+input_name+"_"+attributes[i].nodeName+"' value='"+$(node+"[name='"+input_name+"']").attr(attributes[i].nodeName)+"' "+($.inArray(attributes[i].nodeName,disabled_ap)>-1?"disabled":"")+">";
        html +="</div>";
        }
    }
    if($(node+"[name='"+input_name+"']").text()!=""){
        html += "<div class='row'>";
        html += "<label class='lbl-primary form-label'>Content</label>";
        html += "<input type='text' class='form-control' name='"+input_name+"_content' value='"+$(node+"[name='"+input_name+"']").text()+"'>";
        html += "</div>";
    }

    html += "</div>";
    return html;
}
function getFor(attributes,input_name){
    let html = "<div class='row'>";
    html += "<label class='lbl-primary form-label'>"+attributes.nodeName+"</label>";
    html += "<select name='"+input_name+"_"+attributes.nodeName+"' class='form-control' >";
    html += "<option value=''>Select</option>";
    $("#template_form_creation").find("input").each(function(){
            html += "<option value='"+$(this).attr("name")+"'> Input "+$(this).attr("name")+"</option>";
    });//''
    html += "</select>";
    html += "</div>";
    return html;
}
function saveElement(element,nodename){
    let attributes = ["content"];
    console.log(element+" "+nodename);

    $("[name^='"+element+"_']").each(function(){
            let param_name = "";
            param_name = name_split = $(this).attr("name");
            name_split = name_split.replaceAll(element,"").replace("_","");
            console.log($(this).attr("name")+" "+$(this).attr("value")+" "+$(this).val());
            if(name_split.length){
                console.log(nodename);
            if($.inArray(nodename,nodeNamesWithContent)>-1 && $.inArray(name_split,attributes )>-1){
                console.log("comes"+" "+$(this).attr("name"));
                $(nodename+"[name='"+element+"']").text($(this).val());
            }
            else if($.inArray(nodename,nodeNamesWithValue)>-1 && name_split=="value"){
                $(nodename+"[name='"+element+"']").val($(this).val());
            }
            else{
                console.log("comes"+" "+name_split+" "+($.inArray(name_split,attributes )>-1));
            $(nodename+"[name='"+element+"']").attr(name_split,$(this).val());
            }
            }
        });//''
}
function save_template_with_url(form_name=''){
    if(form_name!=''){
        $("input[name='template_url']").val($("#dataAlterModal #template_form_url").val());
        save_form(form_name);
    }
    else{
        alert("Unable to update form");
    }
}
