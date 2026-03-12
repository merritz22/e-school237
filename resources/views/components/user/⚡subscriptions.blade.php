<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Subscription;

new class extends Component
{
    use WithPagination;

    // public $subscriptions = null;
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $statusColors = [
            'pending'   => 'yellow',
            'active'    => 'green',
            'expired'   => 'red',
            'cancelled' => 'gray',
        ];

    public function sort($column) {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function getSubscriptionsProperty()
    {
        return Subscription::query()
            ->with('level')
            ->when($this->sortBy, fn($query) => $query->orderBy($this->sortBy, $this->sortDirection))
            ->paginate(10);
    }

};
?>

<flux:card class="mt-5">
    <flux:heading size="lg">Liste des abonnements</flux:heading>
    <flux:table :paginate="$this->subscriptions">

        <flux:table.columns>
            <flux:table.column 
                sortable 
                :sorted="$sortBy === 'created_at'" 
                :direction="$sortBy === 'created_at' ? $sortDirection : null" 
                wire:click="sort('created_at')"
            >Date</flux:table.column>

            <flux:table.column 
                sortable 
                :sorted="$sortBy === 'amount'" 
                :direction="$sortBy === 'amount' ? $sortDirection : null" 
                wire:click="sort('amount')"
            >Montant</flux:table.column>
            <flux:table.column>Classe</flux:table.column>
            <flux:table.column>Statut</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($this->subscriptions as $order)
                <flux:table.row :key="$order->id">
                    <flux:table.cell variant="strong">{{ $order->created_at->format('d/m/Y') }}</flux:table.cell>
                    <flux:table.cell variant="strong">{{ $order->amount }} XAF</flux:table.cell>
                    <flux:table.cell variant="strong">{{ $order->level->name }}</flux:table.cell>
                    <flux:table.cell variant="strong" class="flex items-center gap-3">
                        <flux:badge size="sm" :color="$statusColors[$order->status]">{{ ucfirst($order->status) }}</flux:badge>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
</flux:card>