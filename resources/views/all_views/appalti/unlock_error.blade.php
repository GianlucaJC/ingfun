@extends('all_views.viewmaster.index')

@section('title', 'Errore di Sblocco')

@section('content_main')
<div class="container" style="margin-top: 50px;">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="alert alert-danger text-center">
                <h4><i class="fas fa-exclamation-triangle"></i> Errore!</h4>
                <p>{{ $message ?? 'Si è verificato un errore imprevisto.' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
