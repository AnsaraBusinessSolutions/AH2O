<style type='text/css'>
.modal-backdrop
{
    display:none;
}
.modal{
    z-index: 6000;
}
.modal-dialog{
    max-width:800px;
    margin : 1.75rem auto;
    min-width: 690px;
    margin-top:5rem;
}
</style>
<script type='application/javascript' src='{{ asset("js/page.js") }}'></script>

<div class='container'>
    
    <div class='col-md-9'>
            <div class="row">
                    <h4>Create A Dynamic Webpage</h4>
            </div>
            <div class="row">
                <div class="col-md-6">
                        <label class='form-label'>Form Title</label>
                        <input type='text' class='form-control' name='title' >
                </div>
            </div>
            <div class='row'>
                    <div class='col-md-6'>
                            <label class='form-label' for='form-linked'>Form Linked</label>
                            <input type='radio' name='form-option' id='form-linked' value='form linked' >
                            <label class='form-label' for='form-content'>Form Content</label>
                            <input type='radio' name='form-option' id='form-content' value='form content' >
                    </div>
            </div>
            <div class="row form-linked d-none">
                <div class="col-md-6">
                    <label class='form-label' for='form-option-content'>Form Linked</label>
                    <select name='content' id='form-option-content' class='form-control'>
                        <option value=''>Select Option</option>
                        @foreach ($template as $key => $templ)
                        <option value="{{ $key }}">{{ $templ }}</option>              
                        @endforeach
                    </select>
                </div>
            </div>
            <div class='row form-content d-none'>
                    <div class="col-md-6">
                            <label class='form-label' for='form-content-data'>Form Content</label>
                            <textarea name='content' id='form-content-data' rows='15' cols='90' value=''></textarea>
                    </div>
            </div>
            <div class='row form-linked d-none'>
                <div class='col-md-6'>
                    <button name='preview_page' id='preview_page' class='btn btn-primary'>Preview Page</button>
                </div>
            </div>    
            <!--
            <div class='row form-linked d-none'>
                <div class='col-md-6'>
                        <label class='form-label'>Permissions</label>
                        <div class='row'>
                                <select name='permissions'>
                                        <option value=''> </option>
                                </select>
                        </div>
                </div>
            </div>
            -->
            <br>
            <div class='row'>
                <div class='col-md-6'>
                <button name='button' class='btn btn-primary' type='button' id='save_page'>Save Page</button>
                <button name='reset' class='btn btn-default' type='reset' id='clear_page'>Cancel</button>
                </div>
            </div>
    </div>

</div>
@include("modal.modal_file")
