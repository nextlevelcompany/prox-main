@extends('tenant.layouts.app')

@section('content')

    <div class="card">
        <div class="card-body">
            <h1>Modulo de Ejemplo</h1>
            <p>
                This view is loaded from module: addonclient
            </p>
            <div class="p-1">
                <ul class="">
                    <li>
                        Comando para crear un nuevo modulo <br>
                        <pre class="bg-light">php artisan module:make AddonClient</pre>
                    </li>
                    <li>
                        crear archivo para menu en resources\views\tenant\layouts\partials\addons_menu, usar de ejemplo el archivo example.blade.php <br>
                        <pre class="bg-light">addonclient.blade.php</pre>
                    </li>
                    <li>estará disponible una vista en blanco y una ruta mediante /addonclient</li>
                </ul>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Número</th>
                            <th>Nombre</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($persons as $person)
                        <tr>
                            <td>{{ $persons->firstItem() + $loop->index }}</td>
                            <td>{{ $person->number }}</td>
                            <td>{{ $person->name }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="row float-end">
                    <div class="col-md-12 col-lg-12">
                        {{ $persons->links('tenant.layouts.partials.pagination') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection