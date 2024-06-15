<x-app-layout>
    <form action="{{ route('sales.export') }}" method="GET">
        @csrf
        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date" required>
        <label for="end_date">End Date:</label>
        <input type="date" id="end_date" name="end_date" required>
        <button type="submit">Export to Excel</button>
    </form>    
</x-app-layout>