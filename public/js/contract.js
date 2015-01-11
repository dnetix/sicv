function addArticleFieldsContract(){
    var node = $(".article_fields.default_article").clone();
    node.find(".form-control").each(function(){
        $(this).val("");
    });
    node.appendTo("#contract_articles").removeClass("default_article")
}

function removeArticleFieldsContract(element){
    articleFields = $(element).closest(".article_fields");
    if(! articleFields.hasClass("default_article")){
        articleFields.remove();
    }
}

function setAmountExtension(amount){
    $(".extensions #amount").val(amount);
    updateValues($(".extensions #amount"));
}

function validateContract(){
    if(!$("#client_id").val()){
        addGritterNotification({
            title: "Error",
            text: "Por favor seleccione un cliente al cual asignar el contrato",
            class_name: "growl-danger"
        });
        return false;
    }
    if($(".article_fields.default_article #article_description").val().length < 3){
        addGritterNotification({
            title: "Error",
            text: "El contrato debe contener al menos un articulo",
            class_name: "growl-danger"
        });
        $(".article_fields.first #article_description").focus();
        return false;
    }
    var checkForGoldWeights = true;
    $(".article_fields").each(function(){
        if($(this).find(".article_type").val() == 2){
            var weight = $(this).find("#weight");
            if(weight.val() == "" || isNaN(weight.val())){
                weight.focus();
                checkForGoldWeights = false;
            }
        }
    });
    if(!checkForGoldWeights){
        addGritterNotification({
            title: "Error",
            text: "Por favor asignele un peso al oro",
            class_name: "growl-danger"
        });
        return false;
    }
    var amount = moneyToNumber($("#amount").val());
    if(!amount || isNaN(amount) || parseInt(amount) < 5000){
        addGritterNotification({
            title: "Error",
            text: "Por favor asignele un valor válido al contrato",
            class_name: "growl-danger"
        });
        return false;
    }

    return true;
}