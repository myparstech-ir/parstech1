// تبدیل اعداد لاتین به فارسی
function toPersianNumber(str) {
    if (!str) return '';
    return (str + '').replace(/\d/g, d => '۰۱۲۳۴۵۶۷۸۹'[d]);
}

// ساخت بارکد EAN-13 استاندارد (۱۲ رقم + رقم کنترلی)
function generateEan13() {
    let base = '';
    for (let i = 0; i < 12; i++) {
        base += Math.floor(Math.random() * 10);
    }
    let sum = 0;
    for (let i = 0; i < 12; i++) {
        sum += parseInt(base[i]) * ((i % 2 === 0) ? 1 : 3);
    }
    let check = (10 - (sum % 10)) % 10;
    return base + check;
}

document.addEventListener('DOMContentLoaded', function () {
    // Dropzone: فقط یکبار فعال شود
    if (window.Dropzone && !document.getElementById('gallery-dropzone').dropzone) {
        Dropzone.autoDiscover = false;
        new Dropzone("#gallery-dropzone", {
            url: "#",
            autoProcessQueue: false,
            addRemoveLinks: true,
            maxFiles: 5,
            acceptedFiles: "image/*",
            dictDefaultMessage: "تصاویر را اینجا بکشید یا کلیک کنید",
        });
    }

    // SweetAlert2 برای تب‌ها
    const tabAlerts = {
        'main-tab': 'اطلاعات تکمیلی را وارد کنید.',
        'media-tab': 'رسانه و فایل‌های محصول را بارگذاری کنید.',
        'desc-tab': 'توضیحات و ویژگی‌های محصول را وارد کنید.',
        'shareholder-tab': 'سهم سهامداران را مشخص کنید.'
    };
    document.querySelectorAll('#productTab button').forEach(btn => {
        btn.addEventListener('shown.bs.tab', function(e) {
            let id = e.target.id;
            if(tabAlerts[id]){
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'info',
                    title: tabAlerts[id],
                    showConfirmButton: false,
                    timer: 1800,
                    timerProgressBar: true,
                    background: '#e3f0ff',
                    color: '#17517b',
                    iconColor: '#1976d2',
                    customClass: { popup: 'swal2-sm' }
                });
            }
        });
    });

    // اعداد فارسی روی فیلدهای عددی
    document.querySelectorAll(".persian-number").forEach(inp => {
        inp.addEventListener('input', function(){
            let val = this.value.replace(/[^\d.]/g,'');
            this.value = val;
            this.style.direction = 'ltr';
            this.style.textAlign = 'left';
        });
        inp.addEventListener('blur', function(){
            if(this.value)
                this.value = toPersianNumber(this.value);
            this.style.direction = 'rtl';
            this.style.textAlign = 'right';
        });
        inp.addEventListener('focus', function(){
            this.value = this.value.replace(/[۰-۹]/g, d => "0123456789"["۰۱۲۳۴۵۶۷۸۹".indexOf(d)]); // فارسی به لاتین
            this.style.direction = 'ltr';
            this.style.textAlign = 'left';
        });
    });

    // سوییچ کد کالا
    const codeSwitch = document.getElementById('code-edit-switch');
    const codeInput = document.getElementById('product-code');
    if(codeSwitch && codeInput){
        codeSwitch.addEventListener('change', function () {
            codeInput.readOnly = !this.checked;
            if(this.checked){
                codeInput.focus();
                codeInput.value = '';
            } else {
                codeInput.value = 'products-1001';
            }
        });
    }

    // تولید بارکد محصول و فروشگاهی
    function handleBarcodeBtn(btnId, fieldId) {
        let btn = document.getElementById(btnId);
        let field = document.getElementById(fieldId);
        if(btn && field) {
            btn.addEventListener('click', function(){
                field.value = generateEan13();
                field.dispatchEvent(new Event('blur'));
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'بارکد استاندارد تولید شد',
                    showConfirmButton: false,
                    timer: 1300,
                    iconColor: '#1abc9c',
                    background: '#e9fff4',
                    color: '#173f28',
                    customClass: { popup: 'swal2-sm' }
                });
            });
        }
    }
    handleBarcodeBtn('generate-barcode-btn', 'barcode-field');
    handleBarcodeBtn('generate-store-barcode-btn', 'store-barcode-field');

    // دسته‌بندی جستجویی (select2 ajax)
    if (window.jQuery && $('#category-select2').length) {
        $('#category-select2').select2({
            dir: "rtl",
            width: "100%",
            placeholder: "جستجو یا انتخاب دسته‌بندی...",
            ajax: {
                url: "/api/categories/search",
                dataType: 'json',
                delay: 200,
                data: function (params) { return { q: params.term }; },
                processResults: function (data) {
                    return {
                        results: data.items.map(function (item) {
                            return { id: item.id, text: item.name };
                        })
                    };
                },
                cache: true
            },
            minimumInputLength: 0,
            language: {
                noResults: function() { return "دسته‌ای پیدا نشد"; }
            }
        });

        // لیست دسته‌بندی پرتکرار
        $('#category-select2').on('select2:open', function () {
            $.ajax({
                url: '/api/categories/popular',
                dataType: 'json',
                success: function (data) {
                    let $results = $('.select2-results');
                    if (data.items && data.items.length) {
                        let html = '<div class="select2-quick-list" style="padding:10px"><strong>پرتکرار/آخرین‌ها:</strong><ul style="list-style:none;margin:0;padding:0">';
                        data.items.forEach(function (cat) {
                            html += '<li><a href="#" class="select2-quick-cat" data-id="'+cat.id+'">'+cat.name+'</a></li>';
                        });
                        html += '</ul></div>';
                        $results.prepend(html);
                        $('.select2-quick-cat').on('click', function(e){
                            e.preventDefault();
                            let id = $(this).data('id');
                            $('#category-select2').val(id).trigger('change');
                            $('#category-select2').select2("close");
                        });
                    }
                }
            });
        });
    }

    // تاریخ شمسی
    if (window.jQuery && $('#expire_date_picker').length) {
        $('#expire_date_picker').persianDatepicker({
            format: 'YYYY/MM/DD',
            initialValueType: 'gregorian',
            autoClose: true,
            toolbox: false,
            calendar: { persian: { locale: 'fa' } }
        });
    }

    // ویژگی‌های پویا (attributes)
    const attributesArea = document.getElementById('attributes-area');
    const addAttrBtn = document.getElementById('add-attribute');
    let attrIndex = 0;
    function createAttributeRow(key = '', value = '') {
        attrIndex++;
        const row = document.createElement('div');
        row.className = 'attribute-row';
        const keyInput = document.createElement('input');
        keyInput.type = 'text';
        keyInput.className = 'form-control';
        keyInput.placeholder = 'ویژگی';
        keyInput.name = `attributes[${attrIndex}][key]`;
        keyInput.value = key;
        const valueInput = document.createElement('input');
        valueInput.type = 'text';
        valueInput.className = 'form-control';
        valueInput.placeholder = 'مقدار';
        valueInput.name = `attributes[${attrIndex}][value]`;
        valueInput.value = value;
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'attribute-remove-btn';
        removeBtn.innerHTML = '&times;';
        removeBtn.onclick = () => row.remove();
        row.appendChild(keyInput); row.appendChild(valueInput); row.appendChild(removeBtn);
        return row;
    }
    if(addAttrBtn) {
        addAttrBtn.addEventListener('click', function () {
            attributesArea.appendChild(createAttributeRow());
        });
    }

    // سهامداران
    let checkboxes = document.querySelectorAll('.shareholder-checkbox');
    let percents = document.querySelectorAll('.shareholder-percent');
    let warning = document.getElementById('percent-warning');
    function updatePercents() {
        let checked = [];
        checkboxes.forEach((ch) => {
            let percentInput = document.getElementById('percent-' + ch.value);
            if (ch.checked) checked.push(ch.value);
            percentInput.disabled = !ch.checked;
            if (!ch.checked) percentInput.value = '';
        });
        if (checked.length === 0) {
            percents.forEach(inp => inp.value = '');
            warning.style.display = 'none';
        } else if (checked.length === 1) {
            percents.forEach(inp => inp.value = '');
            document.getElementById('percent-' + checked[0]).value = 100;
            warning.innerText = '';
            warning.style.display = 'none';
        } else {
            let allEmpty = true;
            checked.forEach(id => {
                let val = document.getElementById('percent-' + id).value;
                if (val && parseFloat(val) > 0) allEmpty = false;
            });
            if (allEmpty) {
                let share = (100 / checked.length).toFixed(2);
                checked.forEach(id => {
                    document.getElementById('percent-' + id).value = share;
                });
            }
            let sum = checked.reduce((acc, id) => acc + parseFloat(document.getElementById('percent-' + id).value || 0), 0);
            if (sum !== 100 && !allEmpty) {
                warning.innerText = 'مجموع درصدها باید ۱۰۰ باشد. مجموع فعلی: ' + sum;
                warning.style.display = 'block';
            } else {
                warning.innerText = '';
                warning.style.display = 'none';
            }
        }
    }
    checkboxes.forEach(ch => ch.addEventListener('change', updatePercents));
    percents.forEach(inp => inp.addEventListener('input', updatePercents));
    updatePercents();

    // اسکرول به ارور
    let firstError = document.querySelector('.alert-danger');
    if(firstError) {
        window.scrollTo({ top: firstError.offsetTop-50, behavior: "smooth" });
    }
});
