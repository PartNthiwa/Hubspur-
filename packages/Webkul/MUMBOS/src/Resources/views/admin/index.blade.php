<x-admin::layouts>
    <!-- Title of the page -->
    <x-slot:title>
        Shareholders Management
    </x-slot>
    <!-- Breadcrumbs -->
    <!-- Page Content -->
    <div class="page-content">
        <h1>Hello, Welcome {{ auth()->user()->name ?? 'Guest' }}</h1>
         <p>Welcome to the shareholders section for viewing share contributions.</p>
    </div>
</x-admin::layouts>
