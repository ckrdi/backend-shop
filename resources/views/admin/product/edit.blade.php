@extends('layouts.app', ['title' => 'Edit Produk'])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold"><i class="fas fa-folder"></i> EDIT PRODUK</h6>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.product.update', $product) }}"
                              method="POST"
                              enctype="multipart/form-data"
                        >
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label>GAMBAR</label>
                                <input type="file"
                                       name="image"
                                       class="form-control @error('image') is-invalid @enderror"
                                >

                                @error('image')
                                <div class="invalid-feedback" style="display: block">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>NAMA PRODUK</label>
                                <input type="text"
                                       name="title"
                                       value="{{ old('title', $product->title) }}"
                                       placeholder="Masukkan nama produk"
                                       class="form-control @error('title') is-invalid @enderror"
                                >

                                @error('title')
                                <div class="invalid-feedback" style="display: block">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>KATEGORI</label>
                                        <select name="category_id" class="form-control @error('category_id') is-invalid @enderror">
                                            <option value="">-- PILIH KATEGORI --</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}"
                                                        @if ($product->category_id == $category->id)
                                                            selected
                                                        @endif
                                                >
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('category_id')
                                        <div class="invalid-feedback" style="display: block;">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>BERAT (GRAM)</label>
                                        <input type="number"
                                               name="weight"
                                               class="form-control @error('weight') is-invalid @enderror"
                                               value="{{ old('weight', $product->weight) }}"
                                               placeholder="Berat produk (gram)"
                                        >
                                        @error('weight')
                                        <div class="invalid-feedback" style="display: block;">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>DESKRIPSI</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          name="description"
                                          rows="6"
                                          placeholder="Deskripsi produk"
                                >
                                    {{ old('description', $product->description) }}
                                </textarea>
                                @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>HARGA</label>
                                        <input type="number"
                                               name="price"
                                               class="form-control @error('price') is-invalid @enderror"
                                               value="{{ old('price', $product->price) }}"
                                               placeholder="Harga produk (Rupiah)"
                                        >

                                        @error('price')
                                        <div class="invalid-feedback" style="display: block;">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>DISKON (%)</label>
                                        <input type="number"
                                               name="discount"
                                               class="form-control @error('discount') is-invalid @enderror"
                                               value="{{ old('discount', $product->discount) }}"
                                               placeholder="Diskon produk (%)"
                                        >
                                        @error('discount')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
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
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
@endsection
