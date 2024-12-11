@extends('layout.default_layout')
@section('content')
    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg-10">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                            </div>
                            <form class="user" method="POST" action="{{ route('register') }}"> @csrf
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input name="name" type="text" class="form-control form-control-user"
                                            id="exampleFirstName" placeholder="Name">
                                    </div>
                                    <div class="col-sm-6">
                                        <input name="email" type="email" class="form-control form-control-user"
                                            id="exampleLastName" placeholder="email">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input name="password" type="password" class="form-control form-control-user"
                                            id="exampleInputPassword" placeholder="Password">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user"
                                            id="exampleRepeatPassword" placeholder="Repeat Password"
                                            name="password_confirmation" required autocomplete="new-password">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <label>Role</label>
                                        <select name="role" class="form-control" id="clientSelect">
                                            <option value="-1">Select Role</option>
                                            <option value="lawyer">Lawyer</option>
                                            <option value="client">Client</option>
                                            <option value="clerk">Clerk</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <button id="add-customer-btn"
                                        class="btn btn-primary btn-user btn-block">Register</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
