@extends('layouts.app', ['title' => 'Sliders'])

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fa fa-image"></i> UPLOAD SLIDER
                        </h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.slider.store') }}"
                              method="POST"
                              enctype="multipart/form-data"
                        >
                            @csrf

                            <div class="form-group">
                                <label>GAMBAR</label>
                                <input type="file"
                                       name="image"
                                       class="form-control @error('image') is-invalid @enderror"
                                >
                                @error('image')
                                    <div class="alert alert-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>LINK</label>
                                <input type="text"
                                       name="link"
                                       value="{{ old('link') }}"
                                       placeholder="Masukkan link"
                                       class="form-control @error('link') is-invalid @enderror"
                                >
                                @error('link')
                                    <div class="alert alert-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <button class="btn btn-primary mr-1 btn-submit" type="submit">
                                <i class="fa fa-paper-plane"></i> SIMPAN
                            </button>
                            <button class="btn btn-warning btn-reset" type="reset">
                                <i class="fa fa-redo"></i> RESET
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card border-0 shadow mt-3 mb-4">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fa fa-laptop"></i> SLIDERS
                        </h6>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">NO.</th>
                                        <th scope="col">GAMBAR</th>
                                        <th scope="col">LINK</th>
                                        <th scope="col">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($sliders as $no => $slider)
                                        <tr>
                                            <th scope="row" class="text-center">
                                                {{ ++$no + ($sliders->currentPage() - 1) * $sliders->perPage() }}
                                            </th>
                                            <td class="text-center">
                                                <img src="{{ $slider->image }}"
                                                     alt="slider-image"
                                                     class="rounded"
                                                     style="width: 200px;"
                                                >
                                            </td>
                                            <td>{{ $slider->link }}</td>
                                            <td class="text-center">
                                                <button onclick="hapus(this.id)"
                                                        id="{{ $slider->id }}"
                                                        class="btn btn-sm btn-danger"
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
                            <div class="text-center">
                                {{ $sliders->links("vendor.pagination.bootstrap-4") }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function hapus(id) {
            var id = id;
            var token = $("meta[name='csrf-token']").attr("content");

            swal({
                title: 'APA ANDA YAKIN',
                text: 'INGIN MENGHAPUS DATA INI>',
                icon: 'warning',
                buttons: [
                    'TIDAK',
                    'YA'
                ],
                dangerMode: true,
            }).then(function (isConfirm) {
                if (isConfirm) {
                    jQuery.ajax({
                        url: "{{ route('admin.slider.index') }}/" + id,
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
                                    buttons: false,
                                }).then(function () {
                                    location.reload();
                                });
                            } else {
                                swal({
                                    title: 'GAGAL',
                                    text: `DATA GAGAL TERHAPUS,@if (session()->has('message')) {{ session('message') }} @endif`,
                                    icon: 'error',
                                    timer: 1000,
                                    showConfirmButton: false,
                                    showCancelButton: false,
                                    buttons: false,
                                }).then(function () {
                                    location.reload();
                                });
                            }
                        }
                    });
                } else {
                    return true;
                }
            })
        }
    </script>
@endsection
