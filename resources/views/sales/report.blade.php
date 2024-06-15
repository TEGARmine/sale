<x-app-layout>
    @if(session('success'))
        <div>
            <span id="success-message" class="bg-green-100 mb-4 text-sm text-center text-green-800 font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">
                {{ session('success') }}
            </span>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', (event) => {
                setTimeout(() => {
                    const successMessage = document.getElementById('success-message');
                    if (successMessage) {
                        successMessage.style.display = 'none';
                    }
                }, 3000);
            });
        </script>
    @endif

    <h1 class="text-center mb-10 font-semibold text-3xl">Report Sales</h1>
    <div>
        <div x-data="{ open: false }">
            <!-- Button to open the modal -->
            <button @click="open = true" type="button" class="text-white flex items-center gap-2 bg-gray-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>                  
                Report Sales
            </button>
        
            <!-- Modal -->
            <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-[999]">
                <div @click.away="open = false" class="bg-white rounded-lg shadow-lg p-6 w-96">
                    <h2 class="text-xl font-bold mb-4">Export Sales Report</h2>
                    <form action="{{ route('sales.export') }}" method="GET">
                        @csrf
                        <div class="mb-4">
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date:</label>
                            <input type="date" id="start_date" name="start_date" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div class="mb-4">
                            <label for="end_date" class="block text-sm font-medium text-gray-700">End Date:</label>
                            <input type="date" id="end_date" name="end_date" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div class="flex justify-end">
                            <button type="button" @click="open = false" class="bg-gray-300 text-gray-700 rounded-lg px-4 py-2 mr-2">Cancel</button>
                            <button type="submit" @click="open = false" class="bg-blue-600 text-white rounded-lg px-4 py-2">Export to Excel</button>
                        </div>
                    </form>
                </div>
                <script>
                    document.getElementById('start_date').addEventListener('change', function() {
                      var startDate = this.value;
                      var endDateInput = document.getElementById('end_date');
                      endDateInput.min = startDate;
                      if (endDateInput.value < startDate) {
                        endDateInput.value = startDate;
                      }
                    });
                </script>
            </div>
        </div>        
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr class="capitalize">
                        <th scope="col" class="px-6 py-3">
                            User
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Jumlah hari kerja
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Jumlah transaksi barang
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Jumlah transaksi jasa
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Nominal transaksi barang
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Nominal transaksi jasa
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $item['user_name'] }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $item['total_hari_kerja'] }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $item['jumlah_transaksi_barang'] }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $item['jumlah_transaksi_jasa'] }}
                            </td>
                            <td class="px-6 py-4">
                                {{ 'Rp ' . number_format($item['nominal_transaksi_barang'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                {{ 'Rp ' . number_format($item['nominal_transaksi_jasa'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>