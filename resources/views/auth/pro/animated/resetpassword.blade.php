<x-guest-layout layout="simple">
<div class="iq-auth-page">
    @include('components.auth-effect')
    <div class="row align-items-center iq-auth-container w-100">
            <div class="col-10 col-lg-4 offset-lg-7 offset-1">
                <div class="card">
                    <div class="card-body">
                        <h4>Reset Password</h4>
                        <p>Enter your email address and we’ll send you an email with instructions to reset your password</p>
                        <div class="form-group me-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control mb-0"  placeholder="Enter Email">
                            </div>
                        <button type="button" class="btn btn-primary">Reset</button>
                    </div>
                </div>
            </div>
        </div>
</div>
</x-guest-layout>