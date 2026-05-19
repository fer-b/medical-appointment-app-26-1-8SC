<div class="flex items-center gap-2">
    <!-- View / Consultation -->
    <a href="{{ route('admin.orders.fulfill', $order) }}" class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-3 py-2 text-center" title="Atender Pedido">
        <i class="fa-solid fa-clipboard-check"></i>
    </a>
    
    <!-- Edit (Optional placeholder) -->
    <a href="#" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2 text-center">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>

    <!-- Delete -->
    <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="delete-form inline-block">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-2 text-center">
            <i class="fa-solid fa-trash"></i>
        </button>
    </form>
</div>
