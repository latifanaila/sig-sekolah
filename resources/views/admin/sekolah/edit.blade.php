@extends('layouts.app')
@section('title', 'Edit Sekolah')

@section('content')
    <h1 class="mb-4 text-2xl font-bold">Edit Sekolah</h1>
     @include('sekolah._tab', ['sekolah' => $sekolah])
    <form method="POST" action="{{ route('sekolah.update', $sekolah) }}" class="rounded bg-white p-6 shadow">
        @csrf
        @method('PUT')
        @include('admin.sekolah._form')
    </form>
@endsection