<?php $__env->startSection('title', 'Edit Playlist'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4 my-4">
    <h3 class="mb-4">Edit Playlist: <?php echo e($playlist->nama_stasiun); ?></h3>
    <div class="card shadow px-6">
        <div class="card-body">

            <form action="<?php echo e(route('playlist.update', $playlist->id)); ?>" method="POST" enctype="multipart/form-data" id="edit-playlist-form">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="mb-3 row align-items-center">
                    <label for="nama_stasiun" class="col-sm-2 col-form-label">Nama Stasiun</label>
                    <div class="col-sm-10 pe-5">
                        <input type="text" name="nama_stasiun" id="nama_stasiun"
                               class="form-control <?php $__errorArgs = ['nama_stasiun'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('nama_stasiun', $playlist->nama_stasiun)); ?>" required>
                        <?php $__errorArgs = ['nama_stasiun'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="mb-3 row align-items-center">
                    <label for="nama_daerah" class="col-sm-2 col-form-label">Daerah</label>
                    <div class="col-sm-10 pe-5">
                        <select name="nama_daerah" id="nama_daerah"
                                class="form-select select2-daerah <?php $__errorArgs = ['nama_daerah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="">Pilih atau ketik nama daerah</option>
                            <?php
                                $daerahList = ['Surabaya', 'Malang', 'Probolinggo', 'Jember', 'Banyuwangi', 'Situbondo'];
                                $current = old('nama_daerah', $playlist->nama_daerah);
                            ?>
                            <?php $__currentLoopData = $daerahList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $daerah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($daerah); ?>" <?php echo e($current == $daerah ? 'selected' : ''); ?>><?php echo e($daerah); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if(!in_array($current, $daerahList) && $current): ?>
                                <option value="<?php echo e($current); ?>" selected><?php echo e($current); ?></option>
                            <?php endif; ?>
                        </select>
                        <?php $__errorArgs = ['nama_daerah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="mb-3 row align-items-center">
                    <label for="url_stasiun" class="col-sm-2 col-form-label">URL Stasiun</label>
                    <div class="col-sm-10 pe-5">
                        <input type="url" name="url_stasiun" id="url_stasiun"
                               class="form-control <?php $__errorArgs = ['url_stasiun'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('url_stasiun', $playlist->url_stasiun)); ?>" required>
                        <?php $__errorArgs = ['url_stasiun'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="mb-3 row align-items-center">
                    <label for="feed_url" class="col-sm-2 col-form-label">RSS Feed URL</label>
                    <div class="col-sm-8 pe-2">
                        <input type="url" name="feed_url" id="feed_url" 
                            class="form-control <?php $__errorArgs = ['feed_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            value="<?php echo e(old('feed_url', $playlist->feed_url ?? '')); ?>"
                            placeholder="https://example.com/feed.xml">
                        <small class="text-muted">Kosongkan jika tidak ada RSS Feed</small>
                        <?php $__errorArgs = ['feed_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-primary" id="btn-cek-rss" style="width:100%;">
                            <i class="fas fa-rss"></i> Cek RSS
                        </button>
                    </div>
                </div>


                <div class="mb-3 row align-items-center">
                    <label for="jadwal_url" class="col-sm-2 col-form-label">URL Jadwal Tayang</label>
                    <div class="col-sm-8 pe-2">
                        <input type="url" name="jadwal_url" id="jadwal_url" class="form-control" 
                            value="<?php echo e(old('jadwal_url', $playlist->jadwal_url ?? '')); ?>"
                            placeholder="https://www.girifm.com/jadwal">
                        <small class="text-muted">URL halaman jadwal tayang stasiun radio</small>
                    </div>
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-success" id="btn-cek-jadwal" style="width:100%;">
                            <i class="fas fa-calendar-alt"></i> Cek Jadwal
                        </button>
                    </div>
                </div>

                <div class="mb-3 row align-items-center">
                    <label for="genre" class="col-sm-2 col-form-label">Genre</label>
                    <div class="col-sm-10 pe-5">
                        <select name="genre" id="genre" class="form-select <?php $__errorArgs = ['genre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="">Pilih Genre</option>
                            <option value="Pop" <?php echo e(old('genre', $playlist->genre) == 'Pop' ? 'selected' : ''); ?>>Pop</option>
                            <option value="Dangdut" <?php echo e(old('genre', $playlist->genre) == 'Dangdut' ? 'selected' : ''); ?>>Dangdut</option>
                            <option value="Religi" <?php echo e(old('genre', $playlist->genre) == 'Religi' ? 'selected' : ''); ?>>Religi</option>
                            <option value="Berita" <?php echo e(old('genre', $playlist->genre) == 'Berita' ? 'selected' : ''); ?>>Berita</option>
                            <option value="Lokal" <?php echo e(old('genre', $playlist->genre) == 'Lokal' ? 'selected' : ''); ?>>Lokal</option>
                            <option value="Lainnya" <?php echo e(old('genre', $playlist->genre) == 'Lainnya' ? 'selected' : ''); ?>>Lainnya</option>
                        </select>
                        <?php $__errorArgs = ['genre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="mb-3 row align-items-center">
                    <label for="thumbnail" class="col-sm-2 col-form-label">Thumbnail</label>
                    <div class="col-sm-10 pe-5">
                        <input type="file" name="thumbnail" id="thumbnail"
                               class="form-control <?php $__errorArgs = ['thumbnail'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['thumbnail'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah thumbnail</small>
                        <div class="mt-2">
                            <img src="<?php echo e(asset('storage/thumbnails/'.$playlist->thumbnail)); ?>"
                                 alt="Current Thumbnail" width="100" class="img-thumbnail">
                        </div>
                    </div>
                </div>

                <div class="mb-4 row align-items-start">
                    <label for="deskripsi" class="col-sm-2 col-form-label">Deskripsi</label>
                    <div class="col-sm-10 pe-5">
                        <textarea name="deskripsi" id="deskripsi"
                                  class="form-control <?php $__errorArgs = ['deskripsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                  rows="4" required><?php echo e(old('deskripsi', $playlist->deskripsi)); ?></textarea>
                        <?php $__errorArgs = ['deskripsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="offset-sm-2 col-sm-10 pe-5 text-end">
                        <a href="<?php echo e(route('playlist.index')); ?>" class="btn btn-kembali me-2">Kembali</a>
                        <button type="submit" class="btn btn-custom" style="width: 100px;">Simpan</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- Custom Popup -->
<div class="popup-overlay d-none" id="custom-confirm">
    <div class="popup-box">
        <p><strong>Update Stasiun Radio</strong><br>
        Apakah Anda yakin ingin menyimpan perubahan stasiun radio ini?</p>
        <div class="d-flex justify-content-center gap-3 mt-3">
            <button type="button" class="btn-finish" id="confirm-submit">Simpan</button>
            <button type="button" class="btn-finish" style="background-color:#6c757d" id="cancel-submit">Batal</button>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
.popup-overlay {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: rgba(0, 0, 0, 0.4);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}
.popup-box {
    width: 333px;
    background-color: #003135;
    border-top: 13px solid #964734;
    border-radius: 12px;
    padding: 25px 20px;
    color: white;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}
.popup-box p {
    font-size: 0.95rem;
    margin-bottom: 20px;
    line-height: 1.6;
}
.popup-box .btn-finish {
    background-color: #964734;
    color: white;
    padding: 8px 30px;
    border: none;
    border-radius: 20px;
    font-weight: bold;
    font-size: 1rem;
    cursor: pointer;
    text-decoration: none;
}
.popup-box .btn-finish:hover {
    background-color: #7c362a;
}



</style>

<script>
$(document).ready(function() {
    $('.select2-daerah').select2({
        tags: true,
        placeholder: "Pilih atau ketik nama daerah",
        allowClear: true,
        width: '100%',
        createTag: function(params) {
            if ($.trim(params.term) === '') return null;
            return {
                id: params.term,
                text: params.term,
                newTag: true
            };
        },
        templateResult: function(data) {
            var $result = $("<span>" + data.text + "</span>");
            if (data.newTag) {
                $result.append(" <span class='text-muted'>(custom)</span>");
            }
            return $result;
        }
    });

    // Auto-dismiss success alert
    setTimeout(function () {
        $('#success-alert').fadeOut('slow');
    }, 5000);

    // Custom popup confirm
    $('#edit-playlist-form').on('submit', function(e) {
        e.preventDefault();
        $('#custom-confirm').removeClass('d-none');
    });

    $('#confirm-submit').on('click', function () {
        $('#edit-playlist-form')[0].submit();
    });

    $('#cancel-submit').on('click', function () {
        $('#custom-confirm').addClass('d-none');
    });

    const alerts = document.querySelectorAll('#autoCloseAlert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
    
        // =============================
        // CEK RSS FEED - Auto-fill Genre (Edit)
        // =============================
        $('#btn-cek-rss').on('click', function() {
            let feedUrl = $('#feed_url').val();
            
            if (!feedUrl) {
                alert('Masukkan URL RSS Feed terlebih dahulu!');
                return;
            }

            let $btn = $(this);
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Loading...');

            $.ajax({
                url: '<?php echo e(route("playlist.cekRss")); ?>',
                method: 'POST',
                data: {
                    feed_url: feedUrl,
                    _token: '<?php echo e(csrf_token()); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        $('#genre').val(response.genre).trigger('change');
                        alert('✅ Genre berhasil diambil dari RSS: ' + response.genre);
                    } else {
                        alert('❌ Gagal mengambil genre dari RSS:\n' + response.message);
                    }
                },
                error: function(xhr) {
                    let msg = 'Terjadi kesalahan saat menghubungi server.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    alert('❌ Error: ' + msg);
                },
                complete: function() {
                    $btn.prop('disabled', false).html('<i class="fas fa-rss"></i> Cek RSS');
                }
            });
        });

        // =============================
        // CEK JADWAL TAYANG - Auto-fill Genre
        // =============================
        $('#btn-cek-jadwal').on('click', function() {
            let jadwalUrl = $('#jadwal_url').val();
            
            if (!jadwalUrl) {
                alert('Masukkan URL halaman jadwal tayang terlebih dahulu!');
                return;
            }

            let $btn = $(this);
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Loading...');

            $.ajax({
                url: '<?php echo e(route("playlist.cekJadwal")); ?>',
                method: 'POST',
                data: {
                    jadwal_url: jadwalUrl,
                    _token: '<?php echo e(csrf_token()); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        $('#genre').val(response.genre).trigger('change');
                        alert('✅ Genre berhasil diambil dari jadwal tayang: ' + response.genre);
                    } else {
                        alert('❌ Gagal mengambil genre dari jadwal tayang:\n' + response.message);
                    }
                },
                error: function(xhr) {
                    let msg = 'Terjadi kesalahan saat menghubungi server.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    alert('❌ Error: ' + msg);
                },
                complete: function() {
                    $btn.prop('disabled', false).html('<i class="fas fa-calendar-alt"></i> Cek Jadwal');
                }
            });
        });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/misntvbi/laravel_misnfm/resources/views/admin/playlist/edit.blade.php ENDPATH**/ ?>