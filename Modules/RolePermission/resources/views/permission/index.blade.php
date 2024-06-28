<x-app-layout :assets="$assets ?? []" title="Permission" :isBanner="true">
    <style>
        .accordion > .card:not(:last-of-type) {
            border-bottom: 0;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }
        .accordion > .card {
            overflow: hidden;
        }
        .accordion > .card > .card-header {
            border-radius: 0;
            margin-bottom: 0px;
        }
        .card .card-header {
            background: transparent;
            border-radius: 0;
            padding: 20px;
            margin-bottom: 0;
            align-items: center !important;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-block card-stretch">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3 flex-wrap gap-3">
                            <h5 class="font-weight-bold">Permission List</h5>
                            {{-- @dd($auth_user->can('permission add')) --}}
                            @if($auth_user->can('permission add'))
                                    <a href="{{ route('permissions.create',['type'=>'permission']) }}" class="float-right btn btn-sm btn-primary loadRemoteModel"><i class="fa fa-plus-circle"></i> Add Permission</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                {{ Form::open(['route' => 'permissions.store','method' => 'post']) }}
                    <div class="accordion cursor" id="permissionList">
                        @foreach($permission as  $key => $data)
                            <?php
                                $a = str_replace("_"," ",$key);
                                $k = ucwords($a);
                            ?>
                            <div class="card mb-2">
                                <div class="card-header d-flex justify-content-between collapsed btn" id="heading_{{$key}}" data-toggle="collapse" data-target="#pr_{{$key}}" aria-expanded="false" aria-controls="pr_{{$key}}">
                                    <div class="header-title">
                                        <h6 class="mb-0 text-capitalize permission-text"> <i class="fa fa-plus mr-10"></i> {{ $data->name }}<span class="badge badge-secondary"></span></h6>
                                    </div>
                                </div>
                                <div id="pr_{{$key}}" class="collapse bg_light_gray" aria-labelledby="heading_{{$key}}" data-parent="#permissionList">
                                    <div class="card-body table-responsive">
                                        <table class="table text-center table-bordered bg_white">
                                            <tr>
                                                <th>Name</th>
                                                @foreach($roles as $role)
                                                    <th>{{ ucwords(str_replace('_',' ',$role->name)) }}</th>
                                                @endforeach
                                            </tr>
                                            @foreach($data->subpermission as $p)
                                                <tr>
                                                    <td class="text-capitalize">{{ $p->name }}</td>
                                                    @foreach($roles as $role)
                                                        <td>
                                                            <input class="checkbox no-wh permission_check" id="permission-{{$role->id}}-{{$p->id}}" type="checkbox" name="permission[{{$p->name}}][]" value='{{$role->name}}' {{ (checkRolePermission($role,$p->name)) ? 'checked' : '' }} @if($role->is_hidden) disabled @endif >
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </table>
                                        <input type="submit" name="Save" value="Save" class="btn btn-md btn-primary float-right mall-10">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function($) {
        "use strict";
        $(document).ready(function() {
            console.log("Document ready, initializing accordion toggle functionality.");

            $('#permissionList .card-header').on('click', function() {
                var target = $(this).next('.collapse');

                if (target.hasClass('show')) {
                    target.collapse('hide');
                } else {
                    $('#permissionList .collapse.show').collapse('hide'); // Hide any currently open sections
                    target.collapse('show');
                }

                // Toggle the icon
                var $icon = $(this).find('i');
                if ($icon.hasClass('fa-plus')) {
                    $icon.removeClass('fa-plus').addClass('fa-minus');
                } else {
                    $icon.removeClass('fa-minus').addClass('fa-plus');
                }
            });

            // Optional: Add a listener for collapse events to handle icons correctly
            $('#permissionList').on('hidden.bs.collapse shown.bs.collapse', '.collapse', function() {
                var $header = $(this).prev('.card-header');
                var $icon = $header.find('i');

                if ($(this).hasClass('show')) {
                    $icon.removeClass('fa-plus').addClass('fa-minus');
                } else {
                    $icon.removeClass('fa-minus').addClass('fa-plus');
                }
            });
        });
    })(jQuery);
</script>
@endpush
</x-app-layout>
