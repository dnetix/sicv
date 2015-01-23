function getGoldInformation(){
    $("#gold_price").html(getAjaxLoader());
    $.ajax({
        url: SITE_BASE + "goldprice",
        type: "get",
        dataType: "html",
        success: function (data) {
            $("#gold_price").html(data);
        }
    });
}

function togglePreSellout(contractId, field){
    $.ajax({
        url: SITE_BASE + "sellout/presellout",
        type: "post",
        data: {
            contract_id: contractId
        },
        dataType: "json",
        success: function (data) {
            if(data.added){
                $(field).prop('checked', true);
            }else{
                if($(field).data('remove')){
                    $("#contract_id_" + contractId).remove();
                    if($(field).data('kind')){
                        updateContractStatistics($(field).data('kind'));
                    }
                }else{
                    $(field).prop('checked', false);
                }
            }
        }
    });
}

function updateContractStatistics(kind){
    $("#contract_statistics").html(getAjaxLoader());
    $.ajax({
        url: SITE_BASE + "report/contractstatistics/" + kind,
        type: "get",
        dataType: "html",
        success: function (data) {
            $("#contract_statistics").html(data);
        }
    });
}

function selectAll(sw){
    var action = $(sw).prop('checked') ? "check" : "uncheck";
    $(".ckbox-warning input").each(function(){
        var input = $(this);
        if(action == "check"){
            if(!input.prop("checked")){
                input.prop("checked", true);
                input.trigger("change");
            }
        }else{
            if(input.prop("checked")){
                input.prop("checked", false);
                input.trigger("change");
            }
        }
    });
}