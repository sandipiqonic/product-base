<x-app-layout :assets="$assets ?? []" title='Users' :isBanner="true">
    <div>
        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">User List</h4>
                        </div>
                    </div>
                    <div class="container">
                        <div class="text-end mb-2">
                            <a href="{{ route('user.create') }}" class="btn btn-primary">Add User</a>
                        </div>
                        <div></div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>USER NAME</th>
                                    <th>NAME</th>
                                    <th>EMAIL</th>
                                    <th>PHONE NUMBER</th>
                                    <th>STATUS</th>
                                    <th>ROLE</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->first_name . " " .$user->last_name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone_number }}</td>
                                        <td>{{ $user->status }}</td>
                                        <td>
                                            @if($user->roles->isNotEmpty())
                                                {{ $user->roles->pluck('name')->join(', ') }}
                                            @else
                                                -
                                            @endif
                                    </td>
                                        <td>
                                            <a href="{{ route('user.edit', $user->id) }}" class="btn btn-outline-success btn-sm"><i class="fa fa-edit"></i></a>
                                            <form action="{{ route('user.destroy', $user->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm"><i class="fa fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

