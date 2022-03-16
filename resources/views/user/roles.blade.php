<style type='text/css'>
.modal-backdrop
{
    display:none;
}
.modal{
    z-index: 6000;
}
</style>
<script type='application/javascript' src='{{ asset("js/role.js") }}'></script>
<div class='container'>
        <div class='col-md-9'>
                <div class='row'>    
                    <div class='col-md-12'>
                        <h4>User Roles List</h4>
                        <button class='btn btn-primary pull-right' name='create_role'>Create Role</button>
                    </div>
                </div>
                <br>
                <div class='row'>
                        {!! $html !!}
                </div>
        </div>
</div>
@include("modal.modal_file")