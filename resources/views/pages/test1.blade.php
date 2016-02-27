@extends('layouts.master')

@section('contents')
<input id="receipts-image-upload" class="hidden" type="file">
<div id="receipts-image">click here to change profile image</div>
<input id="submit" type="submit">

 <script>

	$('#receipts-image').on('click', function() {
    $('#receipts-image-upload').click();
});
</script>
@stop