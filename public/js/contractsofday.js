function getContractsOfDay(date){
    $("#contracts_day_panel").html(getAjaxLoader());
    $.ajax({
        url: SITE_BASE + "contract/day",
        type: "get",
        data: {
            day: date
        },
        dataType: "html",
        success: function (data) {
            $("#contracts_day_panel").html(data);
        }
    });
}