@extends('layouts.app', ['title' => 'Kategori'])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid mb-5">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold"><i class="fas fa-folder"></i> KATEGORI</h6>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.category.index') }}" method="GET">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <a href="{{ route('admin.category.create') }}"
                                           class="btn btn-primary btn-sm"
                                           style="padding-top: 7px;"
                                        >
                                            <i class="fa fa-plus-circle"></i> TAMBAH
                                        </a>
                                    </div>
                                    <input type="text"
                                           class="form-control"
                                           name="q"
                                           placeholder="Cari kategori"
                                    >
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-search"></i> CARI
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col" style="text-align: center;width: 6%;">NO.</th>
                                        <th scope="col">GAMBAR</th>
                                        <th scope="col">NAMA KATEGORI</th>
                                        <th scope="col" style="text-align: center;width: 15%;">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($categories as $no => $category)
                                    <tr>
                                        <th scope="row" style="text-align: center;">
                                            {{ ++$no + ($categories->currentPage()-1) * $categories->perPage() }}
                                        </th>
                                        <td class="text-center">
                                            <img src="{{ $category->image }}" alt="image" style="width: 50px;">
                                        </td>
                                        <td>
                                            {{ $category->name }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.category.edit', $category->id) }}"
                                               class="btn btn-sm btn-primary"
                                            >
                                                <i class="fa fa-pencil-alt"></i>
                                            </a>

                                            <button class="btn btn-sm btn-danger"
                                                    onclick="hapus(this.id)"
                                                    id="{{ $category->id }}"
                                            >
                                                <i class="fa fa-trash"></i>
                                            </button>

                                        </td>
                                    </tr>
                                    @empty
                                        <div class="alert alert-danger">
                                            Data belum tersedia.
                                        </div>
                                    @endforelse
                                </tbody>
                            </table>
                            <div style="text-align: center;">
                                {{ $categories->links('vendor.pagination.bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <script>
        // ajax delete
        function hapus(id) {
            var id = id;
            var token = $("meta[name='csrf-token']").attr("content");

            swal({
                title: "APA ANDA YAKIN?",
                text: "INGIN MENGHAPUS DATA INI?",
                icon: "warning",
                buttons: [
                    'TIDAK',
                    'YA'
                ],
                dangerMode: true,
            }).then(function (isConfirm) {
                if (isConfirm) {
                    jQuery.ajax({
                        url: "{{ route('admin.category.index') }}/" + id,
                        data: {
                            'id': id,
                            '_token': token,
                        },
                        type: 'DELETE',
                        success: function (response) {
                            if (response.status == 'success') {
                                swal({
                                    title: 'BERHASIL',
                                    text: 'DATA BERHASIL TERHAPUS',
                                    icon: 'success',
                                    timer: 1000,
                                    showConfirmButton: false,
                                    showCancelButton: false,
                                    buttons: false
                                }).then(function () {
                                    location.reload();
                                });
                            } else {
                                swal({
                                    title: 'GAGAL',
                                    text: 'DATA GAGAL TERHAPUS,@if (session()->has('message')) {{ session('message') }} @endif',
                                    icon: 'error',
                                    timer: 1000,
                                    showConfirmButton: false,
                                    showCancelButton: false,
                                    buttons: false
                                }).then(function () {
                                    location.reload();
                                });
                            }
                        }
                    })
                } else {
                    return true;
                }
            })
        }
    </script>
@endsection
