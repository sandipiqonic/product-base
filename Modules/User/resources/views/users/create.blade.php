<x-app-layout :assets="$assets ?? []" title="Add User" :isBanner="true">
    <div>
        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Create User</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('user.store') }}" class="row g-3 needs-validation" novalidate method="POST">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" required>
                                    <div class="invalid-feedback">
                                        Please enter a Username.
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="first_name" class="form-label">First name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                    <div class="invalid-feedback">
                                        Please enter a First name.
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="last_name" class="form-label">Last name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                    <div class="invalid-feedback">
                                        Please enter a Last name.
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                                    <div class="invalid-feedback">
                                        Please enter a email.
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="phone_number" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required>
                                    <div class="invalid-feedback">
                                        Please enter a Phone Number.
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <div class="invalid-feedback">
                                        Please enter a password.
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                    <div class="invalid-feedback">
                                        Please enter a confirmation password.
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="roles" class="form-label">Role</label>
                                    <select name="roles" id="roles" class="form-control">
                                        <option value="">Select Role</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role }}" {{ old('roles') == $role ? 'selected' : '' }}>{{ $role }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="status" class="form-label">Status</label>
                                <div class="form-check form-switch form-check-inline" style="margin-left: 20px;">
                                    <input class="form-check-input" type="checkbox" id="status" name="status" {{ old('status') == 'on' ? 'checked' : '' }}>
                                </div>
                            </div>
                            {{-- <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="image" class="form-label">Image</label><br>
                                    <div class="input-group">
                                        <input id="thumbnail" class="form-control" type="text" name="image" readonly>
                                        <button id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                                            <i class="fa fa-picture-o"></i> Choose
                                        </button>
                                    </div>
                                    <div id="holder" style="margin-top:15px;max-height:100px;"></div>
                                </div>
                            </div> --}}
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-primary" type="submit">Create</button>
                                    <a href="{{ route('user.index') }}" class="btn btn-outline-info">Back</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
        <script>
            $('#lfm').filemanager('image');
        </script>
    @endpush
</x-app-layout>
