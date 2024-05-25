<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Shopping List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-[#94a3b8]">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <div class="container mx-auto my-10">
        <h1 class="text-center text-3xl font-semibold text-white mb-4">
            Shopping List
        </h1>
        
        <div class="md:w-1/2 mx-auto">
            <div class="bg-white shadow-md rounded-lg p-6">
                <form method="POST" action="{{ route('lists.store') }}" id="shop-form">
                    @csrf
                    @method('post')
                    <div class="flex">
                        <input type="text"class="w-full px-4 py-2 mr-2 rounded-lg border-gray-300 focus:outline-none focus:border-blue-500" id="item" name="item" placeholder="Add new item" value="{{ old('item') }}" required>
                        <input type="number"class="w-25 px-4 py-2 mr-2 rounded-lg border-gray-300 focus:outline-none focus:border-blue-500" id="quantity" name="quantity" placeholder="Add Quantity" value="{{ old('quantity') }}" required>
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">Add</button>
                    </div>
                </form>
                
                

            <div class="relative overflow-x-auto mt-3">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-center">
                                Item Name
                            </th>
                            <th scope="col" class="px-6 py-3 text-center">
                                Quantity
                            </th>
                            <th scope="col" class="px-6 py-3 text-center">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-center">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($BuyLists as $BuyList)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $BuyList->item }}
                                </th>
                                <td class="px-6 py-4 text-center">
                                    {{ $BuyList->quantity }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($BuyList->status == 'Bought')
                                        <span id="display_status-{{ $BuyList->id }}" class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Bought</span>
                                    @elseif($BuyList->status == 'Next')
                                        <span id="display_status-{{ $BuyList->id }}" class="bg-yellow-100 text-yellow-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">Next</span>
                                    @else
                                        <span id="display_status-{{ $BuyList->id }}" class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Not Found</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div>
                                        <select id="status-{{ $BuyList->id }}" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option disabled>Choose status</option>
                                            @foreach (['Next', 'Bought', 'Not Found'] as $status)
                                                <option value="{{ $status }}" {{ $status == $BuyList->status ? 'selected' : '' }}>{{ $status }}</option>                                        
                                            @endforeach
                                        </select>
                                        <div class=" flex justify-around mt-2">
                                            <button data-modal-target="crud-modal-{{ $BuyList->id }}" data-modal-toggle="crud-modal-{{ $BuyList->id }}" class="text-blue-500 hover:text-blue-700 edit-btn">Edit</button>
                                            <form action="{{ route('lists.destroy', $BuyList->id) }}" method="post" style="display: inline">
                                                @csrf
                                                @method('delete')
                                                <button class="text-red-500 hover:text-red-700 mr-2 delete-btn" type="submit">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            
                        @endforelse
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>


@forelse ($BuyLists as $BuyList)
    <!-- Main modal -->
    <div id="crud-modal-{{ $BuyList->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden shadow-md fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Edit Item List
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="crud-modal-{{ $BuyList->id }}">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form action="{{ route('lists.update', $BuyList->id) }}" method="POST" class="p-4 md:p-5">
                    @csrf
                    @method('put')
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Item</label>
                            <input type="text" name="item" id="item" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="{{ $BuyList->item }}" value="{{ $BuyList->item }}">
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label for="price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Quantity</label>
                            <input type="number" name="quantity" id="quantity" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="{{ $BuyList->quantity }}" value="{{ $BuyList->quantity }}">
                        </div>
                        
                    </div>
                    <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Edit Item
                    </button>
                </form>
            </div>
        </div>
    </div> 
@empty
    
@endforelse


    @if (session('success-store'))
        <script>
            toastr.success("{{ session('success-store') }}")
        </script>
    @endif
    @if (session('success'))
        <script>
            toastr.success("{{ session('success') }}")
        </script>
    @endif
    
    {{-- <script>
        $(document).ready(function(){
            $('#shop-form').on('submit', function(e){
                e.preventDefault(); // Mencegah perilaku default form

                // Mengambil nilai dari input
                var item = $('#item').val();
                var quantity = $('#quantity').val();

                // Mengirim data form secara asinkron menggunakan AJAX
                $.ajax({
                    url: '{{ route('lists.store') }}',
                    type: 'POST',
                    data: {
                        item: item,
                        quantity: quantity,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response){
                        // Handle respon dari server (opsional)
                        toastr.success('Success add item')
                        window.reload();
                        console.log(response);
                    },
                    error: function(xhr){
                        // Handle error (opsional)
                        toastr.error('Failed to add item :(')
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script> --}}
    <script>
        document.querySelectorAll('select[name="status"]').forEach(select => {
        select.addEventListener('change', async (e) => {
        const id = e.target.id.split('-')[1]; // Ekstrak ID dari elemen select
        const status = e.target.value;
        let classList;
        if (status === 'Next') {
            classList = 'bg-yellow-100 text-yellow-800';
        } else if (status === 'Not Found') {
            classList = 'bg-red-100 text-red-800';
        } else {
            classList = 'bg-green-100 text-green-800';
        }

        // Temukan dan ubah kelas elemen <span> yang sesuai dengan ID
        const displayStatus = document.getElementById(`display_status-${id}`);
        // console.log(displayStatus);
        displayStatus.className = ` ${classList} text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:${classList.split(' ')[0]}-900 dark:${classList.split(' ')[1]}-300`;
        displayStatus.innerText = status;
        try {
            const response = await fetch(`/lists/status/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status })
            });
            if (response.ok) {
                console.log(id);
                const displayStatus = document.getElementById(`display_status-${id}`);
                
                toastr.success('Berhasil merubah status!!!');
            } else {
                console.log("Failed to update status");
                toastr.error('Gagal merubah status!!!');
            }
        } catch (error) {
            console.log(error);
        }
    });
});

    </script>
    {{-- <script>
        // Function to add a new task
        function addTask(task) {
            const todoList = document.getElementById("todo-list");
            const li = document.createElement("li");
            li.className = 
              "border-b border-gray-200 flex items-center justify-between py-4";
            li.innerHTML = `
                <label class="flex items-center">
                    <input type="checkbox" class="mr-2">
                    <span>${task}</span>
                </label>
                <div>
                    <button class="text-red-500 hover:text-red-700
                     mr-2 delete-btn">Delete</button>
                    <button class="text-blue-500
                     hover:text-blue-700 edit-btn">Edit</button>
                </div>
            `;
            todoList.appendChild(li);
 
            // Add event listener to the checkbox
            const checkbox = li.querySelector('input[type="checkbox"]');
            checkbox.addEventListener('change', function () {
                const taskText = this.nextElementSibling;
                if (this.checked) {
                    taskText.classList.add('completed');
                } else {
                    taskText.classList.remove('completed');
                }
            });
        }
 
        // Event listener for form submission
        document.getElementById("todo-form").addEventListener("submit",
            function (event) {
                event.preventDefault();
                const taskInput = document.getElementById("todo-input");
                const task = taskInput.value.trim();
                if (task !== "") {
                    addTask(task);
                    taskInput.value = "";
                }
            });
 
        // Event listener for delete button click
        document.getElementById("todo-list")
          .addEventListener("click",
            function (event) {
                if (event.target.classList.contains("delete-btn")) {
                    event.target.parentElement.parentElement.remove();
                }
            });
 
        // Event listener for edit button click
        document.getElementById("todo-list")
          .addEventListener("click",
            function (event) {
                if (event.target.classList.contains("edit-btn")) {
                    const taskText = event.target.
                        parentElement.parentElement.querySelector("span");
                    const newText = 
                          prompt("Enter new task", taskText.textContent);
                    if (newText !== null) {
                        taskText.textContent = newText.trim();
                    }
                }
            });
 
        // Add default tasks
        const defaultTasks = ["HTML", "CSS", "JS", "Bootstrap"];
        defaultTasks.forEach(task => addTask(task));
    </script> --}}
</body>
</html>