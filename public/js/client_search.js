var searchTimer;

$(document).ready(function(){

    $("#client_search").on('keyup', searchClient);

});

function searchClient(){
    var searchInput = $(this);
    var terms = searchInput.val();
    var link = searchInput.data('link') || 'link';
    clearTimeout(searchTimer);
    if(terms.length > 3) {
        $("#client_search_results").html(getAjaxLoader());
        searchTimer = setTimeout(function () {
            $.ajax({
                url: SITE_BASE + "client/search",
                type: "get",
                data: {
                    terms: terms,
                    link: link
                },
                dataType: "html",
                success: function (data) {
                    $("#client_search_results").html(data);
                }
            });
        }, 400);
    }else{
        $("#client_search_results").html("");
    }
}

function clientSelected(id){
    $("#client_search_results").html(getAjaxLoader());
    $.ajax({
        url: SITE_BASE + "client/profile",
        type: "get",
        data: {
            id: id
        },
        dataType: "html",
        success: function (data) {
            $("#client_search_panel").slideUp("fast");
            $("#client_search_results").html(data);
        }
    });
}

function openClientSearchPanel(){
    $("#client_search_results").fadeOut("fast", function(){
        $(this).html("");
        $("#client_search_panel").slideDown("fast", function(){
            $("#client_search").val("").focus();
        });
        $(this).fadeIn("fast");
    });

}