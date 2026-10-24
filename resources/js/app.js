import './bootstrap';
// Validasi nomor identitas (16 digit)
document.getElementById('no_identitas')?.addEventListener('input', function(e) {
    this.value = this.value.replace(/\D/g, '').slice(0, 16);
});

// Validasi nomor telepon
document.getElementById('no_telp')?.addEventListener('input', function(e) {
    this.value = this.value.replace(/\D/g, '').slice(0, 15);
});

// Validasi ukuran file upload
document.getElementById('url_foto_kartu_identitas')?.addEventListener('change', function(e) {
    if (this.files[0].size > 2 * 1024 * 1024) {
        alert('Ukuran file maksimal 2MB');
        this.value = '';
    }
});