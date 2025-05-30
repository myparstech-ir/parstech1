<div class="modal fade" id="brandModal" tabindex="-1" aria-labelledby="brandModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="brand-add-form" method="POST" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="brandModalLabel">افزودن برند جدید</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <div class="modal-body">
                    <div id="brand-form-alert"></div>
                    <div class="mb-3">
                        <label class="form-label">نام برند <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="brand-name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">تصویر برند</label>
                        <input type="file" name="image" id="brand-image" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                    <button type="submit" class="btn btn-success">ثبت برند</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const brandForm = document.getElementById('brand-add-form');
    if(brandForm){
        brandForm.addEventListener('submit', function(e) {
            e.preventDefault();
            let form = brandForm;
            let formData = new FormData(form);
            document.getElementById('brand-form-alert').innerHTML = '';
            fetch("{{ route('brands.store') }}", {
                method: "POST",
                headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
                body: formData
            })
            .then(async (response) => {
                let ok = response.ok;
                let data;
                try { data = await response.json(); } catch(e){ data = {}; }
                // اگر id و name نبود، از مقدار input استفاده کن
                let brandName = data.name || document.getElementById('brand-name').value;
                let brandId = data.id || '';
                if(ok || response.status===201 || response.status===200){
                    // گزینه جدید را به لیست اضافه کن
                    let select = document.getElementById('brand-select');
                    let option = document.createElement('option');
                    option.value = brandId || (Math.random().toString(36).substr(2,8));
                    option.text = brandName;
                    option.selected = true;
                    select.appendChild(option);
                    document.getElementById('brand-form-alert').innerHTML = '<div class="alert alert-success">برند با موفقیت افزوده شد.</div>';
                    setTimeout(function(){
                        document.getElementById('brand-form-alert').innerHTML = '';
                        document.getElementById('brandModal').querySelector('.btn-close').click();
                    }, 800);
                    form.reset();
                } else if(data && data.errors){
                    let msg = Object.values(data.errors).join('<br>');
                    document.getElementById('brand-form-alert').innerHTML = '<div class="alert alert-danger">'+msg+'</div>';
                } else {
                    document.getElementById('brand-form-alert').innerHTML = '<div class="alert alert-danger">خطا در ارتباط با سرور</div>';
                }
            })
            .catch(function(){
                document.getElementById('brand-form-alert').innerHTML = '<div class="alert alert-danger">خطا در برقراری ارتباط با سرور</div>';
            });
        });
    }
});
</script>
