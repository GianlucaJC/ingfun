@extends('all_views.viewmaster.index')

@section('title', 'Appalto Sbloccato')

@section('content_main')
<div class="container" style="margin-top: 50px;">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="alert alert-success text-center">
                <h4><i class="fas fa-check-circle"></i> Appalto sbloccato con successo!</h4>
                <p>Ora è possibile modificare nuovamente il box. Puoi chiudere questa pagina.</p>
            </div>
        </div>
    </div>
</div>
@endsection
