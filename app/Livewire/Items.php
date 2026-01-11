<?php

namespace App\Livewire;

use App\Models\Item;
use Livewire\Component;
use Livewire\WithPagination;

class Items extends Component
{
    use WithPagination;

    public $active;
    public $q;

    public function render()
    {
        $items = Item::where('user_id', auth()->user()->id)
            ->when($this->q, function($query) {
                return $query
                    ->where('name', 'like', '%'.$this->q.'%')
                    ->orwhere('price', 'like', '%'.$this->q.'%');
            })
            ->when($this->active, function($query) {
                //return $query->where('status', 1);
                return $query->active();
            });
        $query = $items->toSql();
        $items = $items->paginate(10);
        return view('livewire.items', compact('items', 'query'));
        // ['items' => $items, 'query' => $query]
    }

    // 빈페이지 노출 방지
    public function updatingActive() {
        $this->resetPage();
    }
    public function updatingQ() {
        $this->resetPage();
    }
}
