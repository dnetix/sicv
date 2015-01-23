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
                $(field).prop('checked', false);
            }
        }
    });
}