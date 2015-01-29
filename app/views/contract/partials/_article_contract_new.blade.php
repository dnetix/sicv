<div class="article_fields {{ isset($default_article) ? 'default_article' : '' }}">
    <div class="form-group">
        {{ Form::label('', 'Articulo:', ['class' => 'control-label col-sm-2']) }}
        <div class="col-sm-6">
            {{ Form::text('description[]', (isset($article) ? $article->description() : null), ['class' => 'form-control', 'id' => 'article_description', 'placeholder' => 'Descripcion del art&iacute;culo']) }}
        </div>

        {{ Form::label('article_amount', 'Valor:', ['class' => 'control-label col-sm-1']) }}
        <div class="col-sm-3">
            {{ Form::text('article_amount[]', (isset($article) ? $article->present()->articleAmount() : null), ['class' => 'form-control money article_amount', 'id' => 'article_amount', 'placeholder' => 'Valor', 'onkeyup' => 'updateContractAmount()', 'autocomplete' => 'off']) }}
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('article_type_id[]', 'Tipo Articulo:', ['class' => 'control-label col-sm-2']) }}
        <div class="col-sm-4">
            {{ $articleTypes->asHTMLSelect('article_type_id[]', (isset($article) ? $article->articleTypeId() : \SICV\Articles\ArticleType::GOLD_ID), ['id' => 'article_type', 'class' => 'form-control']) }}
        </div>

        {{ Form::label('weight[]', 'Peso:', ['class' => 'control-label col-sm-2']) }}
        <div class="col-sm-2">
            {{ Form::text('weight[]', (isset($article) ? $article->weight() : null), ['class' => 'form-control', 'id' => 'weight', 'placeholder' => 'gramos']) }}
        </div>
        <div class="col-sm-2 text-right">
            <input type="button" tabindex="-1" class="btn btn-danger" onclick="removeArticleFieldsContract(this)" value="x">
            <input type="button" tabindex="-1" class="btn btn-primary" onclick="addArticleFieldsContract()" value="+">
            {{ Form::hidden('article_id[]', (isset($article) ? $article->id() : null), ['class' => 'article_id']) }}
        </div>
    </div>
    <hr />
</div>