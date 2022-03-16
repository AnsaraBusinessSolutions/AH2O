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
            $(".delete-button").css("display","none");
        }
    });
    $("#dataAlterModal").on("shown.bs.modal",function(){
        $(".edit-button").css("display","none");
        $(".delete-button").css("display","none");
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
            console.log(nodename);
            console.log(html);
            let title = "Edit "+e.target.nodeName;
             
            $("#dataAlterModal > .modal-dialog > .modal-content > .modal-body").html("").html(html);
            if(nodename=='select'){
                getEvents();
                $("#dataAlterModal > .modal-dialog > .modal-content > .modal-body > div.row").css("display","flex");
                title = "Edit Select";
            }
            if($("#dataAlterModalLabel").length){
                $("#dataAlterModalLabel").html(title);
            }
        //    else{

        //    }
            $(".edit-button").css({ "left":x, "right":y, "top":top, "display":"block"  ,"position":"absolute"});//s
            $(".delete-button").data("element",e.target.getAttribute("name")).css({"left":(position.x + position.width + 80), "right":y, "top":top, "display":"block", "position":"absolute"});
            console.log($(".edit-button"));
        }
    });
    console.log("Button : "+$("button[name='add_option']").length);

}
function previewHtml(form_name="template_form_creation"){
    if(form_name!=""){
        let html = $("form[name='"+form_name+"'] > .form-creation-area").html();
        $("#dataAlterModalLabel").html("Preview Form");
        html = "<div class='container'>"+html+"</div>";//s
        $("#dataAlterModal > div.modal-dialog > div.modal-content > div.modal-body").html(html);
        $("#dataAlterModal").modal("show");
    }
}
function editElement(node, input_name="",disabled_ap=[]){

    let attributes = $(node+"[name='"+input_name+"']")[0].attributes;
    html = "";
    if(node=="select"){
        html = getOptionsHtml("select","select");
        if($(node+"[name='"+input_name+"'] > option").length){
            $(""+node+"[name='"+input_name+"'] > option").each(function(){
                if($(this).val()!=""){
                let html_content = $("div.option_element_random").get(0).outerHTML;
                let random = Math.ceil(Math.random() * 100000);
                html_content = html_content.replaceAll("option_element_random","option_element_"+random);
                html_content = html_content.replaceAll("value","value='"+$(this).text()+"'");//''
                //$("div#dataAlterModal  > .modal-dialog > .modal-content > .modal-body").append(html_content);
                //$(".option_element_"+random).find("input").val($(this).text());
                //$(".option_element_"+random).css("display","none");
                //$(".option_element_"+random).find("input").val($(this).text());
                console.log($(this).text());
                html += html_content;
                }
                //else{
                //    html += getOptionsHtml();
                //}
            });
            console.log(html);
        }
        else
        html = getOptionsHtml(node,input_name,disabled_ap);

        if(position_available($(node+"[name='"+input_name+"']")[0])){
            html += position_list($(node+"[name='"+input_name+"']")[0]);
        }

    }
    else{
    html = "<div class='container'>";
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
    if(position_available($(node+"[name='"+input_name+"']")[0])){
        html += position_list($(node+"[name='"+input_name+"']")[0]);
    }
    html += "</div>";
    }
    return html;
}
function getOptionsHtml(node,input_name,disabled_ap=[]){
    let html = "";
    if(node=="select"){
        let random = Math.ceil(Math.random() * 1000000);
        html += "<div class=' row option_element_"+random+" col-md-12'>";
        html += "<input type='text' name='"+input_name+"_option_"+random+"' class='form-control col-md-6' value=''> &nbsp;&nbsp;";
        html += "<button type='button' name='add_option' class='btn btn-primary add_option' onclick='add_option(this)'>add</button>";
        html += "</div>";
    }
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
//Verify the possitions available for the element to change
function position_available(element){
 let previous_element =0;
 let next_element = 0;
    if($(element).parent().prev().length){
        previous_element = $(element).parent().prev().length;
    }
    if($(element).parent().next().length){
        next_element = $(element).parent().next().length;
    }
    return (previous_element + next_element);
}
//find the list of position available for the element
function position_list(element){
    let option_list = [];
    let previousall_option_list = {};
    let nextall_option_list = {};
    console.log("previous element");
    $(element).parent().prevAll().each(function(elm,elmcont){
        let nodename = $(this).children() .get(0).textContent;
        let nodeNameAdded = $(elmcont).children()?.get(0)?.getAttribute("name");
               console.log(nodeNameAdded);
        if(nodeNameAdded){
            nodename= $(this).children().get(0).nodeName;       
        previousall_option_list["insert_before_"+nodeNameAdded] = [];
        previousall_option_list["insert_before_"+nodeNameAdded].push("Insert Before "+nodename);
        }
        else{
            console.log("prev element");
            console.log($(this).children().get(0));//i
        }
    });
    console.log("next element");
    $(element).parent().nextAll().each(function(elm,elmcont){
        console.log(elmcont);
        let nodename = $(this).children().get(0).textContent;
        let nodeNameAdded = $(elmcont).children().get(0).getAttribute("name");
        nodename= $(this).children().get(0).nodeName;
        nextall_option_list["insert_after_"+nodeNameAdded] = [];
        nextall_option_list["insert_after_"+nodeNameAdded].push("Insert After "+nodename);
    });
    console.log(previousall_option_list);
    console.  log(nextall_option_list ) ;
    let html = "<select name='inserting_elements_before_after' class='form-control'>";
    html += "<option value=''>Select Position</option>";
    for(var prop in previousall_option_list){
        html += "<option value='"+prop+"'>"+previousall_option_list[prop]+"</option>";
    }
    for(var prop in nextall_option_list){
        html += "<option value='"+prop+"'>"+nextall_option_list[prop]+"</option>";
    }
    html += "</select>"; 
    temp_html = "<div class='row col-md-12'><label class='form-label'>Change to other Position</label>";
    temp_html += html;
    temp_html += "</div>";
    return temp_html;
}
function add_option(element){
    //$("button[name='add_option']").on("click",function(){
        console.log("the add_button click");
        console.log($("div.option_element_random"));
        let html = $("div.option_element_random").get(0).outerHTML;//.html();
        let random = Math.ceil(Math.random() * 100000);
        console.log(html);
        html = html.replaceAll("option_element_random","option_element_"+random);

        console.log(html);
        let value = $(element).siblings("input").val();
        if($("#dataAlterModal").is(":visible")){
            //$().append(html);
            $(html).insertAfter($("#dataAlterModal .modal-body div[class*='option_element_']:last"));
        }
        else{
            $(html).insertAfter($(element));
        }

        $(".option_element_"+random).css("display","flex");
        $(".option_element_"+random).find("input").val(value);
        $(element).siblings("input").val("");
        //});//''
}
function saveElement(element,nodename){
    let attributes = ["content"];
    console.log(element+" "+nodename);
    if(nodename=='select'){
        html = "<option value=''>Please Select</option>";
        $("#dataAlterModal .modal-body").find("div.row").each(function(){
                if($(this).find("button[name='remove_option']").length){
                    let input_value = $(this).find("input").val();
                    html += "<option value='"+input_value.replaceAll(" ","_")+"'>"+input_value+"</option>";
                }
        });
        $("select[name='"+element+"']").html(html);
    }
    else{
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
    let position_change = $("select[name='inserting_elements_before_after']").val();
    if(position_change==""){
        console.log("no position change is required");
    }
    else if(position_change.startsWith("insert_before")){
            console.log(position_change);
            let position_changefor_param = position_change.replace("insert_before_","");
            console.log("From Element :"+nodename+"[name='"+position_changefor_param+"']");
            console.log("To Element : "+"[name='"+position_changefor_param+"']");
            $($(nodename+"[name='"+element+"']").parent("div")).insertBefore($("[name='"+position_changefor_param+"']").parent("div"));
    }
    else if(position_change.startsWith("insert_after")){
        let position_changeparam = position_change.replace("insert_after_","");
        $($(nodename+"[name='"+element+"']").parent("div")).insertAfter($("[name='"+position_changeparam+"']").parent("div"));
    }
    else{
            console.log("Position change is not able to be done");
    }
    $("#dataAlterModal").modal("hide");
}
function removeElement(element){
    console.log(element);
    if($(element).length){
        //let parent_element = $(element).parent("row");
        $(element).parent("div.row").remove();
    }
    else{
        console.log("element is not available");
    }
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
