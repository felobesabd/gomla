$(function () {
    var myCursor = $('.mouse-move');
    if (myCursor.length) {
        if ($('body')) {
            const e = document.querySelector('.mouse-inner'),
                t = document.querySelector('.mouse-outer');
            let n, i = 0,
                o = !1;
            window.onmousemove = function (s) {
                o || (t.style.transform = "translate(" + s.clientX + "px, " + s.clientY + "px)"), e.style.transform = "translate(" + s.clientX + "px, " + s.clientY + "px)", n = s.clientY, i = s.clientX
            }, $('body').on("mouseenter", "a, .cursor-pointer", function () {
                e.classList.add('mouse-hover'), t.classList.add('mouse-hover')
            }), $('body').on("mouseleave", "a, .cursor-pointer", function () {
                $(this).is("a") && $(this).closest(".cursor-pointer").length || (e.classList.remove('mouse-hover'), t.classList.remove('mouse-hover'))
            }), e.style.visibility = "visible", t.style.visibility = "visible"
        }
    }
});

function createDatatable(columns, ajax_url, order_option = [0, 'desc']) {
    $datatable = $('.ajax-data-table').DataTable({
        order: [order_option],
        processing: true,
        serverSide: true,
        searching: true,
        scrollX: true,
        columns: columns,
        ajax: ajax_url,
        language: {
            "emptyTable": "لا توجد بيانات في الجدول",
            "info": "عرض _START_ إلى _END_ من _TOTAL_ عنصر",
            "infoEmpty": "عرض 0 إلى 0 من 0 عنصر",
            "infoFiltered": "(تمت تصفية من _MAX_ عنصر)",
            "infoThousands": ",",
            "loadingRecords": "جاري التحميل...",
            "processing": "جاري المعالجة...",
            "search": "بحث:",
            "zeroRecords": "لا توجد سجلات مطابقة",
            "paginate": {
                "first": "الأول",
                "last": "الأخير",
                "next": "التالي",
                "previous": "السابق"
            },
            "aria": {
                "sortAscending": ": تفعيل لترتيب تصاعدي",
                "sortDescending": ": تفعيل لترتيب تنازلي"
            }
        }
    });

    $("#searchbox").keyup(function () {
        $datatable.search(this.value).draw();
    });

    return $datatable;
}
