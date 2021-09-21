@extends('layouts.auth', ['title' => 'Verify Email'])

@section('content')

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-md-4">
                <div class="img-logo text-center mt-5">
                    <img src="{{ asset('assets/img/company.png') }}"
                         style="width: 80px;"
                    >
                </div>
                <div class="card o-hidden border-0 shadow-lg mb-3 mt-5">
                    <div class="card-body p-4">
                        @if (session('status') == 'verification-link-sent')
                            <div class="alert alert-success alert-dismissible">
                                A new email verification link has been emailed to you!
                            </div>
                        @endif
                        <div class="text-center">
                            <h1 class="h5 text-gray-900 mb-3">
                                Check your email for verification.
                                If it's not there, click resend.
                            </h1>
                        </div>

                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf

                            <div class="form-group">
                                <button type="submit"
                                        class="btn btn-primary btn-lg btn-block"
                                        tabindex="4"
                                >
                                    RESEND VERIFICATION LINK
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection
