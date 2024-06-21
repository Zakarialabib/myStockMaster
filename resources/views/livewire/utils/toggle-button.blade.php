<div>
    <x-toggle-switch name="status" wire:model="status" 
                    wire:key="status-{{ $model->id }}" 
                    class="text-white" id="{{$uniqueId}}" 
                    checked="{{$status}}"/>
</div>

  