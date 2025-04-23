@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Daftar Admin</h5>
                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                            data-bs-target="#createAdminModal">
                            <i class="fas fa-plus"></i> Tambah Admin
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- Search Box -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <form action="{{ route('admin.customer') }}" method="GET">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Cari nama/email..." value="{{ request('search') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="submit">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Customers Table -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Tanggal Daftar</th>
                                        <th>Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($admins as $admin)
                                        <tr>
                                            <td>{{ $admin->name }}</td>
                                            <td>{{ $admin->email }}</td>
                                            <td>{{ $admin->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <span
                                                    class="badge {{ $admin->active ? 'bg-success' : 'bg-secondary' }} status-badge"
                                                    id="status-badge-{{ $admin->id }}">
                                                    {{ $admin->active ? 'Aktif' : 'Nonaktif' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <button type="button"
                                                    class="btn btn-sm toggle-status-btn {{ $admin->active ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                                    data-id="{{ $admin->id }}"
                                                    data-active="{{ $admin->active ? 1 : 0 }}"
                                                    title="{{ $admin->active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                    <i
                                                        class="fas {{ $admin->active ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                                                    <span
                                                        class="toggle-label">{{ $admin->active ? 'Nonaktifkan' : 'Aktifkan' }}</span>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Tidak ada data admin</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between">
                            <div class="text-muted">
                                Menampilkan {{ $admins->firstItem() }} sampai {{ $admins->lastItem() }} dari
                                {{ $admins->total() }} entri
                            </div>
                            <div>
                                {{ $admins->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus customer ini?
                </div>
                <div class="modal-footer">
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Toggle admin active status via AJAX
            $('.toggle-status-btn').click(function() {
                var btn = $(this);
                var adminId = btn.data('id');
                var isActive = btn.data('active');
                btn.prop('disabled', true);

                $.ajax({
                    url: "{{ url('admin/useradmin/') }}/" + adminId + "/toggle",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        // Toggle UI
                        var newStatus = response.active ? 1 : 0;
                        btn.data('active', newStatus);

                        // Update badge
                        var badge = $('#status-badge-' + adminId);
                        if (newStatus) {
                            badge.removeClass('bg-secondary').addClass('bg-success').text(
                                'Aktif');
                            btn.removeClass('btn-outline-success').addClass(
                                'btn-outline-danger');
                            btn.find('i').removeClass('fa-user-check').addClass(
                                'fa-user-slash');
                            btn.find('.toggle-label').text('Nonaktifkan');
                            btn.attr('title', 'Nonaktifkan');
                        } else {
                            badge.removeClass('bg-success').addClass('bg-secondary').text(
                                'Nonaktif');
                            btn.removeClass('btn-outline-danger').addClass(
                                'btn-outline-success');
                            btn.find('i').removeClass('fa-user-slash').addClass(
                                'fa-user-check');
                            btn.find('.toggle-label').text('Aktifkan');
                            btn.attr('title', 'Aktifkan');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Gagal mengubah status admin.',
                            confirmButtonText: 'OK'
                        });
                    },
                    complete: function() {
                        btn.prop('disabled', false);
                    }
                });
            });

            // Delete button click handler
            $('.delete-btn').click(function() {
                var customerId = $(this).data('id');
                var url = "{{ route('admin.customer.destroy', ':id') }}";
                url = url.replace(':id', customerId);

                $('#deleteForm').attr('action', url);
                $('#deleteModal').modal('show');
            });

            $('input[name="search"]').focus();
        });
    </script>
@endsection

@push('styles')
    <style>
        .table th {
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
        }

        .btn-group .btn {
            padding: 0.25rem 0.5rem;
        }

        .pagination {
            margin-bottom: 0;
        }
    </style>
@endpush


<!-- Modal Create Admin -->
<div class="modal fade" id="createAdminModal" tabindex="-1" aria-labelledby="createAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="createAdminForm" method="POST" action="{{ route('admin.useradmin.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createAdminModalLabel">Tambah Admin Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="adminName" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="adminName" name="name" required
                            maxlength="255" placeholder="Nama admin">
                    </div>
                    <div class="mb-3">
                        <label for="adminEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="adminEmail" name="email" required
                            maxlength="255" placeholder="Email admin">
                    </div>
                    <div class="mb-3">
                        <label for="adminPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="adminPassword" name="password" required
                            minlength="6" placeholder="Password">
                    </div>
                    <div class="mb-3">
                        <label for="adminPasswordConfirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="adminPasswordConfirmation"
                            name="password_confirmation" required minlength="6" placeholder="Konfirmasi Password">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Admin</button>
                </div>
            </form>
        </div>
    </div>
</div>
