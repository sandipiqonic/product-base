<x-app-layout :assets="$assets ?? []" title='Plugins' :isBanner="true">
    <div>
        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Upload Plugin</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form class="row g-3 needs-validation" novalidate method="POST"
                            action="{{ route('plugins.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="col-md-6">
                                <label for="zip" class="form-label">Upload Plugin Zip file</label>
                                <input type="file" class="form-control" id="zip" name="zip" required>
                                <div class="invalid-feedback">
                                    Please provide a valid zip file.
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary" type="submit">Upload</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <div class="card bg-light">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <input type="text" class="form-control" placeholder="Search...">
                            </div>
                            <div>
                                <button class="btn btn-outline-primary ml-2 mb-2 status-button active" data-status="all">All ({{ $totalPlugins }})</button>
                                <button class="btn btn-outline-primary ml-2 mb-2 status-button" data-status="active">Activated ({{ $activePlugins }})</button>
                                <button class="btn btn-outline-primary ml-2 mb-2 status-button" data-status="inactive">Not Activated ({{ $inactivePlugins }})</button>
                            </div>
                        </div><br><br>
                        <div class="row">
                            @forelse ($plugins as $plugin)
                                <div class="col-lg-3 col-md-4 col-sm-6 mb-4 plugin-card" data-status="{{ $plugin->status }}" id="plugin_card_{{ $plugin->id }}">
                                    <div class="card">
                                        <div class="card-body">
                                            <img src="https://picsum.photos/400/200?random={{ rand() }}"
                                                alt="" class="rounded" width="100%"><br>
                                            <h4 class="card-title mt-3">{{ $plugin->name }}</h4>
                                            <p class="card-text">{{ $plugin->description }}</p>
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <small class="text-muted">
                                                        <span class="text-dark">By:</span> IQONIC DESIGN
                                                    </small>
                                                </div>
                                                <div>
                                                    <p class="card-text">
                                                        <small class="text-muted"><span
                                                                class="text-dark">Version:</span>
                                                            {{ $plugin->version }}</small>
                                                    </p>
                                                </div>
                                            </div>
                                            <button
                                                class="btn {{ $plugin->status === 'active' ? 'btn-danger' : 'btn-success' }} mt-3"
                                                onclick="togglePluginStatus({{ $plugin->id }}, '{{ $plugin->status }}')">{{ ucfirst($plugin->status === 'active' ? 'Deactive' : ' Active') }}</button>
                                            <a href="#" class="btn btn-light mt-3" onclick="confirmDelete({{ $plugin->id }})">Remove</a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-light text-center" role="alert">
                                        No data found
                                    </div>
                                </div>
                            @endforelse
                            <div id="no-data-found" class="alert alert-light text-center" role="alert" style="display: none;">
                                No data found
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('.status-button').click(function() {
                    $('.status-button').removeClass('active'); // Remove active class from all buttons
                    $(this).addClass('active'); // Add active class to the clicked button

                    var status = $(this).data('status');
                    filterPlugins(status);
                });

                // Function to filter plugins based on status
                    function filterPlugins(status) {
                        $('.plugin-card').hide(); // Hide all plugins

                        if (status === 'all') {
                            $('.plugin-card').show(); // Show all plugins
                        } else {
                            $('.plugin-card[data-status="' + status + '"]').show(); // Show plugins with the selected status
                        }

                        // Check if there are visible plugins after filtering
                        if ($('.plugin-card:visible').length === 0) {
                            $('#no-data-found').show(); // Display "No data found" message
                        } else {
                            $('#no-data-found').hide(); // Hide "No data found" message
                        }
                    }

                    // Function to filter plugins by name
                    function searchPluginsByName(searchText) {
                        $('.plugin-card').hide(); // Hide all plugins
                        $('.plugin-card').filter(function() {
                            return $(this).find('.card-title').text().toLowerCase().includes(searchText.toLowerCase());
                        }).show(); // Show plugins whose names contain the search text

                        // Check if there are visible plugins after filtering
                        if ($('.plugin-card:visible').length === 0) {
                            $('#no-data-found').show(); // Display "No data found" message
                        } else {
                            $('#no-data-found').hide(); // Hide "No data found" message
                        }
                    }
                    // Initial filtering when the page loads
                    filterPlugins('all');

                    // Event listener for status buttons
                    $('.status-button').click(function() {
                        var status = $(this).data('status'); // Get the status from data attribute
                        filterPlugins(status); // Filter plugins based on status
                    });

                    // Event listener for search input
                    $('input[type="text"]').on('input', function() {
                        var searchText = $(this).val(); // Get the search text
                        searchPluginsByName(searchText); // Search plugins by name
                    });

                    $('form').on('submit', function(e) {
                        e.preventDefault();
                        var form = this;
                        var formData = new FormData(form);

                        $.ajax({
                            url: $(form).attr('action'),
                            method: $(form).attr('method'),
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                if (response.status === 'confirm_update') {
                                    Swal.fire({
                                        title: "Update Plugin",
                                        text: response.message,
                                        icon: "warning",
                                        showCancelButton: true,
                                        confirmButtonText: 'Yes, update it!',
                                        cancelButtonText: 'No, cancel!',
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            formData.append('confirm_update', true);
                                            $.ajax({
                                                url: $(form).attr('action'),
                                                method: $(form).attr('method'),
                                                data: formData,
                                                processData: false,
                                                contentType: false,
                                                success: function(response) {
                                                    Swal.fire("Success", response.message, "success").then(() => {
                                                        window.location.reload();
                                                    });
                                                },
                                                error: function(response) {
                                                    Swal.fire("Error", "An error occurred while updating the plugin.", "error");
                                                }
                                            });
                                        }
                                    });
                                } else {
                                    Swal.fire("Success", "Plugin uploaded successfully.", "success").then(() => {
                                        window.location.reload();
                                    });
                                }
                            },
                            error: function(response) {
                                Swal.fire("Error", "An error occurred while uploading the plugin.", "error");
                            }
                        });
                    });

                });

                function togglePluginStatus(id, status) {
                    const action = status === 'active' ? 'deactivate' : 'activate';
                    Swal.fire({
                        title: `Are you sure you want to ${action} this plugin?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '{{ route('plugins.activate') }}',
                                type: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    id: id,
                                    status: status === 'active' ? 'inactive' : 'active'
                                },
                                success: function(response) {
                                    // Show success message
                                    Swal.fire({
                                        title: 'Success',
                                        text: response.message,
                                        icon: 'success'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            // Reload the page
                                            location.reload();
                                        }
                                    });
                                },
                                error: function(response) {
                                    // Show error message
                                    Swal.fire({
                                        title: 'Error',
                                        text: 'An error occurred while changing plugin status',
                                        icon: 'error'
                                    });
                                }
                            });
                        }
                    });
                }

                function confirmDelete(id) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '{{ route('plugin.remove') }}',
                                type: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    id: id
                                },
                                success: function(response) {
                                    // Handle success
                                    console.log(response);
                                    Swal.fire(
                                        'Deleted!',
                                        'Your plugin has been removed successfully.',
                                        'success'
                                    ).then(() => {
                                        // window.location.reload();
                                        $('#plugin_card_' + id).remove();
                                    });
                                },
                                error: function(xhr, status, error) {
                                    // Handle errors
                                    console.error(xhr.responseText);
                                    Swal.fire(
                                        'Error!',
                                        'An error occurred while deleting the model.',
                                        'error'
                                    );
                                }
                            });
                        }
                    });
                }
            </script>
        @endpush
</x-app-layout>
