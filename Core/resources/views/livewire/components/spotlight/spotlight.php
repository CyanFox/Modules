<?php

use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public $searchTerm = '';

    public $isOpen = false;

    public $selectedIndex = 0;

    public $results = [];

    public $staticItems = [];

    protected $listeners = [
        'openSpotlight' => 'open',
        'closeSpotlight' => 'close',
    ];

    public function mount()
    {
        $this->loadStaticItems();
    }

    public function updatedSearchTerm()
    {
        if (empty($this->searchTerm)) {
            $this->loadStaticItems();
            $this->selectedIndex = null;
        } else {
            $this->search();
            $this->selectedIndex = 0;
        }
    }

    public function loadStaticItems()
    {
        $spotlightService = app('spotlight');
        $this->staticItems = $spotlightService->getStaticItems();
        $this->results = [];
    }

    public function search()
    {
        if (empty($this->searchTerm)) {
            $this->loadStaticItems();

            return;
        }

        $spotlightService = app('spotlight');
        $this->results = $spotlightService->search($this->searchTerm);
        $this->staticItems = [];
    }

    public function selectNextResult()
    {
        $items = $this->getDisplayItems();
        if (count($items) > 0) {
            if ($this->selectedIndex === null) {
                $this->selectedIndex = 0;
            } else {
                $this->selectedIndex = ($this->selectedIndex + 1) % count($items);
            }
        }
    }

    public function selectPreviousResult()
    {
        $items = $this->getDisplayItems();
        if (count($items) > 0) {
            if ($this->selectedIndex === null) {
                $this->selectedIndex = count($items) - 1;
            } else {
                $this->selectedIndex = ($this->selectedIndex - 1 + count($items)) % count($items);
            }
        }
    }

    public function selectResult($index = null)
    {
        $index = $index ?? $this->selectedIndex;
        $items = $this->getDisplayItems();

        if (isset($items[$index])) {
            $result = $items[$index];
            $this->close();

            $this->redirect($result['url'], true);
        }
    }

    #[On('open-spotlight')]
    public function open()
    {
        $this->isOpen = true;
        $this->searchTerm = '';
        $this->loadStaticItems();
        $this->selectedIndex = null;

        $this->dispatch('spotlight-opened');
    }

    #[On('close-spotlight')]
    public function close()
    {
        $this->isOpen = false;
        $this->dispatch('spotlight-closed');
    }

    public function getDisplayItems()
    {
        $items = empty($this->searchTerm) ? $this->staticItems : $this->results;

        return array_slice($items, 0, 10);
    }

    public function render(): View
    {
        if (empty($this->searchTerm) && $this->isOpen) {
            $this->loadStaticItems();
        }

        return view('core::livewire.components.spotlight.spotlight');
    }
};
