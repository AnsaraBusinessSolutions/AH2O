<style type='text/css'>
.form-creation-area{
    border-top:0.8px solid blue;
    border-left:0.8px solid blue;
    border-right:0.8px solid blue;
    border-bottom:0.8px solid blue;
    min-width:100vh;
    min-height:100vh;
}
.modal-backdrop
{
    display:none;
}
.modal{
    z-index: 6000;
}
</style>
<script src="js/form-page-content.js"></script>
<div class="container">
    <!-- <div class="row"> -->
    <div class="row">
    <h2>Update Form</h2>
    </div>
    <!-- </div> -->
    <div class="row">
        <div class="col-md-4">
                    <label class="form_label" for="form_template_name">Form Name:</label>
                    <input type="text" name="form_template_name" id="form_template_name" value="{{ $formname }}" disabled>
        </div>
        <div class="col-md-4">
                     <button class="btn btn-primary" name="save_form_template" id="save_form_template" value="Save">Update Form</button>
                     <button name="clear-all" class="clear-all btn btn-primary">Clear All</button>
                    </div>
        
    </div><br>
    <div class="row">
            <div class="col-md-8">
            <form name="template_form_creation" id="template_form_creation" action="#">
                <div class="form-creation-area">
                    &nbsp;
                    {!! $html !!}
                </div>
            </form>
            </div>
            <div class="col-md-1">
                        <button class="btn btn-primary" name="preview" id="preview" onclick="previewHtml('template_form_creation')">Preview</button> 
                      <input type="hidden" name="template_url" id="template_url" value="">
            </div>    
            <div class="col-md-3">
            <div class="pull-right">
                <ul class="list-group">
                    <li class="list-group-item button" data-element="buttons" data-element-type='input' data-class='btn btn-primary' data-placeholder='Button'>button</li>
                    <li class="list-group-item label" data-element="label" data-element-type='output' data-class='form-label' data-placeholder='Label'>label<!--s--></li>
                    <li class="list-group-item input" data-element="inputs" data-element-type='input' data-class='form-control' data-placeholder='Input'>input</li>
                    <li class="list-group-item textarea" data-element="textarea" data-element-type='input' data-class='form-control' data-placeholder='TextArea'>textarea</li>
                    <li class="list-group-item email" data-element="inputs-email" data-element-type='input' data-class='form-control' data-placeholder='Email'>email input</li>
                    <li class="list-group-item password" data-element="inputs-password" data-element-type='input' data-class='form-control' data-placeholder='Password'>password input</li>
                    <li class="list-group-item select" data-element="inputs-select" data-element-type='input' data-class='form-control' data-placeholder='Selection Dropdown'>select</li>
                </ul>
            </div>
            </div>
            <button type="button" class='btn btn-link edit-button col-md-1'   style='display:none'>Edit</Button>
            <button type="button" class='btn btn-link delete-button col-md-1'   style='display:none'>Delete</Button>
    </div>
    <div class='col-md-6 template_url_content_get' style="display:none">
            <label class='form-label'>Enter URL to Store Form Data</label>
            <input type='text' class="form-control" name='template_form_url' id='template_form_url' value="{{ isset($url)?$url:'' }}">
    </div>
    <div class='row option_element_random col-md-12' style="display:none">
          <input    type="text" name="select_option_element_random" class="form-control col-md-6" value="">&nbsp;&nbsp;
          <button name="remove_option" class='btn btn-danger' onclick='removeElement(this)' ><i class='pe-7s-trash'></i></button>
    </div>
      
<!-- Modal -->
                                    

  @include("modal.modal_file")

</div>
