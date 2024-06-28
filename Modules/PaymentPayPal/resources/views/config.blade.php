<div class="card-body">
    <h3>PayPal Payment Gateway Configuration</h3>
    <form class="row g-3 needs-validation" novalidate action="{{ route('paymentconfig.store') }}" method="POST">
        @csrf
        <input type="hidden" name="gateway" value="paypal">
        <div class="col-md-6">
            <label for="paypal_client_id" class="form-label">PayPal Client ID</label>
            <input type="text" class="form-control" id="paypal_client_id" name="config[paypal_client_id]" value="{{ $config['paypal_client_id'] ?? '' }}" required>
            <div class="invalid-feedback">
                Please provide a valid Paypal Client Id.
            </div>
        </div>
        <div class="col-md-6">
            <label for="paypal_client_mitali" class="form-label">PayPal Client</label>
            <input type="text" class="form-control" id="paypal_client_mitali" name="config[paypal_client_mitali]" value="{{ $config['paypal_client_mitali'] ?? '' }}" required>
            <div class="invalid-feedback">
                Please provide a valid Paypal Client.
            </div>
        </div>
        <div class="col-md-6">
            <label for="paypal_secret" class="form-label">PayPal Secret</label>
            <input type="text" class="form-control" id="paypal_secret" name="config[paypal_secret]" value="{{ $config['paypal_secret'] ?? '' }}" required>
            <div class="invalid-feedback">
                Please provide a valid Paypal Secret.
            </div>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>
