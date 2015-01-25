function decreaseByPercentage(selector, percentage){
    $(selector).each(function(){
        var input = $(this);
        var value = moneyToNumber(input.val());
        input.val(numberToMoney(Math.ceil(value * (1 - (percentage / 100)))));
    });
}

function createSellout(){
    if(confirm("Desea realizar la saca, registrando los productos?")){
        $("#note").val($("#show_note").val());
        $("#frm_sellout").submit();
    }
}