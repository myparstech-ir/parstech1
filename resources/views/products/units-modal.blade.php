<div class="modal fade" id="unitModal" tabindex="-1" aria-labelledby="unitModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="add-unit-form" method="POST" autocomplete="off">
                @csrf
                <input type="hidden" name="unit_id" id="unit-id-edit">
                <div class="modal-header">
                    <h5 class="modal-title" id="unitModalLabel">افزودن واحد جدید</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <div class="modal-body">
                    <div id="unit-form-alert"></div>
                    <div class="mb-3">
                        <label for="unit-title" class="form-label">عنوان واحد <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="unit-title" name="title" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">لیست واحدها</label>
                        <ul class="list-group" id="units-list">
                            @foreach($units as $unit)
                                <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $unit->id }}">
                                    <span class="unit-title">{{ $unit->title }}</span>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-primary edit-unit-btn me-1">ویرایش</button>
                                        <button type="button" class="btn btn-sm btn-danger delete-unit-btn">حذف</button>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                    <button type="submit" class="btn btn-success" id="unit-submit-btn">ثبت واحد</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    let unitForm = document.getElementById('add-unit-form');
    let unitSubmitBtn = document.getElementById('unit-submit-btn');
    let unitIdField = document.getElementById('unit-id-edit');
    let unitTitleInput = document.getElementById('unit-title');
    let unitFormAlert = document.getElementById('unit-form-alert');
    let unitsList = document.getElementById('units-list');
    let unitSelect = document.getElementById('selected-unit');

    if(unitForm){
        unitForm.addEventListener('submit', function (e) {
            e.preventDefault();
            let isEdit = !!unitIdField.value;
            let url = isEdit
                ? ("{{ url('/units') }}/" + unitIdField.value)
                : "{{ route('units.store') }}";
            let method = isEdit ? 'POST' : 'POST';
            let formData = new FormData(unitForm);
            if(isEdit) formData.append('_method', 'PATCH');
            unitFormAlert.innerHTML = '';
            fetch(url, {
                method: method,
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: formData
            })
            .then(async r => {
                let ok = r.ok;
                let data;
                try { data = await r.json(); } catch(e){ data = {}; }
                let unitTitle = data.title || unitTitleInput.value;
                let unitId = data.id || unitIdField.value || (Math.random().toString(36).substr(2,8));
                if(ok){
                    if(isEdit){
                        let li = unitsList.querySelector("li[data-id='"+unitId+"']");
                        if(li){
                            li.querySelector('.unit-title').textContent = unitTitle;
                        }
                        let opt = unitSelect.querySelector("option[value='"+unitTitle+"']");
                        if(opt){
                            opt.text = unitTitle;
                            opt.selected = true;
                        }
                        unitFormAlert.innerHTML = '<div class="alert alert-success">واحد با موفقیت ویرایش شد.</div>';
                        unitSubmitBtn.textContent = 'ثبت واحد';
                        unitForm.querySelector("h5.modal-title").textContent = "افزودن واحد جدید";
                    } else {
                        let li = document.createElement('li');
                        li.className = "list-group-item d-flex justify-content-between align-items-center";
                        li.setAttribute('data-id', unitId);
                        li.innerHTML = `<span class="unit-title">${unitTitle}</span>
                            <div>
                                <button type="button" class="btn btn-sm btn-primary edit-unit-btn me-1">ویرایش</button>
                                <button type="button" class="btn btn-sm btn-danger delete-unit-btn">حذف</button>
                            </div>`;
                        unitsList.appendChild(li);
                        let opt = document.createElement('option');
                        opt.value = unitTitle;
                        opt.text = unitTitle;
                        opt.selected = true;
                        unitSelect.appendChild(opt);
                        unitFormAlert.innerHTML = '<div class="alert alert-success">واحد با موفقیت افزوده شد.</div>';
                    }
                    unitForm.reset();
                    unitIdField.value = '';
                    setTimeout(function(){
                        unitFormAlert.innerHTML = '';
                        document.getElementById('unitModal').querySelector('.btn-close').click();
                    }, 900);
                } else if(data && data.errors && data.errors.title) {
                    unitFormAlert.innerHTML = '<div class="alert alert-danger">' + data.errors.title[0] + '</div>';
                } else {
                    unitFormAlert.innerHTML = '<div class="alert alert-danger">خطا در ثبت واحد</div>';
                }
            })
            .catch(err => {
                unitFormAlert.innerHTML = '<div class="alert alert-danger">خطا در ارتباط با سرور</div>';
            });
        });

        unitsList.addEventListener('click', function(e){
            if(e.target.classList.contains('edit-unit-btn')){
                let li = e.target.closest('li');
                let id = li.getAttribute('data-id');
                let title = li.querySelector('.unit-title').textContent;
                unitIdField.value = id;
                unitTitleInput.value = title;
                unitSubmitBtn.textContent = 'ویرایش واحد';
                unitForm.querySelector("h5.modal-title").textContent = "ویرایش واحد";
            }
            if(e.target.classList.contains('delete-unit-btn')){
                let li = e.target.closest('li');
                let id = li.getAttribute('data-id');
                if(confirm('آیا از حذف واحد مطمئن هستید؟')){
                    fetch("{{ url('/units') }}/" + id, {
                        method: "POST",
                        headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
                        body: JSON.stringify({_method:'DELETE'})
                    })
                    .then(res => {
                        li.remove();
                        let opt = unitSelect.querySelector("option[value='"+li.querySelector('.unit-title').textContent+"']");
                        if(opt) opt.remove();
                    });
                }
            }
        });

        // ریست فرم هنگام باز شدن
        $('#unitModal').on('show.bs.modal', function() {
            unitForm.reset();
            unitIdField.value = '';
            unitSubmitBtn.textContent = 'ثبت واحد';
            unitForm.querySelector("h5.modal-title").textContent = "افزودن واحد جدید";
        });
    }
});
</script>
