<x-guest-layout layout="simple">
<div class="iq-auth-page">
@include('components.auth-effect')
<div class="row d-flex align-items-center iq-auth-container w-100">
            <div class="col-10 col-lg-4 offset-lg-7 offset-1">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <h3 class="text-center">Sign Up</h3>
                            <p class="text-center">Create your Hope UI account</p>
                        </div>
                        <div class="row d-flex justify-content-between">
                            <div class="form-group col-sm-12 col-md-6">
                                <label class="form-label" for="firstName">First Name</label>
                                <input type="email" class="form-control mb-0" id="firstName" placeholder="Enter First Name">
                            </div>
                            <div class="form-group col-sm-12 col-md-6">
                                <label class="form-label" for="lastname">Last Name</label>
                                <input type="password" class="form-control mb-0" id="lastname" placeholder="Enter Last Name">
                            </div>
                        </div>
                        <div class="row d-flex justify-content-between">
                            <div class="form-group col-sm-12 col-md-6">
                                <label class="form-label" for="Emailid">Email</label>
                                <input type="email" class="form-control mb-0" id="Emailid"  placeholder="Enter Email">
                            </div>
                            <div class="form-group col-sm-12 col-md-6">
                                <label class="form-label" for="Phone_NO">Phone No.</label>
                                <input type="password" class="form-control mb-0" id="Phone_NO" placeholder="Enter Phone Number">
                            </div>
                        </div>
                        <div class="row d-flex justify-content-between">
                            <div class="form-group col-sm-12 col-md-6">
                                <label class="form-label" for="firstPassword">Password</label>
                                <input type="email" class="form-control mb-0" id="firstPassword"  placeholder="Enter Password">
                            </div>
                            <div class="form-group col-sm-12 col-md-6">
                                <label class="form-label" for="ConfirmPAssword">Confirm Password</label>
                                <input type="password" class="form-control mb-0" id="ConfirmPAssword" placeholder="Enter Confirm Password">
                            </div>
                        </div>
                        <div class="text-center pb-3">
                            <input type="checkbox" class="form-check-input" id="aggrement-hopeui">
                            <label class="form-check-label" for="aggrement-hopeui">I agree with the terms of use</label>
                        </div>
                        <div class="text-center pb-3">
                            <button type="button" class="btn btn-primary">Sign Up</button>
                        </div>
                        <p class="text-center">Or sign in with other accounts?</p>
                        <div class="d-flex justify-content-center align-items-center gap-4 flex-wrap">
                            <a href="#"><img src="{{asset('images/brands/fb.svg')}}" alt="fb" loading="lazy"></a>
                            <a href="#"><img src="{{asset('images/brands/gm.svg')}}" alt="gm" loading="lazy"></a>
                            <a href="#"><img src="{{asset('images/brands/im.svg')}}" alt="im" loading="lazy"></a>
                            <a href="#"><img src="{{asset('images/brands/li.svg')}}" alt="li" loading="lazy"></a>
                        </div>
                        <p class="text-center">Already have an Account<a href="{{route('animated.signin')}}" class="ms-2">Sign in</a></p>
                    </div>
                </div>
            </div>
        </div>
</div>
</x-guest-layout>
