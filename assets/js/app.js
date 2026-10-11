function initNavToggle() {
    const toggleButton = document.getElementById('nav-toggle-btn');
    const nav = document.querySelector('header nav');
    if (!toggleButton || !nav) return;
    toggleButton.addEventListener('click', function () {
        const isOpen = nav.classList.toggle('nav-open');
        toggleButton.setAttribute('aria-expanded', String(isOpen));
    });
}

function initHapusConfirm() {
    document.addEventListener('submit', function (event) {
        const form = event.target.closest('form.form-hapus');
        if (!form) return;
        const row = form.closest('tr');
        const label = row?.querySelector('td')?.textContent.trim() || 'data ini';
        if (!window.confirm('Yakin ingin menghapus "' + label + '"?')) event.preventDefault();
    });
}

function initUpdateConfirm() {
    document.addEventListener('submit', function (event) {
        const form = event.target.closest('form#form-tambah');
        if (!form || !/proses_edit\.php$/.test(form.getAttribute('action') || '')) return;
        if (!window.confirm('Yakin ingin menyimpan perubahan?')) event.preventDefault();
    });
}

function tampilkanError(input, message) {
    hapusError(input);
    const error = document.createElement('span');
    error.className = 'error';
    error.textContent = message;
    input.insertAdjacentElement('afterend', error);
}

function hapusError(input) {
    const nextElement = input.nextElementSibling;
    if (nextElement?.classList.contains('error')) nextElement.remove();
}

function validasiWajib(form, name, message) {
    const input = form.querySelector("[name='" + name + "']");
    if (!input) return true;
    if (input.value.trim() === '') { tampilkanError(input, message); return false; }
    hapusError(input);
    return true;
}

function initValidasiForm() {
    const form = document.getElementById('form-tambah');
    if (!form) return;
    form.addEventListener('submit', function (event) {
        let valid = true;
        const isBookForm = form.querySelector("[name='judul']");
        valid = validasiWajib(form, isBookForm ? 'judul' : 'nama', 'Field ini wajib diisi.') && valid;
        valid = validasiWajib(form, isBookForm ? 'pengarang' : 'no_anggota', 'Field ini wajib diisi.') && valid;
        if (isBookForm) {
            const year = form.querySelector("[name='tahun']");
            const stock = form.querySelector("[name='stok']");
            const isbn = form.querySelector("[name='isbn']");
            const yearValue = Number(year.value);
            const stockValue = Number(stock.value);
            if (!year.value || !Number.isInteger(yearValue) || yearValue < 1900 || yearValue > new Date().getFullYear()) { tampilkanError(year, 'Tahun tidak valid.'); valid = false; } else hapusError(year);
            if (!stock.value || !Number.isInteger(stockValue) || stockValue < 0) { tampilkanError(stock, 'Stok harus berupa angka 0 atau lebih.'); valid = false; } else hapusError(stock);
            if (isbn.value.trim() !== '' && !/^[0-9-]+$/.test(isbn.value.trim())) { tampilkanError(isbn, 'ISBN hanya boleh berisi angka dan tanda hubung.'); valid = false; } else hapusError(isbn);
        }
        if (!valid) event.preventDefault();
    });
}

document.addEventListener('DOMContentLoaded', function () {
    initNavToggle();
    initHapusConfirm();
    initUpdateConfirm();
    initValidasiForm();
});
