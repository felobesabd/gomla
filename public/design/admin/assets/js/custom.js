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

document.addEventListener('DOMContentLoaded', function () {
    const unitSelect = document.getElementById('unit_id');
    const numberInput = document.getElementById('number');
    const qtyInput = document.getElementById('qty');

    function calculateQuantity() {
        const selectedUnit = unitSelect.options[unitSelect.selectedIndex];
        const unitWeight = selectedUnit ? parseFloat(selectedUnit.getAttribute('data-weight')) : 0;
        const number = parseFloat(numberInput.value) || 0;
        const quantity = (unitWeight * number).toFixed(2);
        qtyInput.value = quantity;
    }

    unitSelect.addEventListener('change', calculateQuantity);
    numberInput.addEventListener('input', calculateQuantity);

    // Initial calculation
    calculateQuantity();
});

/* --------------------------------------------------------------- Reports ------------------------------------------------------ */
// add new div
function cloneAndReset(selector, select2Class, insertBeforeSelector, select2Placeholder, select2Width = '100%') {
    var clonedElement = $(selector).first().clone();

    // Remove previous Select2 containers and reset input fields
    clonedElement.find('.select2-container').remove();
    clonedElement.find('input, select, textarea').val('');
    // clonedElement.find('.serial_no').val('');
    clonedElement.find('.diesel-total-cost').text('');
    clonedElement.find('.warranty_date_end').removeClass('hasDatepicker').removeAttr('id').removeClass('datepicker');
    clonedElement.find('.warranty_date_end').datepicker({
        dateFormat: "dd-mm-yy",
        changeYear: true,
        changeMonth: true,
        weekStart: 0,
        calendarWeeks: true,
        autoclose: true,
        todayHighlight: true,
        rtl: true,
        orientation: "auto"
    });

    // Reinitialize Select2 for the cloned select elements
    clonedElement.find(select2Class).select2({
        placeholder: select2Placeholder,
        allowClear: true,
        width: select2Width
    });

    // Insert the cloned element before the specified element
    clonedElement.insertBefore(insertBeforeSelector);
}

// Function to handle delete logic for dynamic elements
function handleDelete(deleteButtonSelector, containerSelector, inputFieldSelector) {
    $(document).on('click', deleteButtonSelector, function () {
        const index = $(this).data('index');
        const id = $(this).data('id');
        const itemsTracks = $(this).data('item_tracking');
        const route = $(this).data('route');

        if (itemsTracks) {
            $.ajax({
                url: route,
                data: {},
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    response(data);
                }
            });
        }

        if (index !== undefined && index !== null) {
            let deleteIndexes = $(inputFieldSelector).val();
            // deleteIndexes = deleteIndexes ? JSON.parse(deleteIndexes) : [];
            deleteIndexes = JSON.parse(deleteIndexes);
            deleteIndexes[index] = id;
            $(inputFieldSelector).val(JSON.stringify(deleteIndexes));
        }

        // Remove the closest container element
        $(this).closest(containerSelector).remove();

        // Check if there are any remaining containers
        if ($(containerSelector).length === 0) {
            // If no containers are left, remove the #add-item-details element
            $('#add-item-details').remove();
        }
    });
}

$('#add-sales-items').on('click', function () {
    cloneAndReset('.report-items', '.report-items-select', '#add-sales-items', "Choose a part number");
});

handleDelete('.delete-sales-items', '.report-items', '.delete-rep-items-indexes');
