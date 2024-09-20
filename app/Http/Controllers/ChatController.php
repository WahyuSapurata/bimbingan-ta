<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChatRequest;
use App\Http\Requests\UpdateChatRequest;
use App\Models\Chat;
use App\Models\ListBimbingan;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends BaseController
{
    public function send(Request $request)
    {
        // Validasi input
        $request->validate([
            'message' => 'nullable|string',
            'media' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp3,wav|max:2048',
        ]);

        // Inisialisasi chat baru
        $chat = new Chat();
        $chat->sender_uuid = auth()->user()->uuid;
        $chat->receiver_uuid = $request->uuid_receiver;

        // Jika ada pesan teks
        if ($request->has('message')) {
            $chat->message = $request->input('message');
            $chat->type = 'text'; // Set tipe menjadi teks
        }

        // Jika ada file yang diupload
        if ($request->hasFile('media')) {
            $file = $request->file('media'); // Ambil file dari request
            $extension = $file->extension(); // Dapatkan ekstensi file

            // Membuat nama baru untuk file
            // Menggunakan judul jika ada, atau menggunakan 'media' jika judul tidak ada
            $newFile = (auth()->user()->name ?? 'media') . '-' . now()->timestamp . '.' . $extension;

            // Simpan file ke storage (public disk) dan simpan path-nya
            $path = $file->storeAs('chat', $newFile, 'public');
            $chat->media_path = $path; // Simpan path ke database

            // Tentukan tipe media berdasarkan MIME type
            $mimeType = $file->getMimeType();
            if (str_contains($mimeType, 'audio')) {
                $chat->type = 'audio';
            } elseif (str_contains($mimeType, 'image')) {
                $chat->type = 'image';
            } else {
                $chat->type = 'file'; // Jika file bukan audio atau image
            }
        }

        // Simpan data chat
        $chat->save();

        // Kembalikan respons
        return $this->sendResponse($chat, 'Chat berhasil dikirim');
    }

    public function getChat($uuid_receiver)
    {
        // Ambil UUID pengguna yang sedang login
        $sender_uuid = auth()->user()->uuid;

        // Update semua pesan yang diterima oleh user yang sedang login dan dikirim dari uuid_receiver
        Chat::where('receiver_uuid', $sender_uuid)
            ->where('sender_uuid', $uuid_receiver)
            ->update(['count_receiver' => true]);

        // Query untuk mendapatkan chat antara pengirim dan penerima
        $chats = Chat::where(function ($query) use ($sender_uuid, $uuid_receiver) {
            $query->where('sender_uuid', $sender_uuid)
                ->where('receiver_uuid', $uuid_receiver);
        })
            ->orWhere(function ($query) use ($sender_uuid, $uuid_receiver) {
                $query->where('sender_uuid', $uuid_receiver)
                    ->where('receiver_uuid', $sender_uuid);
            })
            ->orderBy('created_at', 'asc') // Urutkan berdasarkan waktu pembuatan
            ->get();

        // Format respons untuk dikirim ke client
        $formattedChats = $chats->map(function ($chat) {
            return [
                'sender_uuid' => $chat->sender_uuid,
                'receiver_uuid' => $chat->receiver_uuid,
                'message' => $chat->message,
                'media_path' => $chat->media_path,
                'type' => $chat->type,
                'created_at' => $chat->created_at->toDateTimeString(),
            ];
        });

        return $this->sendResponse($formattedChats, 'Get chat success');
    }

    public function get_user_chat_dosen()
    {
        // Ambil semua data bimbingan berdasarkan uuid dosen yang sedang login
        $data = ListBimbingan::where('uuid_dosen', auth()->user()->uuid)->get();

        // Map data untuk setiap item
        $formattedData = $data->map(function ($item) {
            // Ambil data mahasiswa berdasarkan uuid mahasiswa
            $mahasiswa = User::where('uuid', $item->uuid_mahasiswa)->first();

            // Ambil chat antara mahasiswa dan dosen dengan count_receiver = false
            $chat = Chat::where('receiver_uuid', auth()->user()->uuid) // Dosen sebagai penerima
                ->where('sender_uuid', $mahasiswa->uuid) // Mahasiswa sebagai pengirim
                ->where('count_receiver', false) // Chat yang belum dibaca
                ->get();

            // Set nilai baru ke dalam item
            $item->receiver_uuid = $mahasiswa->uuid;
            $item->nama = $mahasiswa->name;
            $item->total_receiver = $chat->count(); // Total chat yang belum dibaca

            // Ambil chat terakhir, jika ada, untuk mendapatkan waktu
            $lastChat = $chat->last();
            $item->jam_receiver = $lastChat ? $lastChat->created_at->toDateTimeString() : null;

            return $item;
        });

        // Kembalikan hasil yang sudah diformat sebagai JSON response
        return $this->sendResponse($formattedData, 'Get data success');
    }

    public function get_user_chat_mahasiswa()
    {
        // Ambil semua data bimbingan berdasarkan uuid mahasiswa yang sedang login
        $data = ListBimbingan::where('uuid_mahasiswa', auth()->user()->uuid)->get();

        // Map data untuk setiap item
        $formattedData = $data->map(function ($item) {
            // Ambil data dosen berdasarkan uuid dosen
            $dosen = User::where('uuid', $item->uuid_dosen)->first();

            // Ambil chat antara mahasiswa dan dosen dengan count_receiver = false
            $chat = Chat::where('receiver_uuid', auth()->user()->uuid) // Mahasiswa sebagai penerima
                ->where('sender_uuid', $dosen->uuid) // Dosen sebagai pengirim
                ->where('count_receiver', false) // Chat yang belum dibaca oleh mahasiswa
                ->get();

            // Set nilai baru ke dalam item
            $item->receiver_uuid = $dosen->uuid;
            $item->nama = $dosen->name;
            $item->total_receiver = $chat->count(); // Total chat yang belum dibaca

            // Ambil chat terakhir, jika ada, untuk mendapatkan waktu
            $lastChat = $chat->last();
            $item->jam_receiver = $lastChat ? $lastChat->created_at->toDateTimeString() : null;

            return $item;
        });

        // Kembalikan hasil yang sudah diformat sebagai JSON response
        return $this->sendResponse($formattedData, 'Get data success');
    }
}
