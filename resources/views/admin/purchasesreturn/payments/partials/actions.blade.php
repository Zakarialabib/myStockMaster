@can('purchase_return_payments_access')
    <a href="{{ route('purchase-return-payments.edit', [$data->purchaseReturn->id, $data->id]) }}" class="btn btn-info btn-sm">
        <i class="bi bi-pencil"></i>
    </a>
@endcan
@can('purchase_return_payments_access')
    <button id="delete" class="btn btn-danger btn-sm" onclick="
        event.preventDefault();
        if (confirm('Are you sure? It will delete the data permanently!')) {
        document.getElementById('destroy{{ $data->id }}').submit()
        }
        ">
        <i class="fa fa-trash"></i>
        <form id="destroy{{ $data->id }}" class="d-none" action="{{ route('purchase-return-payments.destroy', $data->id) }}" method="POST">
            @csrf
            @method('delete')
        </form>
    </button>
@endcan
