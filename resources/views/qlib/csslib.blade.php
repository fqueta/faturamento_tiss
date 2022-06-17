<link rel="stylesheet" href="{{url('/')}}/vendor/summernote/summernote.min.css">
<link rel="stylesheet" href="{{url('/')}}/vendor/venobox/venobox.min.css">
<link rel="stylesheet" href="{{url('/')}}/css/jquery-ui.min.css">
<link rel="stylesheet" href="{{url('/')}}/css/lib.css">
@if (isset($_GET['popup']) && $_GET['popup'])
<style>
    aside,.wrapper nav{
        display: none;
    }
    .content-wrapper{
        margin-left:0px !important;
    }

</style>
@endif
<div id="preload">
    <div class="lds-dual-ring"></div>
</div>
