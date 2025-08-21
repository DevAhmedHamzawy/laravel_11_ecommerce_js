<script>
    $(document).on('change', '#product_id', function() {
        getProduct();
    });

    $(document).on('keyup change', '#qty, #discount_amount, #discount_sort', function() {
        calculateTotalPrice();
    });

    function calculateTotalPrice() {
        let product_id = Number($('#product_id').val());
        let qty = Number($('#qty').val());
        let price = Number($('#unit_price').val());

        if (price > 0 && product_id > 0) {
            let total = price * qty;
            let discount_sort = Number($('#discount_sort').val());
            let discount_amount = Number($('#discount_amount').val()) || 0;

            $('#price').val(total);

            if (discount_sort === 0) {
                discount_amount = (discount_amount / 100) * total;
            }

            total -= discount_amount;
            $('#price_after_discount').val(total.toFixed(2));
        }
    }

    function getProduct() {
        let product_id = Number($('#product_id').val());
        let qty = Number($('#qty').val());
        let url = '{{ route('admin.get_product') }}';

        if (product_id > 0) {
            $.ajax({
                url: url,
                method: 'GET',
                data: {
                    product_id,
                    qty
                },
                success: function(data) {
                    $('#unit_price').val(data.buying_price);
                    $('#price').val(data.price_qty);
                    calculateTotalPrice();
                }
            });
        }
    }


    function resetAttributeIndexes() {
        let newIndex = 0;

        $('#table tbody tr').each(function() {
            // داخل كل صف منتج
            $(this).find('input[name^="attribute_ids["], input[name^="attribute_parents["]').each(function() {
                let name = $(this).attr('name');

                // ريّح دماغنا واستبدل الرقم اللي بين القوسين بأي رقم
                name = name.replace(/\[\d+\]/, `[${newIndex}]`);

                $(this).attr('name', name);
            });

            newIndex++;
        });

    }

    var index = 0;

    function addItem() {
        let product_id = $('#product_id').val();
        if (product_id === '{{ trans('purchase.choose') }}') {
            alert('{{ trans('purchase.choose_product') }}');
            return;
        }

        let qty = $('#qty').val();
        if (qty === '') {
            alert('{{ trans('purchase.choose_qty') }}');
            return;
        }


        let attributesHTML = '';
        let hiddenInputs = '';

        // خد كل الـ selects الخاصة بالخصائص
        $('.attribute-select').each(function() {
            let attributeName = $(this).data('attribute-name');
            let attributeParentId = $(this).data('attribute-parent-id');
            let attributeValueText = $(this).find('option:selected').text();
            let attributeValueId = $(this).val();

            if (attributeValueId != '{{ trans('purchase.choose') }}') {
                // لو المستخدم اختار قيمة
                attributesHTML += `<strong>${attributeName}:</strong> ${attributeValueText}<br>`;

                @if (isset($edit))
                    window.currentRowIndex = $('#table tbody tr:not(.total_data)').length;

                    hiddenInputs +=
                        ` <input type="hidden" name="attribute_ids[${window.currentRowIndex}][]" value="${attributeValueId}">
                      <input type="hidden" name="attribute_parents[${window.currentRowIndex}][]" value="${attributeParentId}">`;
                @else
                    hiddenInputs +=
                        ` <input type="hidden" name="attribute_ids[${index}][]" value="${attributeValueId}">
                      <input type="hidden" name="attribute_parents[${index}][]" value="${attributeParentId}">`;
                @endif

            }

        });

        @if (isset($edit))
            resetAttributeIndexes();
        @endif

        if (attributesHTML === '') {
            alert('{{ trans('purchase.choose_attributes') }}');
            return;
        }

        index++;

        let unit_price = $('#unit_price').val();

        let discount_sort = $('#discount_sort :selected').val();
        let discount_amount = $('#discount_amount').val();
        let price_after_discount = $('#price_after_discount').val() || $('#price').val();
        let vat = $('#vat').val();
        let url = '{{ route('admin.get_product') }}';

        if ($('#discount_amount').val() == 0) discount_amount = 0;

        discount_sort == 0 ? discount_amount += "%" : discount_amount +=
            "ج.م";

        // Hidden inputs
        $('.table tbody').append(`
            <input type="hidden" name="unit_prices[]" id="unit_price_${index}" value="${unit_price}">
            <input type="hidden" name="discount_sorts[]" id="discount_sort_${index}" value="${discount_sort}">
            <input type="hidden" name="discount_amounts[]" id="discount_amount_${index}" value="${discount_amount}">
            <input type="hidden" name="prices_after_discount[]" id="price_after_discount_${index}" value="${price_after_discount}">
            <input type="hidden" name="qtys[]" id="qty_${index}" value="${qty}">
        `);

        $('.table tbody').append(hiddenInputs);


        $.ajax({
            url: url,
            method: 'GET',
            data: {
                product_id,
                qty
            },
            success: function(data) {
                $('.table tbody').append(
                    `<input type="hidden" name="product_ids[]" id="product_id_${index}" value="${data.id}">`
                );

                let vatToPay = ((price_after_discount / 100) * vat).toFixed(2);
                let totalPrice = (parseFloat(price_after_discount) + parseFloat(vatToPay)).toFixed(2);

                $('.table tbody').append(`
                    <input type="hidden" name="vats_to_pay[]" id="vat_to_pay_${index}" value="${vatToPay}">
                    <input type="hidden" name="total_prices[]" id="total_price_${index}" value="${totalPrice}">
                `);

                $('.table tbody').prepend(`
                    <tr id="r${index}">
                        <td>1</td>
                        <td>${data.name}</td>
                        <td>${attributesHTML}</td>
                        <td>${unit_price}</td>
                        <td>${qty}</td>
                        <td>${discount_amount}</td>
                        <td class="prices_after_discount" id="price_after_discount_${index}">${price_after_discount}</td>
                        <td class="vat_values" id="vat_value_${index}">${vatToPay}</td>
                        <td class="total_prices" id="total_price_${index}">
                            ${totalPrice}
                            <div class="btn btn-danger" onclick="delete_item(${index})">{{ trans('dashboard.delete') }}</div>
                        </td>
                    </tr>
                `);

                $(".table tr").each(function() {
                    $(this).find("td").first().html($(this).index() + 1);
                });

                $('.total_data').each(function() {
                    $(this).find("td").first().html('');
                });

                getPricesAfterDiscount();
                getVatValues();
                getTotalPrices();
            }
        });

        // Reset fields
        $('#product_id').val('{{ trans('purchase.choose') }}');
        $('#qty, #price, #unit_price, #price_after_discount').val('');
        $('#discount_sort').val(-1);
        $('#discount_amount').val('');
        $('#vat').val(-1);
        $('.attribute-select').each(function() {
            $(this).val('{{ trans('purchase.choose') }}');
        });
    }

    function delete_item(item) {
        let price_after_discount = parseFloat($('#price_after_discount_' + item).text()) || 0;
        let vat_value = parseFloat($('#vat_value_' + item).text()) || 0;
        let total_price = parseFloat($('#total_price_' + item).text()) || 0;

        $('#subtotal').text($('#subtotal').text() - price_after_discount);
        $('#tax').text($('#tax').text() - vat_value);
        $('#total').text($('#total').text() - total_price);

        $('.table tbody').append(`
            <input type="hidden" name="subtotal" value="${$('#subtotal').text()}">
            <input type="hidden" name="vat" value="${$('#tax').text()}">
            <input type="hidden" name="total" value="${$('#total').text()}">
        `);

        $(`#r${item}, #unit_price_${item}, #discount_sort_${item}, #discount_amount_${item},
           #price_after_discount_${item}, #qty_${item}, #product_id_${item},
           #vat_to_pay_${item}, #total_price_${item}`).remove();

        @if (isset($edit))
            resetAttributeIndexes();
        @endif
    }

    function getPricesAfterDiscount() {
        let sum = 0;
        $('.prices_after_discount').each(function() {
            sum += parseFloat($(this).text()) || 0;
        });
        $('#subtotal').text(sum.toFixed(2));
        $('.table tbody').append(`<input type="hidden" name="subtotal" value="${sum}">`);
    }

    function getVatValues() {
        let sum = 0;
        $('.vat_values').each(function() {
            sum += parseFloat($(this).text()) || 0;
        });
        $('#tax').text(sum.toFixed(2));
        $('.table tbody').append(`<input type="hidden" name="vat" value="${sum}">`);
    }

    function getTotalPrices() {
        let sum = 0;
        $('.total_prices').each(function() {
            sum += parseFloat($(this).text()) || 0;
        });
        $('#total').text(sum.toFixed(2));
        $('.table tbody').append(`<input type="hidden" name="total" value="${sum}">`);
    }
</script>
