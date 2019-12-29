@extends('layouts.template')

@section('title', 'Users')

@section('main')
    <h1>Users</h1>
    @include('shared.alert')
    <form method="get" action="/admin/users" id="searchForm">
        <div class="row">
            <div class="col-sm-6 mb-2">
                <label for="Name">Filter Name or Email</label>
                <input type="text" class="form-control" name="name" id="name"
                       value="{{ request()->name }}"
                       placeholder="Filter Name Or Email">
            </div>
            <div class="col-sm-4 mb-2">
                <label for="sort">Sort by</label>
                <select class="form-control" name="sort" id="sort">
                    @foreach($orderlist as $i => $sort )
                        <option value="{{$i}}"
                                {{ (request()->sort == $i ? 'selected' : '') }}>{{$sort['name']}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Active</th>
                <th>Admin</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>@if ($user->active == 1)
                            <i class="fas fa-check"></i>
                    @endif</td>
                    <td>@if ($user->admin == 1)
                            <i class="fas fa-check"></i>
                    @endif</td>
                    <td>
                        <form action="/admin/users/{{ $user->id }}" method="post">
                            @method('delete')
                            @csrf
                            <div class="btn-group btn-group-sm">
                                <a href="/admin/users/{{ $user->id }}/edit" class="btn btn-outline-success"
                                   data-toggle="tooltip"
                                   title="Edit {{ $user->name }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="submit" class="btn btn-outline-danger"
                                        data-toggle="tooltip"
                                        title="Delete {{ $user->name }}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{$users->links()}}
    </div>
    @include('admin.users.modal')
@endsection

@section('script_after')
    <script>
        $(function () {

            $('tbody').on('click', '.btn-delete', function () {
                // Get data attributes from td tag
                let id = $(this).closest('td').data('id');
                let name = $(this).closest('td').data('name');
                // Set some values for Noty
                let text = `<p>Delete the user <b>${name}</b>?</p>`;
                let type = 'warning';
                let btnText = 'Delete genre';
                let btnClass = 'btn-success';


                // Show Noty
                let modal = new Noty({
                    timeout: false,
                    layout: 'center',
                    modal: true,
                    type: type,
                    text: text,
                    buttons: [
                        Noty.button(btnText, `btn ${btnClass}`, function () {
                            // Delete genre and close modal
                            deleteUser(id);
                            modal.close();
                        }),
                        Noty.button('Cancel', 'btn btn-secondary ml-2', function () {
                            modal.close();
                        })
                    ]
                }).show();
            });

            // Delete a user
            function deleteUser(id) {
                // Delete the genre from the database
                let pars = {
                    '_token': '{{ csrf_token() }}',
                    '_method': 'delete'
                };
                $.post(`/admin/users/${id}`, pars, 'json')
                    .done(function (data) {
                        console.log('data', data);
                        // Show toast
                        new Noty({
                            type: data.type,
                            text: data.text
                        }).show();
                        // Rebuild the table
                        loadTable();
                    })
                    .fail(function (e) {
                        console.log('error', e);
                    })
            }
        }
    </script>
@endsection