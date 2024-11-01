jQuery(function ($) {

    $(document).on("click", "input#publish", function (e) {
        if (0 == $("input#title").val().length) {
            e.preventDefault();
            alert("Please fill in a title");
            return false;
        }

        if ("-1" === $("#select_product").val()) {
            e.preventDefault();
            alert("Please select a product");
            return false;
        }
    });

    $(document).on('click', "input#submit-answer", (function (e) {
        e.preventDefault();

        $("#question-content-div .error").empty();
        $("#question-content-div .success").empty();

        if ($("#respond-to-question").val().length == 0) {
            $("#question-content-div").prepend('<span class="error">' + ywqa.empty_answer + '</span>');
            return false;
        }

        var data = {
            'action': 'submit_answer',
            'question_id': $("#post_ID").val(),
            'answer_content': $("#respond-to-question").val(),
            'product_id': $("input#product_id").val()
        };

        var clicked_item = $(this);

        clicked_item.block({
            message: null,
            overlayCSS: {
                background: "#fff url(" + ywqa.loader + ") no-repeat center",
                opacity: .6
            }
        });

        $.post(ywqa.ajax_url, data, function (response) {
            //  retrieve new status and set "selected" CSS class
            if (1 == response.code) {
                $("#respond-to-question").after('<span class="success">' + ywqa.answer_success + '</span>')
            }
            else if (-1 == response.code) {
                $("#respond-to-question").after('<span class="error">' + ywqa.answer_error + '</span>')
            }

            clicked_item.unblock();
        });
    }))
});




