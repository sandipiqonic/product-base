<div class="card-body">
    <h4>Razorpay Payment Gateway Configuration</h4>
    <form class="row g-3 needs-validation" novalidate action="{{ route('paymentconfig.store') }}" method="POST">
        @csrf
        <input type="hidden" name="gateway" value="razorpay">
        <div class="col-md-6">
            <label for="razorpay_client_id" class="form-label">Razorpay Client ID</label>
            <input type="text" class="form-control" id="razorpay_client_id" name="config[razorpay_client_id]" value="{{ $config['razorpay_client_id'] ?? '' }}" required>
            <div class="invalid-feedback">
                Please provide a valid Razorpay Client Id.
            </div>
        </div>
        <div class="col-md-6">
            <label for="razorpay_secret" class="form-label">Razorpay Secret</label>
            <input type="text" class="form-control" id="razorpay_secret" name="config[razorpay_secret]" value="{{ $config['razorpay_secret'] ?? '' }}" required>
            <div class="invalid-feedback">
                Please provide a valid Razorpay Secret.
            </div>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>
