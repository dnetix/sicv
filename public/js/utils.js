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