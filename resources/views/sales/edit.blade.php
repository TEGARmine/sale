<x-app-layout>
    <h1 class="text-center mb-10 font-semibold text-3xl">Edit Sales</h1>
    <form id="currency-form" class="max-w-sm mx-auto" method="POST" action="{{ route('sales.update', $sales->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-5">
            <label for="tanggal_transaksi" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal</label>
            <input value="{{ $sales->tanggal_transaksi }}" type="date" id="tanggal_transaksi" name="tanggal_transaksi" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@flowbite.com" required />
        </div>
        <div class="mb-5">
            <label for="kategori_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kategori Sales</label>
            <select id="kategori_id" name="kategori_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option selected>pilih kategori</option>
                @foreach ($kategoris as $kategori)
                    <option value="{{ $kategori->id }}" {{ $kategori->id == $sales->kategori_id ? 'selected' : '' }}>{{ $kategori->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-5">
            <label for="nominal" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nominal Transaksi</label>
            <input value="{{ $sales->nominal }}" type="text" id="nominal" name="nominal" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Rp" type-currency="IDR" required />
        </div>
        <div class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            <button type="submit" class="w-full">Edit</button>
        </div>
    </form>
    <script>
        document.querySelectorAll('input[type-currency="IDR"]').forEach((element) => {
            element.addEventListener('keyup', function(e) {
                let cursorPosition = this.selectionStart;
                let value = parseInt(this.value.replace(/[^,\d]/g, ''));
                let originalLength = this.value.length;
                if (isNaN(value)) {
                    this.value = "";
                } else {
                    this.value = value.toLocaleString('id-ID', {
                        currency: 'IDR',
                        style: 'currency',
                        minimumFractionDigits: 0
                    });
                    cursorPosition = this.value.length - originalLength + cursorPosition;
                    this.setSelectionRange(cursorPosition, cursorPosition);
                }
            });
        });
    
        document.querySelector('#currency-form').addEventListener('submit', function(e) {
            document.querySelectorAll('input[type-currency="IDR"]').forEach((element) => {
                let value = parseInt(element.value.replace(/[^,\d]/g, ''));
                element.value = value;
            });
        });
    </script>    
</x-app-layout>