<?php

namespace App\Services;

use App\Models\Faq;
use Illuminate\Database\Eloquent\Collection;

class FaqService
{
    /**
     * Get all homepage FAQs.
     */
    public function getHomepageFaqs(): Collection
    {
        return Faq::visibleOnHomepage()->ordered()->get();
    }
}
