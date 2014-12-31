@if (isset($flash_notification))
<div class="{{ $flash_notification['overlay'] }}{{ $flash_notification['type'] }}">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    {{ $flash_notification['title'] ? '<h4>'.$flash_notification['title'].'</h4>' : '' }}
    {{ $flash_notification['message'] }}
</div>
@endif