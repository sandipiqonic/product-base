<div class="card-body">
    <h3>Stripe Payment Gateway Configuration</h3>
    <form class="row g-3 needs-validation" novalidate action="{{ route('paymentconfig.store') }}" method="POST">
        @csrf
        <input type="hidden" name="gateway" value="stripe">
        <div class="col-md-6">
            <label for="stripe_key" class="form-label">Stripe Key</label>
            <input type="text" class="form-control" id="stripe_key" name="config[stripe_key]" value="{{ $config['stripe_key'] ?? '' }}" required>
            <div class="invalid-feedback">
                Please provide a valid Stripe Key.
            </div>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>

