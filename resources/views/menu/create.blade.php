@extends("layouts.app")

@section("content")
<div class="container">
    <div class="row justify-center">
    <div class="col-md-9">
        <form class="" action="{{ route('createMenuData') }}" id="menu_create_manage" method="POST">
            @foreach($menu_list as $direct_menu)
            <div class="row menu_item ">
                <div class="col-md-2">    
                    <label class="lbl-primary" for="menu_name">Menu Name</label>
                    <input type="text" class="form-control" name="menu_name" id="menu_name" value="{{ $direct_menu->menu_name }}">
                </div>
                <div class="col-md-2">    
                        <label class="lbl-primary" for="menu_icon">Menu Icon</label>
                        <input type="text" class="form-control" name="menu_icon" id="menu_icon" value="{{ $direct_menu->menu_icon }}">
                </div>       
                <div class="col-md-2">    
                    <label class="lbl-primary" for="menu_colour">Menu Color</label>
                    <input type="text" class="form-control" name="menu_colour" id="menu_colour" value="{{ $direct_menu->menu_colour }}">
                </div>
                <div class="col-md-4">    
                    <label class="lbl-primary" for="menu_name">Menu Parent</label>
                        <select name="menu_parent" id="menu_parent" class="form-control">
                            <option value="0">Select Menu</option>
                            @foreach($menu_direct_list as $key=> $menu_direct)
                                <option value="{{ $menu_direct }}" {{ $direct_menu->menu_parent==$menu_direct?"selected":"" }}><b>{{ $menu_direct }}</b></option>
                            @endforeach
                        </select>
                </div>
                <div class="col-md-2">
                    <label class="lbl-primary" for="menu_access">Menu Access</label>
                    <select name="menu_access" id="menu_access" class="form-control" multiple>
                            <option value="0">Select User</option>
                            <option value="1" {{ in_array(1,$direct_menu->menu_access)?"selected":"" }}>Admin</option>
                            <option value="2" {{ in_array(2,$direct_menu->menu_access)?"selected":"" }}>Manager</option>
                            <option value="3" {{ in_array(3,$direct_menu->menu_access)?"selected":"" }}>User</option>
                    </select>
                </div>
            </div>
            <br>
            @endforeach
            <div class="row menu_item ">
                <div class="col-md-2">    
                    <label class="lbl-primary" for="menu_name">Menu Name</label>
                    <input type="text" class="form-control" name="menu_name" id="menu_name" value="">
                </div>
                <div class="col-md-2">    
                        <label class="lbl-primary" for="menu_icon">Menu Icon</label>
                        <input type="text" class="form-control" name="menu_icon" id="menu_icon" value="">
                </div>       
                <div class="col-md-2">    
                    <label class="lbl-primary" for="menu_colour">Menu Color</label>
                    <input type="text" class="form-control" name="menu_colour" id="menu_colour" value="">
                </div>
                <div class="col-md-4">    
                    <label class="lbl-primary" for="menu_name">Menu Parent</label>
                        <select name="menu_parent" id="menu_parent" class="form-control">
                            <option value="0">Select Menu</option>
                            @foreach($menu_direct_list as $key=> $menu_direct)
                                <option value="{{ $key }}" ><b>{{ $menu_direct }}</b></option>
                            @endforeach
                        </select>
                </div>
                <div class="col-md-2">
                    <label class="lbl-primary" for="menu_access">Menu Access</label>
                    <select name="menu_access" id="menu_access" class="form-control" multiple>
                            <option value="0">Select User</option>
                            <option value="1">Admin</option>
                            <option value="2">Manager</option>
                            <option value="3">User</option>
                    </select>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-4">
                <button name="submit_form_menu" id="submit_form_menu" type="button">Submit</button>&nbsp;&nbsp;
                <button name="clear_form" id="clear_form" type="button">Cancel</button>
                </div>
            </div>
        </form>
    </div>
    </div>
