<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewsSubmissionNotification;
use App\Notifications\NewsReactivationNotification;
use App\Models\User;

class BeritaController extends Controller
{
    public function index(Request $request)
    {
        $query = Berita::where('is_archieved', false)
            ->where('penulis_id', Auth::id())
            ->orderBy('created_at', 'desc');

        // Tambahkan pencarian jika ada parameter search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul_berita', 'like', '%' . $search . '%')
                    ->orWhere('topik', 'like', '%' . $search . '%')
                    ->orWhere('isi_berita', 'like', '%' . $search . '%');
            });
        }
        $beritas = $query->paginate(10);

        return view('wartawan.berita.index', compact('beritas'));
    }

    public function create()
    {
        return view('wartawan.berita.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul_berita' => 'required|string|max:255|unique:berita',
            'url_gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'topik' => 'required|string|max:255',
            'isi_berita' => 'required',
        ], [
            'judul_berita.required' => 'Judul berita wajib diisi',
            'judul_berita.unique' => 'Judul berita sudah digunakan',
            'url_gambar.required' => 'Gambar utama wajib diunggah',
            'url_gambar.image' => 'File harus berupa gambar',
            'url_gambar.mimes' => 'Format gambar yang diterima: jpeg, png, jpg',
            'url_gambar.max' => 'Ukuran gambar tidak boleh lebih dari 2MB',
            'topik.required' => 'Topik wajib dipilih',
            'isi_berita.required' => 'Isi berita tidak boleh kosong',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('quill_content', $request->isi_berita)
                ->with('selected_topic', $request->topik);
        }

        try {
            $imageName = time() . '_' . strtolower($request->url_gambar->getClientOriginalName());
            $request->url_gambar->storeAs('berita', $imageName);

            if (!Storage::exists('berita/' . $imageName)) {
                throw new \Exception('Gagal menyimpan gambar');
            }

            $berita = Berita::create([
                'judul_berita' => $request->judul_berita,
                'penulis_id' => Auth::id(),
                'url_gambar' => $imageName,
                'tanggal_kirim' => now()->format('Y-m-d'),
                'jam_kirim' => now()->format('H:i:s'),
                'tanggal_terbit' => now()->format('Y-m-d'),
                'jam_terbit' => now()->format('H:i:s'),
                'topik' => $request->topik,
                'isi_berita' => $request->isi_berita,
                'status_unggah' => 'pengajuan',
                'is_archieved' => false,
                'edited_since_archieved' => false,
            ]);

            $admin = User::where('role', 'admin_radio')->first();
            $admin?->notify(new NewsSubmissionNotification($berita));

            return redirect()->route('berita.index')
                ->with('success', 'Berita berhasil diajukan dan menunggu verifikasi admin');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menyimpan berita: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $berita = Berita::where('id', $id)
            ->where('penulis_id', Auth::id())
            ->firstOrFail();

        return view('wartawan.berita.show', compact('berita'));
    }


    public function edit($id)
    {
        $berita = Berita::where('id', $id)
            ->where('penulis_id', Auth::id())
            ->firstOrFail();

        // Jika berita sudah disetujui/diarsipkan
        if (!in_array($berita->status_unggah, ['pengajuan', 'draft', 'ditolak'])) {
            return redirect()->back()->with('error_edit', 'Berita yang sudah diterima tidak dapat diubah');
        }

        return view('wartawan.berita.edit', compact('berita'));
    }

    public function update(Request $request, $id)
    {
        $berita = Berita::findOrFail($id);

        if ($berita->penulis_id != Auth::id()) {
            abort(403);
        }

        // Jika berita sudah disetujui/diarsipkan
        if (!in_array($berita->status_unggah, ['pengajuan', 'draft', 'ditolak'])) {
            return redirect()->back()->with('error_edit', 'Berita yang sudah diterima tidak dapat diubah');
        }

        $validator = Validator::make($request->all(), [
            'judul_berita' => 'required|string|max:255|unique:berita,judul_berita,' . $id,
            'url_gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'topik' => 'required|string|max:255',
            'isi_berita' => 'required',
        ], [
            'judul_berita.required' => 'Judul berita wajib diisi',
            'judul_berita.unique' => 'Judul berita sudah digunakan',
            'url_gambar.required' => 'Gambar utama wajib diunggah',
            'url_gambar.image' => 'File harus berupa gambar',
            'url_gambar.mimes' => 'Format gambar yang diterima: jpeg, png, jpg',
            'url_gambar.max' => 'Ukuran gambar tidak boleh lebih dari 2MB',
            'topik.required' => 'Topik wajib dipilih',
            'isi_berita.required' => 'Isi berita tidak boleh kosong',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $isEdited = false;

            if ($request->hasFile('url_gambar')) {
                Storage::delete('berita/' . $berita->url_gambar);
                $imageName = time() . '_' . $request->url_gambar->getClientOriginalName();
                $request->url_gambar->storeAs('berita', $imageName);
                $berita->url_gambar = $imageName;
                $isEdited = true;
            }

            if ($berita->judul_berita !== $request->judul_berita) {
                $berita->judul_berita = $request->judul_berita;
                $isEdited = true;
            }

            if ($berita->topik !== $request->topik) {
                $berita->topik = $request->topik;
                $isEdited = true;
            }

            if (trim($berita->isi_berita) !== trim($request->isi_berita)) {
                $berita->isi_berita = $request->isi_berita;
                $isEdited = true;
            }

            if (!$isEdited) {
                return redirect()->back()->with('error', 'Tidak ada perubahan yang dilakukan.');
            }

            if ($berita->is_archieved) {
                $berita->edited_since_archieved = true;
            }

            if ($berita->status_unggah === 'ditolak') {
                $berita->status_unggah = 'pengajuan';
            }

            $berita->save();

            $redirectRoute = $berita->is_archieved ? 'berita.archived' : 'berita.index';
            $message = 'Berita berhasil diperbarui' . ($berita->is_archieved ? ' dan siap dipulihkan' : '');

            return redirect()->route($redirectRoute)->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui berita: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function archive($id)
    {
        $berita = Berita::where('id', $id)
            ->where('penulis_id', Auth::id())
            ->where('is_archieved', false)
            ->firstOrFail();

        $berita->update([
            'is_archieved' => true,
            'edited_since_archieved' => false
        ]);

        return redirect()->route('berita.index')
            ->with('success', 'Berita berhasil diarsipkan.');
    }

    public function archived()
    {
        $beritas = Berita::where('is_archieved', true)
            ->where('penulis_id', Auth::id())
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('wartawan.berita.archived', compact('beritas'));
    }

    public function showarsip($id)
    {
        $berita = Berita::where('id', $id)
            ->where('penulis_id', Auth::id())
            ->firstOrFail();

        return view('wartawan.berita.showarsip', compact('berita'));
    }

    public function restore($id)
    {
        $berita = Berita::where('id', $id)
            ->where('penulis_id', Auth::id())
            ->where('is_archieved', true)
            ->firstOrFail();

        if (!$berita->edited_since_archieved) {
            return redirect()->route('berita.archived')
                ->with('error', 'Silakan edit berita terlebih dahulu sebelum mengembalikannya.');
        }

        $berita->update([
            'is_archieved' => false,
            'edited_since_archieved' => false,
            'status_unggah' => 'pengajuan'
        ]);

        $admin = User::where('role', 'admin_radio')->first();
        if ($admin) {
            $admin->notify(new NewsReactivationNotification($berita));
        }

        return redirect()->route('berita.index')
            ->with('success', 'Berita berhasil dipulihkan dan dikirim ulang untuk verifikasi.');
    }
}