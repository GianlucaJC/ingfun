@extends('all_views.viewmaster.index')

@section('title', 'Sblocco Appalto')


@section('content_main')
<div class="container" style="margin-top: 50px;">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><h4>Sblocco Appalto</h4></div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('unlock_appalto.process') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-3">
                            <label for="password" class="form-label">Password di Sblocco</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Sblocca Appalto</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
