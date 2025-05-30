<div class="modal fade" id="brandModal" tabindex="-1" aria-labelledby="brandModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="brand-add-form" method="POST" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <input type="hidden" name="brand_id" id="brand-id-edit">
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
                    <div class="mb-3">
                        <label class="form-label">لیست برندها</label>
                        <ul class="list-group" id="brands-list">
                            @foreach($brands as $brand)
                                <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $brand->id }}">
                                    <span class="brand-title">{{ $brand->name }}</span>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-primary edit-brand-btn me-1">ویرایش</button>
                                        <button type="button" class="btn btn-sm btn-danger delete-brand-btn">حذف</button>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                    <button type="submit" class="btn btn-success" id="brand-submit-btn">ثبت برند</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // درج یا ویرایش برند
    let brandForm = document.getElementById('brand-add-form');
    let brandSubmitBtn = document.getElementById('brand-submit-btn');
    let brandIdField = document.getElementById('brand-id-edit');
    let brandNameInput = document.getElementById('brand-name');
    let brandImageInput = document.getElementById('brand-image');
    let brandFormAlert = document.getElementById('brand-form-alert');
    let brandsList = document.getElementById('brands-list');
    let brandSelect = document.getElementById('brand-select');

    if(brandForm){
        // ثبت یا ویرایش برند
        brandForm.addEventListener('submit', function(e) {
            e.preventDefault();
            let isEdit = !!brandIdField.value;
            let url = isEdit
                ? ("{{ url('/brands') }}/" + brandIdField.value)
                : "{{ route('brands.store') }}";
            let method = isEdit ? 'POST' : 'POST'; // PATCH باعث مشکلات لاراول و فرم‌دیت می‌شود. پس POST و _method=PATCH
            let formData = new FormData(brandForm);
            if(isEdit) formData.append('_method', 'PATCH');
            brandFormAlert.innerHTML = '';
            fetch(url, {
                method: method,
                headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
                body: formData
            })
            .then(async (response) => {
                let ok = response.ok;
                let data;
                try { data = await response.json(); } catch(e){ data = {}; }
                let brandName = data.name || brandNameInput.value;
                let brandId = data.id || brandIdField.value || (Math.random().toString(36).substr(2,8));
                if(ok || response.status===201 || response.status===200){
                    // اگر ویرایش بود، در لیست و سلکت اصلاح کن
                    if(isEdit){
                        let li = brandsList.querySelector("li[data-id='"+brandId+"']");
                        if(li){
                            li.querySelector('.brand-title').textContent = brandName;
                        }
                        let opt = brandSelect.querySelector("option[value='"+brandId+"']");
                        if(opt){
                            opt.text = brandName;
                            opt.selected = true;
                        }
                        brandFormAlert.innerHTML = '<div class="alert alert-success">برند با موفقیت ویرایش شد.</div>';
                        brandSubmitBtn.textContent = 'ثبت برند';
                        brandForm.querySelector("h5.modal-title").textContent = "افزودن برند جدید";
                    } else {
                        // افزودن به لیست
                        let li = document.createElement('li');
                        li.className = "list-group-item d-flex justify-content-between align-items-center";
                        li.setAttribute('data-id', brandId);
                        li.innerHTML = `<span class="brand-title">${brandName}</span>
                            <div>
                                <button type="button" class="btn btn-sm btn-primary edit-brand-btn me-1">ویرایش</button>
                                <button type="button" class="btn btn-sm btn-danger delete-brand-btn">حذف</button>
                            </div>`;
                        brandsList.appendChild(li);
                        // افزودن به سلکت
                        let opt = document.createElement('option');
                        opt.value = brandId;
                        opt.text = brandName;
                        opt.selected = true;
                        brandSelect.appendChild(opt);
                        brandFormAlert.innerHTML = '<div class="alert alert-success">برند با موفقیت افزوده شد.</div>';
                    }
                    brandForm.reset();
                    brandIdField.value = '';
                    setTimeout(function(){
                        brandFormAlert.innerHTML = '';
                        document.getElementById('brandModal').querySelector('.btn-close').click();
                    }, 900);
                } else if(data && data.errors){
                    let msg = Object.values(data.errors).join('<br>');
                    brandFormAlert.innerHTML = '<div class="alert alert-danger">'+msg+'</div>';
                } else {
                    brandFormAlert.innerHTML = '<div class="alert alert-danger">خطا در ارتباط با سرور</div>';
                }
            })
            .catch(function(){
                brandFormAlert.innerHTML = '<div class="alert alert-danger">خطا در برقراری ارتباط با سرور</div>';
            });
        });

        // دکمه ویرایش برند
        brandsList.addEventListener('click', function(e){
            if(e.target.classList.contains('edit-brand-btn')){
                let li = e.target.closest('li');
                let id = li.getAttribute('data-id');
                let name = li.querySelector('.brand-title').textContent;
                brandIdField.value = id;
                brandNameInput.value = name;
                brandSubmitBtn.textContent = 'ویرایش برند';
                brandForm.querySelector("h5.modal-title").textContent = "ویرایش برند";
                brandImageInput.value = "";
            }
            // حذف برند
            if(e.target.classList.contains('delete-brand-btn')){
                let li = e.target.closest('li');
                let id = li.getAttribute('data-id');
                if(confirm('آیا از حذف برند مطمئن هستید؟')){
                    fetch("{{ url('/brands') }}/" + id, {
                        method: "POST",
                        headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
                        body: JSON.stringify({_method:'DELETE'})
                    })
                    .then(res => {
                        // از لیست برند و سلکت حذف شود
                        li.remove();
                        let opt = brandSelect.querySelector("option[value='"+id+"']");
                        if(opt) opt.remove();
                    });
                }
            }
        });

        // ریست فرم هنگام باز شدن
        $('#brandModal').on('show.bs.modal', function() {
            brandForm.reset();
            brandIdField.value = '';
            brandSubmitBtn.textContent = 'ثبت برند';
            brandForm.querySelector("h5.modal-title").textContent = "افزودن برند جدید";
        });
    }
});
</script>
