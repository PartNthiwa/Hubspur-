<x-admin::layouts>
    <!-- Title of the page -->
    <x-slot:title>
        Shareholders Management
    </x-slot>
    

    <!-- Page Content -->
    <div class="page-content">
        <h1>Hello, Welcome {{ auth()->user()->name ?? 'Guest' }}</h1>
    </div>
</x-admin::layouts>
