$(".category").click(function () {
    let categoryId = $(this).attr("category_id");
    let selectedCategoryLink = $(".selected-category")
    selectedCategoryLink.html($(this).html())
    $.ajax({
        method: "GET",
        url: "/properties/" + categoryId,
        success: function (data) {
            let cardBody = $(".popup_add-product .card-body");
            if (cardBody != null) {
                let properties = '';
                for (let i = 0; i < data.length; i++) {
                    properties +=
                        '<li class="align-items-baseline row">\n' +
                        '    <p class="col-sm">' + data[i]['name'] + '</p>\n' +
                        '    <label for="inputProperty_' + data[i]['id'] + '" class="visually-hidden"/>\n' +
                        '    <input type="text" class="form-control col-sm" name="inputProperty_' + data[i]['id'] +
                            '" placeholder="Значение">\n' +
                        '</li>'
                }
                cardBody.html(properties);
            }

            let selectedCategoryInput = $("#selected-category-input");
            if (selectedCategoryInput != null) {
                selectedCategoryInput.val(categoryId);
            }
        },
        error: function (jqXHR, text, error) {
            alert(JSON.parse(jqXHR.responseText)['message']);
        },
    });
});

