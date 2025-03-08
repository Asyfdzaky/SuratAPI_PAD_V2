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
    public function register(Request $request) {
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
                'id_rt' => 'required|exists:rt,id',
                'id_rw' => 'required|exists:rw,id',
                "alamat" => "required|string",
                "kabupaten" => "required|string",
                "provinsi" => "required|string",
            ]);

            $rt = RT::find($request->id_rt);
            if ($rt->id_rw != $request->id_rw) {
                return response()->json(['error' => 'RT dan RW tidak cocok'], 400);
            }
            // Buat user baru
            $user = User::create([
                "email" => $request->email,
                "password" => Hash::make($request->password),
                "role" => "warga",
                "statusAkun" => false,
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
                "id_rt" => $request->id_rt,
                "nama" => $request->nama,
                "nomor_kk" => $request->nomer_kk,
                "nik" => $request->nik,
                "jenis_kelamin" => $request->jenis_kelamin,
                "phone" => $request->phone,
                "tempat_lahir" => $request->tempat_lahir,
                "tanggal_lahir" => $request->tanggal_lahir,
            ]);

            

            // Commit transaksi jika semua berhasil
            DB::commit();

            return response()->json([
                "message" => "Registrasi berhasil! Tunggu aktivasi dari admin.",
                "data" => [
                    "user" => $user,
                    "warga" => $warga,
                ],
            ], 200);
        } catch (Exception $e) {
            // Jika error, rollback transaksi
            DB::rollBack();
            Log::error("Register Error: " . $e->getMessage());
            return response()->json([
                "message" => "Terjadi kesalahan saat registrasi",
                "error" => $e->getMessage(),
            ], 500);
        }
    }
    // LOGIN USER
    public function Login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();

        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json([
                'message' => 'Email atau password salah'
            ], 401);
        }
        if($user->statusAkun == false){
            return response()->json([
                'message' => "Akun belum diaktifkan! Silakan tunggu admin."
            ], 401);
        }
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user,
        ], 200);
    }
     /**
     * Logout User
     */
    public function logout(Request $request)
{
    $user = $request->user();

    if (!$user) {
        return response()->json([
            "message" => "Unauthenticated."
        ], 401);
    }

    $user->tokens()->delete();
    return response()->json([
        "message" => "Logout berhasil"
    ], 200);
}

}
