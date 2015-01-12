var searchTimer;

$(document).ready(function(){
    $("#client_search").on('keyup', searchClient);
    $("#client-notes form").ajaxForm({
        beforeSubmit: function(){
            $("#client-notes form .form-group").fadeOut("fast");
        },
        clearForm: true,
        success: function(data){
            $("#client-notes-list").prepend(data);
            $("#client-notes form .form-group").fadeIn("fast");
        }
    });
    $("#client-notes form .submit-ajax").removeClass('disabled');
});

function searchClient(){
    var searchInput = $(this);
    var terms = searchInput.val();
    var link = searchInput.data('link') || 'link';
    clearTimeout(searchTimer);
    if(terms.length > 3) {
        $("#client_results").html(getAjaxLoader());
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
                    $("#client_results").html(data);
                }
            });
        }, 400);
    }else{
        $("#client_results").html("");
    }
}

function clientSelected(id){
    $("#client_results").html(getAjaxLoader());
    $.ajax({
        url: SITE_BASE + "client/profile",
        type: "get",
        data: {
            id: id
        },
        dataType: "html",
        success: function (data) {
            $("#client_search_panel").slideUp("fast");
            $("#client_results").html(data);
            if($("#client-notes-wrapper").length){
                $("#client-notes-wrapper").html(getAjaxLoader());
                $.ajax({
                    url: SITE_BASE + "client/notes",
                    type: "get",
                    data: {
                        client_id: id
                    },
                    dataType: "html",
                    success: function (data) {
                        $("#client-notes-wrapper").html(data);
                    }
                });
            }
        }
    });
}

function openClientSearchPanel(){
    $("#client_results").fadeOut("fast", function(){
        $(this).html("");
        $("#client_search_panel").slideDown("fast", function(){
            $("#client_search").val("").focus();
        });
        $(this).fadeIn("fast");
    });
}

function openClientEditPanel(client_id){
    $("#client_results").html(getAjaxLoader());
    $.ajax({
        url: SITE_BASE + "client/profile",
        type: "get",
        data: {
            id: client_id,
            edit: true
        },
        dataType: "html",
        success: function (data) {
            $("#client_results").html(data);
        }
    });
}

function updateClientInformation(){
    $.ajax({
        url: SITE_BASE + "client/edit",
        type: "post",
        data: {
            id: $("#client_id").val(),
            name: $("#name").val(),
            cell_number: $("#cell_number").val(),
            phone_number: $("#phone_number").val(),
            address: $("#address").val()
        },
        dataType: "html",
        success: function (data) {
            $("#client_results").html(data);
        }
    });
}

