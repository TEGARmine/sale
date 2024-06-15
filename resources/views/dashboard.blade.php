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

    <h1 class="text-center mb-10 font-semibold text-3xl">Management Sales</h1>
    <div>
        <a href="{{ route('sales.input') }}">
            <button type="button" class="text-white bg-gray-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Input Sales</button>
        </a>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            User
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Tanggal Transaksi
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Jenis Transaksi
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Nominal Transaksi
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sales as $item)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $item->user->name }}
                            </th>
                            <td class="px-6 py-4">
                                {{ \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $item->kategori->nama }}
                            </td>
                            <td class="px-6 py-4">
                                {{ 'Rp ' . number_format($item->nominal, 0, ',', '.') }}
                            </td>
                            @if (auth()->id() == $item->user->id)
                                <td class="px-6 py-4 flex items-center gap-3">
                                    <a href="{{ route('sales.edit', $item->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                    <form action="{{ route('sales.delete', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline" onclick="return confirm('Kamu yakin ingin menghapus ini?');">Delete</button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>