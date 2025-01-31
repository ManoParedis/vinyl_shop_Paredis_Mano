@extends('layouts.template')

@section('title', 'Shop')


@section('main')
    <h1>Shop - alternative listing</h1>
    @foreach($genres as $genre)
        <h2>{{ $genre->name }}</h2>
        <ul>
            @foreach($genre->records as $record)
                <li><a href="/shop/{{ $record->id }}">{{ $record->artist }} - {{$record->title}}</a> | Price: € {{ number_format($record->price,2) }} | stock: {{ $record->stock }}</li>
            @endforeach
        </ul>
    @endforeach
@endsection