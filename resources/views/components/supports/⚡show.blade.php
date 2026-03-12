<?php

use Livewire\Component;
use App\Models\EducationalResource;

new class extends Component
{
    public EducationalResource $resource;
    public $relatedResources = [];

    public function mount(EducationalResource $resource)
    {
        $this->resource = $resource->load(['category', 'uploader', 'likes']);

        $this->relatedResources = EducationalResource::where('category_id', $resource->category_id)
            ->where('level_id', $resource->level_id)
            ->where('id', '!=', $resource->id)
            ->where('is_approved', 1)
            ->popular(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.supports.show');
    }
};
?>
