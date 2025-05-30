// --- اعداد فارسی، اعشاری، قیمت خرید/فروش --- //
function toPersianNumber(str) {
    if (!str) return '';
    return (str + '').replace(/\d/g, d => '۰۱۲۳۴۵۶۷۸۹'[d]);
}
function toEnglishNumber(str) {
    if (!str) return '';
    return str.replace(/[۰-۹]/g, d => "0123456789"["۰۱۲۳۴۵۶۷۸۹".indexOf(d)]);
}
function formatDecimalPersian(numStr) {
    let en = toEnglishNumber(numStr.replace(/,/g, ''));
    if(en === "") return "";
    let parts = en.split('.');
    let intPart = parts[0].replace(/^0+/, '') || "0";
    intPart = intPart.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    let fracPart = (parts[1] || '').slice(0,2);
    let result = intPart;
    if(fracPart.length > 0) result += "." + fracPart;
    return toPersianNumber(result);
}
function unformatDecimalPersian(str) {
    return toEnglishNumber(str.replace(/,/g, ''));
}

document.addEventListener('DOMContentLoaded', function () {
    // قیمت خرید و فروش: همواره اعشاری فارسی
    document.querySelectorAll('input[name=buy_price], input[name=sell_price]').forEach(inp => {
        inp.value = formatDecimalPersian(inp.value);
        inp.addEventListener('input', function(e){
            let raw = unformatDecimalPersian(this.value);
            let parts = raw.split('.');
            raw = parts[0];
            if(parts.length > 1) raw += '.' + parts[1].slice(0,2);
            let formatted = formatDecimalPersian(raw);
            this.value = formatted;
            this.setSelectionRange(this.value.length, this.value.length);
        });
        inp.addEventListener('focus', function(){ this.value = formatDecimalPersian(this.value); });
        inp.addEventListener('blur', function(){ this.value = formatDecimalPersian(this.value); });
    });

    // سایر فیلدهای عددی
    document.querySelectorAll(".persian-number").forEach(inp => {
        if(inp.name === "buy_price" || inp.name === "sell_price") return;
        inp.addEventListener('input', function(){
            let val = toEnglishNumber(this.value).replace(/[^0-9.]/g, '');
            let parts = val.split('.');
            if(parts.length > 2) val = parts[0]+'.'+parts.slice(1).join('');
            this.value = toPersianNumber(val);
        });
        inp.addEventListener('focus', function(){
            this.value = toPersianNumber(toEnglishNumber(this.value));
        });
        inp.addEventListener('blur', function(){
            this.value = toPersianNumber(toEnglishNumber(this.value));
        });
    });

    // سوییچ کد کالا
    const codeSwitch = document.getElementById('code-edit-switch');
    const codeInput = document.getElementById('product-code');
    if(codeSwitch && codeInput){
        codeSwitch.addEventListener('change', function () {
            codeInput.readOnly = !this.checked;
            if(this.checked){
                codeInput.value = '';
                codeInput.focus();
            } else {
                codeInput.value = 'product-1001';
            }
        });
    }

    // دکمه تولید بارکد
    function handleBarcodeBtn(btnId, fieldId) {
        let btn = document.getElementById(btnId);
        let field = document.getElementById(fieldId);
        if(btn && field) {
            btn.addEventListener('click', function(){
                let base = '';
                for (let i = 0; i < 12; i++) base += Math.floor(Math.random() * 10);
                let sum = 0;
                for (let i = 0; i < 12; i++) sum += parseInt(base[i]) * ((i % 2 === 0) ? 1 : 3);
                let check = (10 - (sum % 10)) % 10;
                field.value = toPersianNumber(base + check);
                field.dispatchEvent(new Event('blur'));
                Swal.fire({toast:true,position:'top-end',icon:'success',title:'بارکد استاندارد تولید شد',showConfirmButton:false,timer:1300,iconColor:'#1abc9c',background:'#e9fff4',color:'#173f28',customClass:{popup:'swal2-sm'}});
            });
        }
    }
    handleBarcodeBtn('generate-barcode-btn', 'barcode-field');
    handleBarcodeBtn('generate-store-barcode-btn', 'store-barcode-field');

    // Dropzone
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

    // ویژگی‌های پویا (attributes)
    const attributesArea = document.getElementById('attributes-area');
    const addAttrBtn = document.getElementById('add-attribute');
    let attrIndex = 0;
    function createAttributeRow(key = '', value = '') {
        attrIndex++;
        const row = document.createElement('div');
        row.className = 'attribute-row d-flex mb-2 gap-2';
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
        removeBtn.className = 'btn btn-danger';
        removeBtn.innerText = 'حذف';
        removeBtn.onclick = () => row.remove();
        row.appendChild(keyInput); row.appendChild(valueInput); row.appendChild(removeBtn);
        return row;
    }
    if(addAttrBtn) {
        addAttrBtn.addEventListener('click', function () {
            attributesArea.appendChild(createAttributeRow());
        });
    }

    // سهامداران (همانند قبل)
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
            document.getElementById('percent-' + checked[0]).value = '۱۰۰';
            warning.innerText = '';
            warning.style.display = 'none';
        } else {
            let allEmpty = true;
            checked.forEach(id => {
                let val = document.getElementById('percent-' + id).value;
                if (val && parseFloat(toEnglishNumber(val)) > 0) allEmpty = false;
            });
            if (allEmpty) {
                let share = 100 / checked.length;
                share = Math.round(share * 100) / 100;
                checked.forEach(id => {
                    document.getElementById('percent-' + id).value = toPersianNumber(share);
                });
            }
            let sum = checked.reduce((acc, id) => acc + parseFloat(toEnglishNumber(document.getElementById('percent-' + id).value || '۰')), 0);
            if (sum !== 100 && !allEmpty) {
                warning.innerText = 'مجموع درصدها باید ۱۰۰ باشد. مجموع فعلی: ' + toPersianNumber(sum);
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
