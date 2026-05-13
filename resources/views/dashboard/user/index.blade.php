@extends('layout')

@section('content')
    @include('layouts.sections.navbar')

    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper">

            @include('layouts.sections.sidebar')

            <div class="main-panel">
                <div class="content-wrapper pengeluaran-table-wrapper" style="background-color: #fafbfc; min-height: 100vh;">

                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <div class="d-flex flex-wrap justify-content-between align-items-center">
                                <div>
                                    <h2 class="fw-bold mb-0" style="color: #000; letter-spacing: -0.5px; font-size: 26px;">Kelola User</h2>
                                </div>
                                <div class="d-flex align-items-center mt-3 mt-md-0" style="font-size:14px; color:#888;">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right: 8px"><path d="M19 4H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2z"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                                    <span>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d M, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(session('success'))
                    <div class="custom-alert success" id="successAlert">
                        <div class="custom-alert-icon"><i class="ti-check"></i></div>
                        <div class="custom-alert-content">{{ session('success') }}</div>
                        <button type="button" class="custom-alert-close" onclick="document.getElementById('successAlert').style.display='none'">
                            <i class="ti-close"></i>
                        </button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="custom-alert error" id="errorAlert">
                        <div class="custom-alert-icon"><i class="ti-alert"></i></div>
                        <div class="custom-alert-content">{{ session('error') }}</div>
                        <button type="button" class="custom-alert-close" onclick="document.getElementById('errorAlert').style.display='none'">
                            <i class="ti-close"></i>
                        </button>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card border-0 rounded-4" style="box-shadow: 0 4px 30px rgba(0,0,0,0.03);">
                                <div class="card-body p-4 p-md-5">

                                    <!-- Filter & Search -->
                                    <form method="GET" action="{{ url()->current() }}" class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 pb-2 gap-3 w-100">
                                        <div class="d-flex align-items-center" style="gap: 15px;">
                                            <label class="text-muted fw-medium mb-0" style="font-size: 15px; white-space: nowrap;">Filter role</label>
                                            <select name="role" class="form-select shadow-none" style="width: 140px; border-radius: 4px; padding: 6px 12px; font-size: 14px; cursor: pointer;" onchange="this.form.submit()">
                                                <option value="Semua" {{ request('role') == 'Semua' || !request('role') ? 'selected' : '' }}>Semua</option>
                                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                                <option value="penyewa" {{ request('role') == 'penyewa' ? 'selected' : '' }}>Penyewa</option>
                                            </select>
                                        </div>
                                        <div class="d-flex align-items-center w-100 mt-2 mt-md-0 d-md-flex justify-content-md-end" style="gap: 10px; max-width: 320px;">
                                            <input type="text" name="search" class="form-control shadow-none w-100" placeholder="Cari nama atau email" value="{{ request('search') }}" style="border-radius: 4px; padding: 6px 12px; font-size: 14px;">
                                            <button type="submit" class="btn border-0 shadow-sm d-flex align-items-center justify-content-center flex-shrink-0" style="background-color: #00a669; color: white; padding: 0; width: 36px; height: 36px; border-radius: 4px; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">
                                                <i class="ti-search" style="font-size: 15px;"></i>
                                            </button>
                                        </div>
                                    </form>

                                    <!-- ===================== TABLE FIX ===================== -->
                                    <div class="table-responsive" style="width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch;">
                                        <table class="table align-middle mb-0" style="border-collapse: separate; border-spacing: 0; min-width: 700px; table-layout: fixed; width: 100%;">
                                            <colgroup>
                                                <col style="width: 52px;">      {{-- No --}}
                                                <col style="width: 18%;">       {{-- Nama --}}
                                                <col style="width: 26%;">       {{-- Email --}}
                                                <col style="width: 16%;">       {{-- No. Telepon --}}
                                                <col style="width: 11%;">       {{-- Role --}}
                                                <col style="width: 130px;">     {{-- Aksi --}}
                                            </colgroup>
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3" style="font-size: 14px; border-color: #e5e7eb !important;">No</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3" style="font-size: 14px; border-color: #e5e7eb !important;">Nama</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3" style="font-size: 14px; border-color: #e5e7eb !important;">Email</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3" style="font-size: 14px; border-color: #e5e7eb !important;">No. Telepon</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3" style="font-size: 14px; border-color: #e5e7eb !important;">Role</th>
                                                    <th class="border-bottom-2 border-top-0 border-start-0 border-end-0 text-dark fw-bold pb-3 px-3 text-center" style="font-size: 14px; border-color: #e5e7eb !important;">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no = ($users->currentPage() - 1) * $users->perPage() + 1; @endphp
                                                @forelse ($users as $user)
                                                <tr class="table-row-hover" style="transition: background 0.2s;">
                                                    <td class="border-bottom border-top-0 border-start-0 border-end-0 py-3 px-3 text-dark bg-transparent" style="font-size: 14px; border-color: #f1f2f6;">{{ $no++ }}</td>

                                                    <td class="border-bottom border-top-0 border-start-0 border-end-0 py-3 px-3 bg-transparent fw-semibold" style="font-size: 14px; border-color: #f1f2f6; color: #111827; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 0;">
                                                        <span title="{{ $user->nama ?? '-' }}">{{ $user->nama ?? '-' }}</span>
                                                    </td>

                                                    <td class="border-bottom border-top-0 border-start-0 border-end-0 py-3 px-3 text-dark bg-transparent" style="font-size: 14px; border-color: #f1f2f6; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 0;">
                                                        <span title="{{ $user->email ?? '-' }}">{{ $user->email ?? '-' }}</span>
                                                    </td>

                                                    <td class="border-bottom border-top-0 border-start-0 border-end-0 py-3 px-3 text-dark bg-transparent" style="font-size: 14px; border-color: #f1f2f6; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 0;">
                                                        <span title="{{ $user->no_telepon ?? '-' }}">{{ $user->no_telepon ?? '-' }}</span>
                                                    </td>

                                                    <td class="border-bottom border-top-0 border-start-0 border-end-0 py-3 px-3 bg-transparent" style="font-size: 14px; border-color: #f1f2f6;">
                                                        @if($user->role == 'admin')
                                                            <span class="badge rounded-pill" style="background-color: #e0e7ff; color: #3730a3; font-weight: 600; font-size: 12px;">Admin</span>
                                                        @else
                                                            <span class="badge rounded-pill" style="background-color: #fef3c7; color: #d97706; font-weight: 600; font-size: 12px;">Penyewa</span>
                                                        @endif
                                                    </td>

                                                    <td class="border-bottom border-top-0 border-start-0 border-end-0 py-3 px-3 text-center bg-transparent" style="border-color: #f1f2f6;">
                                                        <a href="#" data-bs-toggle="modal" data-bs-target="#detailModal{{ $user->id_user }}"
                                                           class="badge rounded-pill text-white text-decoration-none px-3 py-2"
                                                           style="background-color: #3b82f6; font-size: 12px; font-weight: 500; transition: opacity 0.2s;"
                                                           onmouseover="this.style.opacity='0.85';" onmouseout="this.style.opacity='1';">Detail</a>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-5 text-muted bg-transparent">Tidak ada data user ditemukan.</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- ==================== END TABLE ==================== -->

                                    <!-- Pagination -->
                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 mb-2 gap-4 text-center">
                                        <span class="text-muted" style="font-size: 15px; font-weight: 500; letter-spacing: -0.2px;">
                                            Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} data dari total {{ $users->total() }} data
                                        </span>
                                        <div class="d-flex align-items-center" style="gap: 25px;">
                                            @if ($users->onFirstPage())
                                                <span class="text-muted d-flex align-items-center" style="font-size: 15px; opacity: 0.4; font-weight: 500; cursor: not-allowed;">
                                                    <i class="ti-angle-left me-2 fw-bold" style="font-size: 15px;"></i> Kembali
                                                </span>
                                            @else
                                                <a href="{{ $users->previousPageUrl() . '&role=' . request('role') . '&search=' . request('search') }}" class="text-dark text-decoration-none d-flex align-items-center" style="font-size: 15px; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='#6366f1';" onmouseout="this.style.color='#343a40';">
                                                    <i class="ti-angle-left me-2 fw-bold" style="font-size: 15px;"></i> Kembali
                                                </a>
                                            @endif

                                            @if ($users->hasMorePages())
                                                <a href="{{ $users->nextPageUrl() . '&role=' . request('role') . '&search=' . request('search') }}" class="text-dark text-decoration-none d-flex align-items-center" style="font-size: 15px; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='#6366f1';" onmouseout="this.style.color='#343a40';">
                                                    Selanjutnya <i class="ti-angle-right ms-2 fw-bold" style="font-size: 15px;"></i>
                                                </a>
                                            @else
                                                <span class="text-muted d-flex align-items-center" style="font-size: 15px; opacity: 0.4; font-weight: 500; cursor: not-allowed;">
                                                    Selanjutnya <i class="ti-angle-right ms-2 fw-bold" style="font-size: 15px;"></i>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- ===================== MODALS PER USER ===================== -->
                @foreach($users as $user)

                <!-- Modal Detail — IMPROVED SPACING -->
                <div class="modal fade" id="detailModal{{ $user->id_user }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
                        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">

                            <!-- Header -->
                            <div style="background: #fff; padding: 20px 24px 16px; border-bottom: 1px solid #f0f1f3;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center" style="gap: 12px;">
                                        <div style="background: #e0e7ff; border-radius: 10px; width: 40px; height: 40px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                            <i class="ti-info-alt" style="color: #6366f1; font-size: 18px;"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0 fw-bold" style="color: #111827; font-size: 15px; letter-spacing: -0.3px;">Detail User</h5>
                                            <p class="mb-0" style="color: #9ca3af; font-size: 12px; margin-top: 2px;">Informasi lengkap user {{ $user->nama }}</p>
                                        </div>
                                    </div>
                                    <button type="button" data-bs-dismiss="modal"
                                        style="background: #f3f4f6; border: none; border-radius: 50%; width: 32px; height: 32px; display:flex; align-items:center; justify-content:center; color: #6b7280; font-size: 12px; flex-shrink:0; transition: background 0.2s; cursor:pointer;"
                                        onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                                        <i class="ti-close"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Body — field dengan gap yang lebih rapi -->
                            <div class="modal-body" style="padding: 24px; background: #fff;">
                                <div style="display: flex; flex-direction: column; gap: 14px;">

                                    <!-- Nama -->
                                    <div style="background: #f9fafb; border: 1px solid #f0f1f3; border-radius: 10px; border-left: 3px solid #6366f1; padding: 14px 16px;">
                                        <p style="font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; color: #9ca3af; margin: 0 0 6px 0;">Nama</p>
                                        <p style="font-size: 15px; font-weight: 700; color: #111827; margin: 0; word-break: break-word;">{{ $user->nama ?? '-' }}</p>
                                    </div>

                                    <!-- Email -->
                                    <div style="background: #f9fafb; border: 1px solid #f0f1f3; border-radius: 10px; padding: 14px 16px;">
                                        <p style="font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; color: #9ca3af; margin: 0 0 6px 0;">Email</p>
                                        <p style="font-size: 14px; color: #4b5563; margin: 0; word-break: break-all;">{{ $user->email ?? '-' }}</p>
                                    </div>

                                    <!-- No. Telepon & Role — side by side -->
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                                        <div style="background: #f9fafb; border: 1px solid #f0f1f3; border-radius: 10px; padding: 14px 16px;">
                                            <p style="font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; color: #9ca3af; margin: 0 0 6px 0;">No. Telepon</p>
                                            <p style="font-size: 14px; color: #4b5563; margin: 0; word-break: break-all;">{{ $user->no_telepon ?? '-' }}</p>
                                        </div>
                                        <div style="background: #f9fafb; border: 1px solid #f0f1f3; border-radius: 10px; padding: 14px 16px;">
                                            <p style="font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; color: #9ca3af; margin: 0 0 8px 0;">Role</p>
                                            @if($user->role == 'admin')
                                                <span class="badge rounded-pill" style="background-color: #e0e7ff; color: #3730a3; font-weight: 600; font-size: 12px; padding: 5px 12px;">Admin</span>
                                            @else
                                                <span class="badge rounded-pill" style="background-color: #fef3c7; color: #d97706; font-weight: 600; font-size: 12px; padding: 5px 12px;">Penyewa</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Alamat -->
                                    <div style="background: #f9fafb; border: 1px solid #f0f1f3; border-radius: 10px; padding: 14px 16px;">
                                        <p style="font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; color: #9ca3af; margin: 0 0 6px 0;">Alamat</p>
                                        <p style="font-size: 14px; color: #4b5563; margin: 0; line-height: 1.6; word-break: break-word;">{{ $user->alamat ?? '-' }}</p>
                                    </div>

                                </div>
                            </div>

                            <!-- Footer -->
                            <div style="padding: 0 24px 24px; background: #fff;">
                                <button type="button" class="btn w-100" data-bs-dismiss="modal"
                                    style="background: #374151; color: white; border-radius: 10px; padding: 11px; font-weight: 600; font-size: 14px; border: none; transition: opacity 0.2s;"
                                    onmouseover="this.style.opacity='0.88'" onmouseout="this.style.opacity='1'">
                                    Tutup
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Modal Edit -->
                <div class="modal fade" id="editModal{{ $user->id_user }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
                        <div class="modal-content border-0" style="border-radius: 14px; overflow: hidden; box-shadow: 0 8px 40px rgba(0,0,0,0.13);">
                            <div style="background: #fff; padding: 22px 26px 18px; border-bottom: 1px solid #f0f1f3;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center" style="gap: 8px;">
                                        <div style="background: #eff6ff; border-radius: 10px; width: 40px; height: 40px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                            <i class="ti-pencil-alt" style="color: #3b82f6; font-size: 17px;"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0 fw-bold" style="color: #111827; font-size: 16px; letter-spacing: -0.3px;">Edit User</h5>
                                            <p class="mb-0" style="color: #9ca3af; font-size: 12.5px; margin-top: 1px;">Perbarui data user {{ $user->nama }}</p>
                                        </div>
                                    </div>
                                    <button type="button" data-bs-dismiss="modal" aria-label="Close"
                                        style="background: #f3f4f6; border: none; border-radius: 50%; width: 33px; height: 33px; display:flex; align-items:center; justify-content:center; cursor:pointer; color: #6b7280; font-size: 13px; flex-shrink:0; transition: background 0.2s;"
                                        onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                                        <i class="ti-close"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="modal-body" style="padding: 18px 26px 20px; background: #fff; max-height: 75vh; overflow-y: auto;">
                                <form action="{{ route('user.update', $user->id_user) }}" method="POST" id="formEditUser{{ $user->id_user }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #eff6ff; color: #3b82f6; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-user"></i></span>
                                            Nama <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="nama" value="{{ $user->nama }}" required
                                            style="width:100%; padding: 11px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; transition: 0.2s;"
                                            onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)';"
                                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                                    </div>

                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #eff6ff; color: #3b82f6; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-email"></i></span>
                                            Email <span class="text-danger">*</span>
                                        </label>
                                        <input type="email" name="email" value="{{ $user->email }}" required
                                            style="width:100%; padding: 11px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; transition: 0.2s;"
                                            onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)';"
                                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                                    </div>

                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #eff6ff; color: #3b82f6; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-lock"></i></span>
                                            Password Baru <span style="color:#9ca3af; font-weight:400;">(kosongkan jika tidak ingin diubah)</span>
                                        </label>
                                        <input type="password" name="password"
                                            style="width:100%; padding: 11px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; transition: 0.2s;"
                                            placeholder="Minimal 6 karakter"
                                            onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)';"
                                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                                    </div>

                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #eff6ff; color: #3b82f6; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-mobile"></i></span>
                                            No. Telepon
                                        </label>
                                        <input type="text" name="no_telepon" value="{{ $user->no_telepon }}"
                                            style="width:100%; padding: 11px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; transition: 0.2s;"
                                            onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)';"
                                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                                    </div>

                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #eff6ff; color: #3b82f6; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-location-pin"></i></span>
                                            Alamat
                                        </label>
                                        <textarea name="alamat" rows="2"
                                            style="width:100%; padding: 10px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #111827; outline: none; resize: none; transition: 0.2s;"
                                            onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)';"
                                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">{{ $user->alamat }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="d-flex align-items-center mb-2" style="font-size: 13px; font-weight: 600; color: #374151; gap: 8px;">
                                            <span style="background: #eff6ff; color: #3b82f6; border-radius: 6px; width: 22px; height: 22px; display:inline-flex; align-items:center; justify-content:center; font-size:11px;"><i class="ti-settings"></i></span>
                                            Role <span class="text-danger">*</span>
                                        </label>
                                        <div class="d-flex flex-wrap" style="gap: 10px;">
                                            @foreach(['admin', 'penyewa'] as $role)
                                            <label style="cursor:pointer;">
                                                <input type="radio" name="role" value="{{ $role }}" {{ $user->role == $role ? 'checked' : '' }} required style="display:none;" onchange="updateEditPill(this, 'role', {{ $user->id_user }})">
                                                <span class="pill-label-role-{{ $user->id_user }} {{ $user->role == $role ? 'pill-active-edit' : '' }}"
                                                    style="display:inline-block; padding: 6px 18px; border-radius: 20px; font-size: 13px; font-weight: 500; border: 1.5px solid {{ $user->role == $role ? '#3b82f6' : '#e5e7eb' }}; background: {{ $user->role == $role ? '#eff6ff' : '#f9fafb' }}; color: {{ $user->role == $role ? '#1e40af' : '#6b7280' }}; transition: all 0.15s; user-select:none;">{{ ucfirst($role) }}</span>
                                            </label>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end" style="gap: 12px; margin-top: 20px;">
                                        <button type="button" data-bs-dismiss="modal"
                                            style="padding: 9px 22px; border-radius: 8px; border: 1.5px solid #e5e7eb; background: white; font-weight: 600; font-size: 13.5px; color: #6b7280; cursor:pointer; transition: 0.2s;"
                                            onmouseover="this.style.background='#f9fafb';" onmouseout="this.style.background='white';">
                                            Batal
                                        </button>
                                        <button type="submit"
                                            style="padding: 9px 26px; border-radius: 8px; border: none; background: #3b82f6; color: white; font-weight: 600; font-size: 13.5px; cursor:pointer; transition: opacity 0.2s;"
                                            onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">
                                            Simpan Perubahan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Hapus -->
                <div class="modal fade" id="hapusModal{{ $user->id_user }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
                        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                            <div class="modal-body text-center" style="padding: 40px 30px;">
                                <div style="background: #fef2f2; border-radius: 50%; width: 70px; height: 70px; display:flex; align-items:center; justify-content:center; margin: 0 auto 24px;">
                                    <i class="ti-trash" style="color: #ef4444; font-size: 32px;"></i>
                                </div>
                                <h5 class="fw-bold mb-2" style="color: #111827; font-size: 18px;">Konfirmasi Hapus</h5>
                                <p class="mb-4" style="color: #6b7280; font-size: 14px; line-height: 1.5;">Apakah Anda yakin ingin menghapus user <strong>{{ $user->nama }}</strong>? Tindakan ini tidak dapat dibatalkan.</p>
                                <form action="{{ route('user.destroy', $user->id_user) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <div class="d-flex flex-column gap-3">
                                        <button type="submit" class="btn text-white py-2 fw-bold" style="background: #ef4444; border-radius: 10px; font-size: 14px; border: none;">Ya, Hapus Sekarang</button>
                                        <button type="button" class="btn py-2" data-bs-dismiss="modal" style="background: #f3f4f6; color: #4b5563; border-radius: 10px; font-size: 14px; border: none;">Batalkan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                @endforeach

                <script>
                    function updateEditPill(element, type, id) {
                        const pills = document.querySelectorAll(`.pill-label-${type}-${id}`);
                        pills.forEach(pill => {
                            const isChecked = pill.previousElementSibling && pill.previousElementSibling.checked;
                            if (isChecked) {
                                pill.style.borderColor = '#3b82f6';
                                pill.style.background = '#eff6ff';
                                pill.style.color = '#1e40af';
                            } else {
                                pill.style.borderColor = '#e5e7eb';
                                pill.style.background = '#f9fafb';
                                pill.style.color = '#6b7280';
                            }
                        });
                    }
                </script>

            </div>
        </div>
    </div>
@endsection