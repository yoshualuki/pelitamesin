@extends('customer.template')

@section('content')
    <div class="container py-4">
        <div class="row">
            <!-- Main Content -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Profil Saya</h5>
                    </div>
                    <div class="card-body">
                        <form id="profileForm" action="{{ route('customer.profile.update') }}" method="POST">
                            @csrf
                            @method('POST')

                            <!-- Personal Information -->
                            <div class="mb-4">
                                <h6 class="border-bottom pb-2 mb-3">Informasi Pribadi</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control" name="name"
                                            value="{{ old('name', $user->name) }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                                        <small class="text-muted">Email tidak dapat diubah</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nomor HP</label>
                                        <input type="tel" class="form-control" name="phone"
                                            value="{{ old('phone', $user->phone) }}" required>
                                        @error('phone')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Address Information -->
                            <div class="mb-4">
                                <h6 class="border-bottom pb-2 mb-3">Alamat Utama</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Provinsi</label>
                                        <select class="form-select" name="province_id" id="provinceSelect" required>
                                            <option value="">Pilih Provinsi</option>
                                            @foreach ($provinces as $province)
                                                <option value="{{ $province['province_id'] }}"
                                                    {{ old('province_id', $user->province_id) == $province['province_id'] ? 'selected' : '' }}>
                                                    {{ $province['province'] }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <!-- Tambahkan input hidden untuk nama provinsi -->
                                        <input type="hidden" name="province_name" id="provinceName"
                                            value="{{ old('province_name', $user->province) }}">
                                        @error('province_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kota/Kabupaten</label>
                                        <select class="form-select" name="city_id" id="citySelect" required>
                                            <option value="">Pilih Kota/Kabupaten</option>
                                            @if ($cities)
                                                @foreach ($cities as $city)
                                                    <option value="{{ $city['city_id'] }}"
                                                        {{ old('city_id', $user->city_id) == $city['city_id'] ? 'selected' : '' }}>
                                                        {{ $city['city_name'] }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <!-- Tambahkan input hidden untuk nama kota -->
                                        <input type="hidden" name="city_name" id="cityName"
                                            value="{{ old('city_name', $user->city) }}">
                                        @error('city_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kode Pos</label>
                                        <input type="text" class="form-control" name="postal_code"
                                            value="{{ old('postal_code', $user->postal_code) }}" required>
                                        @error('postal_code')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label">Alamat Lengkap</label>
                                        <textarea class="form-control" name="address" rows="3" required>{{ old('address', $user->address) }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .list-group-item.active {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .card-header {
            font-weight: 600;
        }

        .form-label {
            font-weight: 500;
        }
    </style>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Dynamic dropdown for provinces, cities, and districts
        document.getElementById('provinceSelect').addEventListener('change', function() {
            const provinceId = this.value;
            const citySelect = document.getElementById('citySelect');

            citySelect.innerHTML = '<option value="">Loading...</option>';

            if (provinceId) {
                $.get(`/cart/cities?province_id=${provinceId}`, function(data) {
                    let options = '<option value="">Pilih Kota/Kabupaten</option>';
                    data.data.forEach(city => {
                        options +=
                            `<option value="${city.city_id}">${city.city_name}</option>`;
                    });
                    // $('#city').html(options).prop('disabled', false);
                    citySelect.innerHTML = options;
                }).fail(function() {
                    citySelect.innerHTML = '<option value="">Gagal memuat data</option>';
                });
                // $.get(`/cart/cities?province_id=${provinceId}`)
                //     .then(response => {
                //         response.data.array.forEach(element => {
                //             console.log(element);

                //         });
                //         citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                //         response.data.forEach(city => {
                //             citySelect.innerHTML +=
                //                 `<option value="${city.city_id}">${city.city_name}</option>`;
                //         });
                //     })
                //     .catch(error => {
                //         console.error('Error fetching cities:', error);
                //         citySelect.innerHTML = '<option value="">Gagal memuat data</option>';
                //     });
            } else {
                citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                document.getElementById('districtSelect').innerHTML = '<option value="">Pilih Kecamatan</option>';
            }
        });

        // Form submission with validation
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = e.target;
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';

            fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Profil berhasil diperbarui',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        let errorMessage = data.message || 'Terjadi kesalahan saat menyimpan';

                        if (data.errors) {
                            errorMessage = Object.values(data.errors).join('<br>');
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            html: errorMessage,
                            confirmButtonText: 'Tutup'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat menyimpan',
                        confirmButtonText: 'Tutup'
                    });
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                });
        });
    </script>
@endsection
