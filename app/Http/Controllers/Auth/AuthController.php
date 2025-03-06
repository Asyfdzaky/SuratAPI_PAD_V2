<?php

namespace App\Http\Controllers\Auth;

use App\Models\RT;
use App\Models\User;
use App\Models\Warga;
use App\Models\DetailAlamat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class AuthController extends Controller
{
    public function store(Request $request) {
        try {
            // Mulai transaksi database
            DB::beginTransaction();

            $validatedData = $request->validate([
                "email" => "required|email|unique:users",
                "password" => "required|min:8",
                "nama" => "required|string",
                "nomer_kk" => "required|numeric",
                "nik" => "required|numeric",
                "jenis_kelamin" => "required|in:laki-laki,perempuan",
                "phone" => "required|numeric",
                "tempat_lahir" => "required|string",
                "tanggal_lahir" => "required|date",
                "id_rw" => "required|integer",
                "id_rt" => "required|integer",
                "alamat" => "required|string",
                "kabupaten" => "required|string",
                "provinsi" => "required|string",
            ]);

            // Buat user baru
            $user = User::create([
                "email" => $request->email,
                "password" => Hash::make($request->password),
                "role" => "warga",
                "status" => false,
            ]);

            // Buat detail alamat
            $detailAlamat = DetailAlamat::create([
                "alamat" => $request->alamat,
                "kabupaten" => $request->kabupaten,
                "provinsi" => $request->provinsi,
            ]);  
        

            // Buat warga
            $warga = Warga::create([
                "id_users" => $user->id,
                "id_alamat" => $detailAlamat->id,
                "id_rw" => $request->id_rw,
                "id_rt" => $request->id_rt,
                "nama" => $request->nama,
                "nomor_kk" => $request->nomor_kk,
                "nik" => $request->nik,
                "jenis_kelamin" => $request->jenis_kelamin,
                "phone" => $request->phone,
                "tempat_lahir" => $request->tempat_lahir,
                "tanggal_lahir" => $request->tanggal_lahir,
            ]);

            // Generate token
            $token = $user->createToken("auth_token")->plainTextToken;

            // Commit transaksi jika semua berhasil
            DB::commit();

            return response()->json([
                "message" => "success",
                "data" => [
                    "user" => $user,
                    "warga" => $warga,
                    "token" => $token,
                ],
            ], 201);
        } catch (Exception $e) {
            // Jika error, rollback transaksi
            DB::rollBack();

            // Catat error di log Laravel
            Log::error("Register Error: " . $e->getMessage());

            return response()->json([
                "message" => "Terjadi kesalahan saat registrasi",
                "error" => $e->getMessage(),
            ], 500);
        }
    }
}