</div>
<script type="application/javascript">
$(document).ready(function(){
    let submenu = {};
    var string_of_mapping = "";
    var string_of_mapping_object = {};
    var string_of_mapping_array = [];
    var mapping_parent_existing_array = {};
    var mapping_to_specific_parameters = {};
   /* $("select[name='menu_parent']").each(function(){
        //submenu.push($(this).attr("value"));
        let current_menu = $(this).attr("value");
        if(submenu[$(this).attr("value")].length==0)
        submenu[$(this).attr("value")]={};
        

        loop_var = true;
        while(loop_var){

        }
        
    });*/
    getItems(0);
    console.log(string_of_mapping);
    console.log(string_of_mapping_object);
    console.log(string_of_mapping_array);
    
    for(var prop in string_of_mapping_array){
        let element_fivalidation = string_of_mapping_array[prop];
        let split = element_fivalidation.split(":");
        if(split[0]==0){
            mapping_parent_existing_array[split[0]] = {};
            mapping_parent_existing_array[split[0]][split[1]] = [];
        }
        else if(split[1]!="" && mapping_parent_existing_array[split[0]][split[1]].length==0){
                mapping_parent_existing_array[split[0]][split[1]] = getelementsfrom(string_of_mapping_array,prop,prop + split[2]);
        }
       
       /* if(mapping_parent_existing_array[split[0]]!==undefined){
            mapping_parent_existing_array[split[0]][split[1]] = [];
        }
        else{
            
            
            mapping_parent_existing_array[split[0]] = {};//''
            mapping_parent_existing_array[split[0]][split[1]] = [];
            
        }*/
       
    }
    
    function getelementsfrom(string_of_mapping_array,start,complete)){
        let element = [];
        for(let i=start;i<complete;i++){
            let elm_add = string_of_mapping_array[i];
             elm_add = elm_add.split(":");
            
        }

    }
    
    console.log(mapping_parent_existing_array);
    function getItems(menu_name,colon=":",parent_menu="0"){
   string_of_mapping += menu_name+colon;
   string_of_mapping_object[menu_name+colon] = {};
   string_of_mapping_array.push(parent_menu+colon+menu_name+colon+$("select[name='menu_parent'] > option[value='"+menu_name+"']:selected").length);
   if($("select[name='menu_parent'] > option[value='"+menu_name+"']:selected").length){
       let length = $("select[name='menu_parent'] > option[value='"+menu_name+"']:selected").length;
       let i=0;
       for(;i<length;i++){
        
       getItems($($("select[name='menu_parent'] > option[value='"+menu_name+"']:selected")[i]).parents("div.menu_item").find("input#menu_name").val(),":",menu_name);
       }
       if(i==length && length>2){
           string_of_mapping += ";";
       }
       }
       else{
           string_of_mapping += ";";
       }
        
}
console.log(submenu);
    $("#submit_form_menu").off("click").on("click",function(){
        var formData = new FormData($("form#menu_create_manage")[0]);
        var object = {};
        var objects = [];
        formData.forEach(function(value, key){
            console.log(key);
        object[key] = value;
        objects.push(object);
        object = {};
        });
        object = {};
        let loop = 0;
        $("div.menu_item").each(function(){
            console.log($(this));
            let menu_name = "";
            let menu_colour = "";
            let menu_icon = "";
            let menu_parent = "";
            menu_name = $(this).find("#menu_name").val();
            menu_colour = $(this).find("#menu_colour").val();
            menu_icon = $(this).find("#menu_icon").val();
            menu_parent = $(this).find("#menu_parent").val();
            menu_access = $(this).find("#menu_access").val();
            menu_submenu_list = {};
            let sub_menu = getSubMenu(menu_name);
            if(menu_name!="" && menu_name.length>0){
                object[loop++] = {"menu_name":menu_name, "menu_colour":menu_colour,"menu_icon":menu_icon,"menu_parent":menu_parent,"sub_menu":sub_menu,"menu_access":menu_access};
            }
        });
            
        var json = JSON.stringify(object);
        console.log(json);
        
        $.ajax({
            url : "/api/menu_save",
            type:"POST",
            data : {
                settingname:"sidebar",
                settingvalue:json
            },
            success:function(response){
                if(response.id==1){
                    location.reload();
                }
                else{
                    alert(response.message);
                }
            },
            error:function(xhr,textStatus,error){

            }
        })
    });''
});''
function getSubMenu(menu_name){
    if(menu_name){
        let sub_menu_name = [];
        $("select[name='menu_parent']").each(function(){
           if(menu_name == $(this).val()){
               let element = $(this).parents("div.menu_item");
               let menu_name = $(element).find("#menu_name").val();
               let menu_icon = $(element).find("#menu_icon").val();
               let menu_colour = $(element).find("#menu_colour").val();
               let menu_access = $(element).find("#menu_access").val();
               let menu_parent = $(this).val();
               sub_menu_name.push({"menu_name":menu_name,"menu_icon":menu_icon,"menu_colour":menu_colour,"menu_parent":menu_parent,"menu_access":menu_access});
            
           }
        });
        console.log("sub menu name"); 
        console.log(sub_menu_name);
        return sub_menu_name;
    }
    return [];
}
</script>
@endsection
