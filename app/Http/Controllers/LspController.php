<?php

namespace App\Http\Controllers;

use App\Models\CertificationScheme;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LspController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('lsp.dashboard');
        }
        return view('lsp.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        // Fallback: check email or username
        $field = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        if (Auth::attempt([$field => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->intended(route('lsp.dashboard'));
        }

        // Auto seed admin if none exists
        if (User::count() === 0 && $request->email === 'admin' && $request->password === 'admin123') {
            $user = User::create([
                'name' => 'admin',
                'email' => 'admin@lsp.id',
                'password' => Hash::make('admin123'),
            ]);
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->intended(route('lsp.dashboard'));
        }

        return back()->withErrors([
            'login' => 'Username atau password tidak sesuai.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('lsp.login');
    }

    public function dashboard()
    {
        $totalParticipants = Participant::count();
        $totalSchemes = CertificationScheme::count();
        $totalKompeten = Participant::where('status', 'like', '%Kompeten%')->count();
        $totalBelum = Participant::where('status', 'not like', '%Kompeten%')->count();
        $recentParticipants = Participant::with('scheme')->latest()->take(5)->get();

        return view('lsp.dashboard', compact(
            'totalParticipants',
            'totalSchemes',
            'totalKompeten',
            'totalBelum',
            'recentParticipants'
        ));
    }

    // Schemes
    public function schemes()
    {
        $schemes = CertificationScheme::withCount('participants')->latest()->get();
        return view('lsp.schemes', compact('schemes'));
    }

    public function storeScheme(Request $request)
    {
        $validated = $request->validate([
            'scheme_code' => 'required|string|unique:certification_schemes,scheme_code',
            'scheme_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        CertificationScheme::create($validated);
        return back()->with('success', 'Skema sertifikasi berhasil ditambahkan.');
    }

    public function updateScheme(Request $request, CertificationScheme $scheme)
    {
        $validated = $request->validate([
            'scheme_code' => 'required|string|unique:certification_schemes,scheme_code,' . $scheme->id,
            'scheme_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $scheme->update($validated);
        return back()->with('success', 'Skema sertifikasi berhasil diperbarui.');
    }

    public function destroyScheme(CertificationScheme $scheme)
    {
        $scheme->delete();
        return back()->with('success', 'Skema sertifikasi berhasil dihapus.');
    }

    // Participants
    public function participants(Request $request)
    {
        $search = $request->input('search');
        $query = Participant::with('scheme')->latest();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('registration_number', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('scheme', function($sq) use ($search) {
                      $sq->where('scheme_name', 'like', "%{$search}%")
                         ->orWhere('scheme_code', 'like', "%{$search}%");
                  });
            });
        }

        $participants = $query->paginate(10)->withQueryString();
        $schemes = CertificationScheme::orderBy('scheme_name')->get();

        return view('lsp.participants', compact('participants', 'schemes', 'search'));
    }

    public function storeParticipant(Request $request)
    {
        $validated = $request->validate([
            'scheme_id' => 'required|exists:certification_schemes,id',
            'registration_number' => 'required|string|unique:participants,registration_number',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'nullable|string',
            'status' => 'required|string',
        ]);

        Participant::create($validated);
        return back()->with('success', 'Peserta berhasil didaftarkan.');
    }

    public function updateParticipant(Request $request, Participant $participant)
    {
        $validated = $request->validate([
            'scheme_id' => 'required|exists:certification_schemes,id',
            'registration_number' => 'required|string|unique:participants,registration_number,' . $participant->id,
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'nullable|string',
            'status' => 'required|string',
        ]);

        $participant->update($validated);
        return back()->with('success', 'Data peserta berhasil diperbarui.');
    }

    public function destroyParticipant(Participant $participant)
    {
        $participant->delete();
        return back()->with('success', 'Data peserta berhasil dihapus.');
    }
}
