$(document).ready(function(){
    $("#percentage").on('keyup', updateContractAmount);
});

function addArticleFieldsContract(){
    var node = $(".article_fields.default_article").clone();
    node.find(".form-control").each(function(){
        $(this).val("");
    });
    node.find(".money").each(function(){
        setMoneyListeners(this);
    });
    node.appendTo("#contract_articles").removeClass("default_article");
}

function removeArticleFieldsContract(element){
    articleFields = $(element).closest(".article_fields");
    if(! articleFields.hasClass("default_article")){
        articleFields.remove();
    }
}

function updateContractAmount(){
    var total = 0;
    $(".article_amount").each(function(){
        total += parseInt(moneyToNumber($(this).val()));
    });
    $("#contract_amount").val(numberToMoney(total));
    $("#contract_amount").trigger('keyup');

    $("#payment").val(numberToMoney(Math.ceil(total * ($("#percentage").val() / 100))));
}

function updateArticleLocation(element){
    var location = $(element);
    $.ajax({
        url: SITE_BASE + "article/location",
        type: "post",
        data: {
            id: location.data('article'),
            location: location.val()
        },
        dataType: "json",
        success: function (data) {
            if(data.isOk){
                location.addClass('btn-success');
            }else{
                location.addClass('btn-danger');
            }
        }
    });
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
    var contract_amount = moneyToNumber($("#contract_amount").val());
    if(!contract_amount || isNaN(contract_amount) || parseInt(contract_amount) < 5000){
        addGritterNotification({
            title: "Error",
            text: "Por favor asignele un valor válido al contrato",
            class_name: "growl-danger"
        });
        return false;
    }

    return true;
}