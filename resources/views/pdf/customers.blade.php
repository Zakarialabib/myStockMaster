@section('title', __('Customers'))

@extends('layouts.print')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <table >
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Phone') }}</th>
                            <th>{{ __('City') }}</th>
                            <th>{{ __('Country') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $row)
                            <tr>
                                <td>{{ $row->name }}</td>
                                <td>{{ $row->email }}</td>
                                <td>{{ $row->phone }}</td>
                                <td>{{ $row->city }}</td>
                                <td>{{ $row->country }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="footer">
            <strong>{{ settings()->company_name }}</strong><br>
            @if (settings()->show_address == true)
                {{ settings()->company_address }}<br>
            @endif
            @if (settings()->show_email == true)
                {{ __('Email') }}: {{ settings()->company_email }}<br>
            @endif
            {{ __('Phone') }}:<br> {{ settings()->company_phone }}<br>
        </div>
    </div>
@endsection
