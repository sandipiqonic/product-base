<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Wizard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .d-none { display: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="container mt-5">
            <div class="card">
                <div class="card-header">
                    <h3>Setup Wizard</h3>
                    <ul class="nav nav-tabs card-header-tabs" id="setupTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="step-1-tab" data-toggle="tab" href="#step-1" role="tab" aria-controls="step-1" aria-selected="true">Step 1</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link disabled" id="step-2-tab" data-toggle="tab" href="#step-2" role="tab" aria-controls="step-2" aria-selected="false">Step 2</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link disabled" id="step-3-tab" data-toggle="tab" href="#step-3" role="tab" aria-controls="step-3" aria-selected="false">Step 3</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link disabled" id="step-4-tab" data-toggle="tab" href="#step-4" role="tab" aria-controls="step-4" aria-selected="false">Step 4</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('setup.store') }}" id="setup-form">
                        @csrf

                        <div class="tab-content" id="setupTabContent">
                            <!-- Step 1: Terms and Conditions -->
                            <div class="tab-pane fade show active setup-step" id="step-1" role="tabpanel" aria-labelledby="step-1-tab">
                                <h4>Step 1: Terms & Conditions</h4>
                                <div class="form-group">
                                    <input type="checkbox" name="terms" id="terms" {{ old('terms') ? 'checked' : '' }} required>
                                    <label for="terms">I agree to the terms and conditions</label>
                                    @error('terms')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="button" class="btn btn-primary next-btn" id="nextBtnStep1">Next</button>
                            </div>

                            <!-- Step 2: Check Requirements -->
                            <div class="tab-pane fade setup-step" id="step-2" role="tabpanel" aria-labelledby="step-2-tab">
                                <h4>Step 2: Check Requirements</h4>
                                <ul class="list-group mb-3" id="requirementsList">
                                    @foreach($requirements as $requirement => $status)
                                        <li class="list-group-item d-flex justify-content-between align-items-center" id="requirement-{{ $requirement }}">
                                            {{ $requirement }}
                                            @if($status)
                                                <span class="badge badge-success">OK</span>
                                            @else
                                                <span class="badge badge-danger">Failed</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn btn-secondary prev-btn">Previous</button>
                                <button type="button" class="btn btn-primary next-btn" id="nextBtnStep2">Next</button>
                            </div>

                            <!-- Step 3: Database Configuration -->
                            <div class="tab-pane fade setup-step" id="step-3" role="tabpanel" aria-labelledby="step-3-tab">
                                <h4>Step 3: Database Configuration</h4>
                                <div class="form-group">
                                    <label for="db_host">Database Host</label>
                                    <input type="text" class="form-control" id="db_host" name="db_host" value="{{ old('db_host') }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="db_port">Database Port</label>
                                    <input type="number" class="form-control" id="db_port" name="db_port" value="{{ old('db_port') }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="db_database">Database Name</label>
                                    <input type="text" class="form-control" id="db_database" name="db_database" value="{{ old('db_database') }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="db_username">Database Username</label>
                                    <input type="text" class="form-control" id="db_username" name="db_username" value="{{ old('db_username') }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="db_password">Database Password</label>
                                    <input type="password" class="form-control" id="db_password" name="db_password">
                                </div>
                                <button type="button" class="btn btn-secondary prev-btn">Previous</button>
                                <button type="button" class="btn btn-primary next-btn" id="nextBtnStep3">Next</button>
                            </div>

                            <!-- Step 4: User Registration -->
                            <div class="tab-pane fade setup-step" id="step-4" role="tabpanel" aria-labelledby="step-4-tab">
                                <h4>Step 4: User Registration</h4>
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="form-group">
                                    <label for="password_confirmation">Confirm Password</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                </div>
                                <button type="button" class="btn btn-secondary prev-btn">Previous</button>
                                <button type="submit" class="btn btn-success">Finish</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('#setupTabs .nav-link');
            const nextBtnStep1 = document.getElementById('nextBtnStep1');
            const nextBtnStep2 = document.getElementById('nextBtnStep2');
            const nextBtnStep3 = document.getElementById('nextBtnStep3');
            const prevBtns = document.querySelectorAll('.prev-btn');
            let currentStep = 0;

            function showStep(step) {
                tabs.forEach((tab, index) => {
                    if (index <= step) {
                        tab.classList.remove('disabled');
                    } else {
                        tab.classList.add('disabled');
                    }

                    if (index === step) {
                        tab.classList.add('active');
                        document.querySelector(tab.getAttribute('href')).classList.add('show', 'active');
                    } else {
                        tab.classList.remove('active');
                        document.querySelector(tab.getAttribute('href')).classList.remove('show', 'active');
                    }
                });

                if (step === 0) {
                    nextBtnStep1.disabled = false; // Always enable Next button for Step 1
                } else if (step === 1) {
                    nextBtnStep2.disabled = !checkRequirements(); // Disable if requirements not met
                } else if (step === 2) {
                    nextBtnStep3.disabled = validateDatabaseConfig(); // Disable if database config not valid
                }
            }

            function checkRequirements() {
                let allPassed = true;
                const requirementsList = document.getElementById('requirementsList').getElementsByTagName('li');

                for (let i = 0; i < requirementsList.length; i++) {
                    const badge = requirementsList[i].querySelector('.badge');
                    if (badge.textContent.trim() === 'Failed') {
                        allPassed = false;
                        break;
                    }
                }

                return allPassed;
            }

            function validateDatabaseConfig() {
                const dbHost = document.getElementById('db_host').value;
                const dbPort = document.getElementById('db_port').value;
                const dbDatabase = document.getElementById('db_database').value;
                const dbUsername = document.getElementById('db_username').value;
                const dbPassword = document.getElementById('db_password').value;

                // Validate each input field
                const isHostValid = dbHost.trim() !== '';
                const isPortValid = dbPort.trim() !== '' && /^\d+$/.test(dbPort); // Ensure it's a number
                const isDatabaseValid = dbDatabase.trim() !== '';
                const isUsernameValid = dbUsername.trim() !== '';
                const isPasswordValid = true; // Password may be optional

                // Enable next button if all fields are valid
                const isValid = isHostValid && isPortValid && isDatabaseValid && isUsernameValid && isPasswordValid;
                return isValid;
            }



            nextBtnStep1.addEventListener('click', () => {
                const termsCheckbox = document.getElementById('terms');
                if (termsCheckbox.checked) {
                    currentStep++;
                    showStep(currentStep);
                } else {
                    alert('Please agree to the terms and conditions.');
                }
            });

            nextBtnStep2.addEventListener('click', () => {
                if (checkRequirements()) {
                    currentStep++;
                    showStep(currentStep);
                } else {
                    alert('Please resolve all failed requirements before proceeding.');
                }
            });

            nextBtnStep3.addEventListener('click', () => {
                if (validateDatabaseConfig()) {
                    currentStep++;
                    showStep(currentStep);
                } else {
                    alert('Please enter valid database configuration details.');
                }
            });

            prevBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    currentStep--;
                    showStep(currentStep);
                });
            });
        });
    </script>
</body>
</html>
