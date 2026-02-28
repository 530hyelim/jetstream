<?php

namespace App\Livewire;

use App\Models\Item;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Items extends Component
{
    use WithPagination;

    public $active;
    public $q = '';
    public $item;

    public $sortBy = 'id';
    public $sortAsc = true;
    
    public $confirmingItemDeletion = false;
    public $confirmingItemAdd = false;

    // 모달 내 동작 체크박스용 (배열 바인딩 대신 단일 프로퍼티 사용)
    public $itemStatus = false;

    protected $queryString = [
        'active' => ['except' => false],
        'q' => ['except' => ''],
        'sortBy' => ['except' => 'id'],
        'sortAsc' => ['except' => true]
    ];

    protected $rules = [
        'item.name' => 'required|string|min:3',
        'item.price' => 'required|numeric|between:1,100',
        'itemStatus' => 'boolean',
    ];

    public function render()
    {
        $items = Item::where('user_id', Auth::user()->id)
            ->when($this->q, function($query) {
                return $query
                    ->where('name', 'like', '%'.$this->q.'%')
                    ->orwhere('price', 'like', '%'.$this->q.'%');
            })
            ->when($this->active, function($query) {
                //return $query->where('status', 1);
                return $query->active();
            })
            ->orderBy($this->sortBy, $this->sortAsc ? 'ASC':'DESC');

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

    public function sortBy($field) {
        if ($field == $this->sortBy) {
            $this->sortAsc = !$this->sortAsc;
        } else {
            $this->sortAsc = true;
        }
        $this->sortBy = $field;
        $this->resetPage();
    }
    public function sortById() { $this->sortBy('id'); }
    public function sortByName() { $this->sortBy('name'); }
    public function sortByPrice() { $this->sortBy('price'); }

    public function confirmItemDeletion($id) {
        $this->confirmingItemDeletion = $id;
    }
    public function deleteItem(Item $item) {
        $item->delete();
        $this->confirmingItemDeletion = false;
        session()->flash('message', '아이템이 성공적으로 삭제되었습니다.');
    }

    public function confirmAdd() {
        $this->item = ['name' => '', 'price' => '', 'status' => 0];
        $this->itemStatus = false;
        $this->confirmingItemAdd = true;
    }
    public function saveItem() {
        $this->validate();
        $status = $this->itemStatus ? 1 : 0;
        if (isset($this->item['id'])) {
            $item = Item::findOrFail($this->item['id']);
            $item->update([
                'name'   => $this->item['name'],
                'price'  => $this->item['price'],
                'status' => $status,
            ]);
            session()->flash('message', '아이템이 성공적으로 수정되었습니다.');
        } else {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $user->items()->create([
                'name' => $this->item['name'],
                'price' => $this->item['price'],
                'status' => $status,
            ]);
            session()->flash('message', '아이템이 성공적으로 등록되었습니다.');
        }
        $this->confirmingItemAdd = false;
    }
    public function confirmItemEdit($id) {
        $item = Item::findOrFail($id);
        $this->item = [
            'id'     => $item->id,
            'name'   => $item->name,
            'price'  => $item->price,
            'status' => (int) ($item->status ?? 0),
        ];
        $this->itemStatus = (bool) ($item->status ?? 0);
        $this->confirmingItemAdd = true;
    }
}
