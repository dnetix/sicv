<div class="article">
    <div class="well well-sm row">
        <div class="col-md-11">
            <div>{{ $article->present()->description() }}</div>
            <div class="text-muted">{{ $article->present()->articleType() }}</div>
        </div>
        <div class="col-md-1">
            {{ Form::text('location'.$article->id(), $article->present()->location(), ['class' => 'form-control location']) }}
        </div>
    </div>
    <hr />
</div>