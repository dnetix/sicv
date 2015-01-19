<div class="article_fields {{ isset($default_article) ? 'default_article' : '' }}">
    <div class="form-group">
        {{ Form::label('article_description', 'Articulo:', ['class' => 'control-label col-sm-2']) }}
        <div class="col-sm-10">
            {{ Form::text('article[]', (isset($article) ? $article->description() : null), ['class' => 'form-control', 'id' => 'article_description', 'placeholder' => 'Descripcion del art&iacute;culo']) }}
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('article_type_id[]', 'Tipo Articulo:', ['class' => 'control-label col-sm-2']) }}
        <div class="col-sm-4">
            {{ $articleTypes->printAsHTMLSelectWithStructure('article_type_id[]', (isset($article) ? $article->articleTypeId() : null), 2, 'article_type') }}
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