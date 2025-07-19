@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="py-70">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    @php
                        echo @$policy->data_values->details;
                    @endphp
                </div>
            </div>
        </div>
    </section>
@endsection
