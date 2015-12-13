
$(document).ready(function () {
            $("#add_row").on("click", function () {
                addDynamicRows($('.tab_logic').eq(0));
            });

            // add first row
            $('.tab_logic').each(function () {
                addDynamicRows($(this));
            });

            // Sortable Code
            var fixHelperModified = function (e, tr) {
                var $originals = tr.children();
                var $helper = tr.clone();

                $helper.children().each(function (index) {
                    $(this).width($originals.eq(index).width())
                });

                return $helper;
            };

            $(".table-sortable tbody").sortable({
                helper: fixHelperModified
            }).disableSelection();

            $(".table-sortable thead").disableSelection();
        });


        function addDynamicRows(table) {
            // Dynamic Rows Code

            // Get max row id and set new id
            var newid = 0;
            $.each(table.find("tr"), function () {
                if (parseInt($(this).data("id")) > newid) {
                    newid = parseInt($(this).data("id"));
                }
            });
            // We want at most 50 rows
            if (newid > 49) {
                return;
            }

            newid++;

            var tr = $("<tr></tr>", {
                id: "addr" + newid,
                "data-id": newid
            });

            // loop through each td and create new elements with name of newid
            $.each(table.find("tbody tr:nth(0) td"), function () {
                var cur_td = $(this);

                var children = cur_td.children();

                // add new td and element if it has a name
                if ($(this).data("name") != undefined) {
                    var td = $("<td></td>", {
                        "data-name": $(cur_td).data("name")
                    });

                    var c = $(cur_td).find($(children[0]).prop('tagName')).clone().val("");
                    c.attr("name", $(cur_td).data("name") + newid);
                    c.appendTo($(td));
                    td.appendTo($(tr));
                } else {
                    var td = $("<td></td>", {
                        'text': table.find('tr').length
                    }).appendTo($(tr));
                }
            });

            // add the new row
            $(tr).appendTo(table);

            $(tr).find("td button.row-remove").on("click", function () {
                $(this).closest("tr").remove();
            });
        }


$(function() {

    $('#login-form-link').click(function(e) {
		$("#login-form").delay(100).fadeIn(100);
 		$("#register-form").fadeOut(100);
		$('#register-form-link').removeClass('active');
		$(this).addClass('active');
		e.preventDefault();
	});
	$('#register-form-link').click(function(e) {
		$("#register-form").delay(100).fadeIn(100);
 		$("#login-form").fadeOut(100);
		$('#login-form-link').removeClass('active');
		$(this).addClass('active');
		e.preventDefault();
	});

});