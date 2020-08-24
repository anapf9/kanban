<!-- resources/views/tasks/index.blade.php -->

@extends('layouts.app')

@section('content')
<div class="md:mx-4 relative overflow-hidden">
    <main class="h-full flex flex-col overflow-auto">
        <!-- Our Kanban Vue component will go here --> aki
        <kanban-board :initial-data="{{ $tasks }}"></kanban-board>
        222
    </main>
</div>
@endsection
