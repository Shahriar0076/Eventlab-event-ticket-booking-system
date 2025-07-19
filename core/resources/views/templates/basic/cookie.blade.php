@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="py-70">
        <div class="container">
            @php
                echo @$cookie->data_values->description;
            @endphp
    </section>
@endsection
