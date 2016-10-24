@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">All Users</div>

                <div class="panel-body">
                    <form action="{{ url('/edituser') }}" method="post">
                        {!! csrf_field() !!}
                        <table class="table table-bordered table-hover">
                            <tbody>
                                <tr>
                                    <th>Name</th>
                                    <th>E-mail</th>
                                    <th>Organization</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Edit</th>
                                </tr>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>{{$user->organization->name}}</td>
                                        <td>
                                            <select disabled class="form-control" name="role">
                                                <option value="1" {{$user->role == 1 ? 'selected' : ''}}>User</option>
                                                <option value="2" {{$user->role == 2 ? 'selected' : ''}}>HR Admin</option>
                                                <option value="3" {{$user->role == 3 ? 'selected' : ''}}>Supper Admin</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select disabled class="form-control" name="status">
                                                <option value="2" {{$user->status == 0 ? 'selected' : ''}}>Inactive</option>
                                                <option value="1" {{$user->status == 1 ? 'selected' : ''}}>Active</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="hidden" name="userid" value="{{$user->id}}" disabled>
                                            <button class="btn" type="button">Edit<i class="fa fa-pencil"></i></button>
                                            <button class="btn" type="submit" style="display:none;">Save<i class="fa fa-check"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('button[type=button]').click(function(){
            $('form').find('select').each(function(){$(this).prop('disabled', true)});
            $('form').find('input').not('input[name=_token]').each(function(){$(this).prop('disabled', true)});
            $('form').find('button[type=submit]').each(function(){$(this).hide()});
            $('form').find('button[type=button]').each(function(){$(this).show()});
            $(this).hide();
            $(this).next('button').show();
            $(this).prev('input').removeAttr('disabled');
            $(this).closest('tr').find('select').each(function(){$(this).removeAttr('disabled')});
        });
    });
</script>
@endsection
