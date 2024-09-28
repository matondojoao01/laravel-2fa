@extends('vendor.twofactorauth.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('security_verification.title') }}</div>

                <div class="card-body">
                    <p>{{ __('security_verification.instructions') }}</p>

                    @if($msg == 'invalid_code')
                        <div class="alert alert-danger" role="alert">
                            {{ __('security_verification.invalid_code') }}
                        </div>
                    @endif

                    @if($msg == 'user_not_found')
                        <div class="alert alert-danger" role="alert">
                            {{ __('security_verification.user_not_found') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('2fa.verify') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="2fa" class="col-md-4 col-form-label text-md-end">{{ __('security_verification.code_label') }}</label>

                            <div class="col-md-6">
                                <input id="2fa" type="text" class="form-control @error('2fa') is-invalid @enderror" name="2fa" required autofocus>

                                @error('2fa')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <input type="hidden" name="tk" value="{{ $tk }}">
                        <input type="hidden" name="id" value="{{ $id }}">
                        <input type="hidden" name="username" value="{{ $username }}">

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('security_verification.verify_button') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
