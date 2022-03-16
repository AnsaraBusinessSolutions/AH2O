//const { component } = require("vue/types/umd");

jQuery(function(){

    //Identify the Components or items trigger loading
    $(".component-menu").on("click",function(){
        let classname= $(this).attr('class')?$(this).attr("class").replace("component-menu","").trim():"";
        console.log(classname);
        console.log(classname.replaceAll("component-menu","").replace("-","").replace(" ",""));
        if(classname.length!=0){
            let component_name = classname.replaceAll("component-menu","").replace("-","").replace(" ","");
            console.log(component_name);
            $("div.app-main__outer > .processing").show();
            $("div.app-main__outer > .display_content_area").hide();
        $.ajax({
            url : "/api/get-inputs",//+classname
            data : {"input_type":component_name,name:"component-name",class:"btn btn-primary","content":component_name,"input_field_type":"text"},
            type:"GET",
            success:function(response){
                if(response.result=="success"){
                    console.log($("div.app-main__outer").length);
                    $("div.app-main__outer > .processing").hide();
                    $("div.app-main__outer > .display_content_area").show();
                    $("div.app-main__outer > .display_content_area").html(response.html);
                }else{
                    $("div.app-main__outer > .processing").hide();
                    $("div.app-main__outer > .display_content_area").show();
                    $("div.app-main__outer > .display_content_area").html("<p> Some Error Occurred.</p>");
                }
            },
            error:function(xhr,textStatus,error){
                alert("unable to load page");
            }
        });
        }
    });

    $(".forms-section").on("click",function(){
        $("div.app-main__outer > .processing").show();
        $("div.app-main__outer > .display_content_area").hide();
        if($(this) .hasClass("forms-layout")){
            $.ajax({
                url : "/api/get-forms-list",
                data: {"form_list":1},
                type:"GET",
                success:function(response){
                    if(response.result=="success"){
                        console.log("form list loaded");
                        $("div.app-main__outer > .processing").hide();
                        $("div.app-main__outer > .display_content_area").show();
                        $("div.app-main__outer > .display_content_area").html(response.html);
                    }
                    else{
                        $("div.app-main__outer > processing").hide();
                        $("div.app-main__outer > .display_content_area").show();
                        $("div.app-main__outer > .display_content_area").html(response.html);
                    }
                },
                error:function(xhr,textStatus,error){
                    alert("unable to load page");
                }
            })
        }
        else if($(this).hasClass("forms-create")){
            $.ajax({
                url : "/api/get-forms-create",
                data:{"form_create":1},
                type:"GET",
                success:function(response){
                    if(response.result=="success"){
                        $("div.app-main__outer > .processing").hide();
                        $("div.app-main__outer > .display_content_area").show();
                        $("div.app-main__outer > .display_content_area").html(response.html);
                    }
                    else{
                        $("div.app-main__outer > .proccessing").hide();
                        $("div.app-main__outer > .display_content_area").show();
                        $("div.app-main__outer > .display_content_area").html(response.html);
                    }
                },
                error:function(xhr,textStatus,error){
                    alert("Unable to load page");
                }
            });
        }
        else{

        }
    });
    $(".users").on("click",function(){
        $("div.app-main__outer > .processing").css("display","block");
        $("div.app-main__outer > .display_content_area").css("display","none");
        let classdata= $(this).attr("class");
        classdata = classdata.replace("users ",'');
        $.ajax({
            url:"/api/"+classdata,
            type:"GET",
            data:{

            },
            success:function(response){
                console.log(response);
                if(response.result=="success"){
                    $("div.app-main__outer > .display_content_area").html("").html(response.html).css("display","block");
                    $("div.app-main__outer > .processing").css("display","none");
                }
                else{
                    $("div.app-main__outer > .display_content_area").html("").html(response.html).css("display","block");
                    $("div.app-main__outer > .processing").css("display","none");
                }
            },
            error:function(xhr,textStatus,error){
                alert("the page is not able to load");
            }
        });
    });
    pageClick();
    //''

    //''
});
function getGenerateForm(formname="get-generate-form"){
    //$(".get_form").on("click",function(){
        console.log("get form clicked");
        let classname = formname;
        $.ajax({
            url : "/api/get-generate-dynamic-form",
            data:{formname : classname},
            type:"GET",
            success:function(response){
                if(response.result){
                    $("div.app-main__outer > processing").hide();
                    $("div.app-main__outer > .display_content_area").show();
                    $("div.app-main__outer > .display_content_area").html(response.html);
                }
                else{
                    $("div.app-main__outer > processing").hide();
                        $("div.app-main__outer > .display_content_area").show();
                        $("div.app-main__outer > .display_content_area").html(response.html);
                }
            },
            error:function(xhr,textstatus,error){
                alert("unable to load page content");
            }
        });
    //});//''
}
function create_form(element){
    $("div.app-main__outer > processing").show();
    $("div.app-main__outer > .display_content_area").hide();
    $.ajax({
        url : "/api/form-create",
        type:"GET",
        data : {},
        success:function(response){
            if(response.result=="success"){
                $("div.app-main__outer > .display_content_area").html(response.html);
                $("div.app-main__outer > processing").hide();
                $("div.app-main__outer > .display_content_area").show();
            }
            else{
                $("div.app-main__outer > .display_content_area").html("<b> some error occured while loading the content</b>");
                $("div.app-main__outer > processing").hide();
                $("div.app-main__outer > .display_content_area").show();
            }
        },
        error:function(xhr,textStats,err){
            console.log("the error occurred while loading the content");
        }
    });
}
var save_form = function(form_name="generated_form"){
    let elements_list =[];
    $("form[name='"+form_name+"']").children().each(function(){
        if($(this)[0].tagName=="DIV"){
            if($(this).hasClass("container")){
                console.log("the div has class container");
                return ;
            }
            else{
            elements_list.push(getElements($(this)[0],$(this)[0].tagName +"-"+ $(this)[0].className));
            }
        }
    });//''
   //console.log(elements_list);
   //return false;//'
   
   let form_template_name = $("#form_template_name").val();
    $.ajax({
        url:"/api/form-template-save",
        type:"POST",
        data:{template_data:JSON.stringify(elements_list),name:form_template_name,template_url:$("#template_url").val()},
        success:function(response){
            if(response.result=="success"){
                alert("Template saved successfully");
            }
            else{
                alert("Failed to save the template");
            }
        },
        error:function(xhr,textStatus,error){
            console.log("the error occured while storing");
        }
    });
}
function getElements(elem,classname="",position=0){
    console.log("in get elements list start");
    let element_lst = {};
    
    for(let i=0;i<$(elem).children().length;i++){
        
        let index_element = classname==""?i:classname +"-"+ Math.ceil(Math.random()*100000);
        //if the child element it self a div element then this condition works
        if($(elem).children().get(i).tagName=="DIV"){
            let tag_with_class=$(elem).children().get(i).tagName+"-"+$(elem).children().get(i).className+"-"+Math.ceil(Math.random()*100000);
            element_lst[tag_with_class] =   getElements($(elem).children().get(i),"",(i+1));
        }
        else{
            //if the child element is not div then this will work
            if($(elem).children().get(i).tagName=="SELECT"){
                element_lst[index_element] = getAttributesandPropertiesSelect($(elem).children().get(i),(position==0?(i+1):(position)));
            }
            else
            element_lst[index_element]= getAttributesandProperties($(elem).children().get(i),(position==0?(i+1):(position)));
        }
        
    }
    //console.log(elem);
    //console.log(element_lst);
    //console.log("in get elements list complete");
    //console.log(element_lst);
    return element_lst;
}
function getAttributesandProperties(elem,position=0){
    let attributes = elem.attributes;
    let element_data = {} ;
    
//    console.log(attributes);
  //Get the properties of the element
    for(let i=0;i<attributes.length;i++){
        
        element_data[attributes[i].nodeName] = attributes[i].nodeValue;
        //console.log(attributes[i] +" "+$(elem).hasAttr(attributes[i]));
    }
    if($(elem).text()!=""){
        element_data["content"] = $(elem).text();
    }
    if(!(element_data && element_data["value"])){
        element_data["value"] = $(elem).val();
    }
    console.log("Position : "+position);
    if(position){
        element_data["position"] = position;
    }
//    console.log($(elem).text());
    element_data["tagname"]=elem.tagName.toLowerCase();
    return element_data;   
}
function getAttributesandPropertiesSelect(elem,position){
    let attributes = elem.attributes;
    let element_data = {};

    for(let i=0;i<attributes.length;i++){
        
        element_data[attributes[i].nodeName] = attributes[i].nodeValue;
        //console.log(attributes[i] +" "+$(elem).hasAttr(attributes[i]));
    }
    /*if($(elem).text()!=""){
        element_data["content"] = $(elem).text();
    }*/
    if(!(element_data && element_data["value"])){
        element_data["value"] = $(elem).val();
    }
    console.log("Position : "+position);
    if(position){
        element_data["position"] = position;
    }
//    console.log($(elem).text());
    element_data["tagname"]=elem.tagName.toLowerCase();
    let options = [];
    //fetch the values which are not empty
    $(elem).find("option").each(function(){
        if($(this).text()!="" && $(this).val()!="")
        options.push($(this).text());
    });
    element_data["options"] = options;//'
    return element_data;//'
}
function editForm(form_name){
    $.ajax({
        url : "/api/edit-form",
        type:"GET",
        data:{formname:form_name},
        success:function(response){
            if(response.result=="success"){
                console.log(response);
                 $(".app-main__outer .display_content_area").html("");
                 console.log(response.html);
                 $(".app-main__outer .display_content_area").html(response.html);
                 //$(".app-main__outer .display_content_area .form_creation_area div.class^='added_item_div'").each(function(){
                 //       console.log($(this).attr("class"));
                 //});   
                 $("form#template_form_creation > div.form-creation-area > div").each(function(){
                    console.log($(this).attr("class"));
                    if($(this).attr("class").includes("added_item_div")){
                    
                        $(this).attr("class",$(this).attr("class").replace(/added_item_div-\d+/g,"added_item_div"));
                        console.log($(this).attr("class"));
                        getEvents();   
                    }
                    }); 
            }
            else{
                alert(response.message);
            }
        },
        error:function(xhr,textStatus,error){
            alert("Unable to Load page");
        }
    });
}
function postForm(form_name=""){
    let formdata = new FormData($("form[name='"+form_name+"']")[0]);
    //if(1 ){

    //}
    let formdatasend = getFormData(form_name);
    $.ajax({
        url:"/api/"+form_name,
        type:"POST",
        data:formdatasend,
        success:function(response){
                if(response.result=="success"){
                    alert("in the success");
                    //$(".users.users-menu-list").trigger("click");
                }
                else{
                    alert(response.message);
                }
        },
        error:function(xhr,textStatus,error){

        }
    });
}
function pageClick(){
    $(".pages").off("click").on("click",function(){
        let class_linked = $(this).attr("class");
        class_linked = class_linked.replace("pages ","");
        console.log(class_linked);
        if(class_linked!=""){
            datasend = {};
            console.log(class_linked);
            if(class_linked.includes("page-disp-user")){
                class_linked = 'page-disp-user';
                datasend["page"] = $(this).data("page");
            }
            console.log(class_linked);
            //console.log(data_send);
            $(".app-main__outer .processing").css("display","block");
            $(".app-main__outer .display_content_area").css("display","none");
            $.ajax({
            url: "/api/"+class_linked,
            type:"GET",
            data : datasend ,
            success:function(response){
                $(".app-main__outer .processing").css("display","none");
                $(".app-main__outer .display_content_area").css("display","block");
                if(response.result=='success'){
                    $(".app-main__outer .display_content_area").html(response.html);
                }
                else{
                    $(".app-main__outer .display_content_area").html(response.html);
                }
            },
            error:function(xhr,textStatus,error){
                $(".app-main__outer .processing").css("display","none");
                $(".app-main__outer .display_content_area").css("display","block");
            }
        });
    }
    else{

    }
    });
}
function getFormData(form_data){
    let formdata = {};
    $("form[name='"+form_data+"'] :input").each(function(){
            if($(this)[0].tagName=="button"){

            }
            else{
                formdata[$(this).attr("name")] = $(this).val();
            }
    });
    return formdata;
}