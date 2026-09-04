@extends('app')

@section('content')
    <div class="container">

        <form action="{{ route('setting-update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Application</h5>
                </div>

                <div class="card-body">

                    <div class="mb-3">
                        <label for="app_name" class="form-label">
                            Application Name
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="app_name"
                            name="app_name"
                            value="{{ old('app_name', $settings['app_name'] ?? '') }}"
                        >

                        @error('app_name')
                            <div class="text-danger mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="app_logo" class="form-label">
                            Application Logo
                        </label>

                        <input
                            type="file"
                            class="form-control"
                            id="app_logo"
                            name="app_logo"
                            accept="image/*"
                        >

                        @error('app_logo')
                            <div class="text-danger mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>


                    {{-- App Favicon --}}
                    <div class="mb-3">
                        <label for="app_favicon" class="form-label">
                            Application Favicon
                        </label>

                        <input
                            type="file"
                            class="form-control"
                            id="app_favicon"
                            name="app_favicon"
                            accept="image/*"
                        >

                        @error('app_favicon')
                            <div class="text-danger mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Contact Information</h5>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <label for="app_email" class="form-label">
                            Email
                        </label>

                        <input
                            type="email"
                            class="form-control"
                            id="app_email"
                            name="app_email"
                            value="{{ old('app_email', $settings['app_email'] ?? '') }}"
                        >

                        @error('app_email')
                            <div class="text-danger mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="app_phone" class="form-label">
                            Phone
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="app_phone"
                            name="app_phone"
                            value="{{ old('app_phone', $settings['app_phone'] ?? '') }}"
                        >

                        @error('app_phone')
                            <div class="text-danger mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="app_address" class="form-label">
                            Address
                        </label>

                        <textarea
                            class="form-control"
                            id="app_address"
                            name="app_address"
                            rows="3"
                        >{{ old('app_address', $settings['app_address'] ?? '') }}</textarea>

                        @error('app_address')
                            <div class="text-danger mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>
            </div>


            {{-- Save --}}
            <div class="mb-3">
                <button class="btn btn-primary" type="submit">
                    Save Settings
                </button>
            </div>

        </form>

    </div>
@endsection