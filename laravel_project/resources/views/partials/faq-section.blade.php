{{-- FAQ section --}}
@if(isset($servicesFaqs) && $servicesFaqs && $servicesFaqs->count() > 0)
<section class="section-bg">
    <h2 class="section-title">Часто задаваемые вопросы</h2>
    <p class="text-center text-gray-600 mb-8 max-w-3xl mx-auto">
        Ответы на популярные вопросы о наших услугах.
        Не нашли ответ? Свяжитесь с нами для консультации.!
    </p>

    <div class="max-w-3xl mx-auto">
        @foreach($servicesFaqs as $faq)
            <div class="mb-4">
                <details class="group">
                    <summary class="flex items-center justify-between w-full p-4 bg-white rounded-md shadow-sm cursor-pointer hover:bg-gray-50">
                        <span class="font-medium text-gray-800">{{ $faq->question }}</span>
                        <i class="material-icons text-gray-400 group-open:rotate-180 transition-transform">expand_more</i>
                    </summary>
                    <div class="p-4 bg-gray-50 rounded-b-md shadow-sm">
                        <p class="text-gray-600">{{ $faq->answer }}</p>
                    </div>
                </details>
            </div>
        @endforeach
    </div>
</section>
@elseif(isset($homepageFaqs) && $homepageFaqs && $homepageFaqs->count() > 0)
<section class="section-bg">
    <h2 class="section-title">Часто задаваемые вопросы</h2>
    <p class="text-center text-gray-600 mb-8 max-w-3xl mx-auto">
        Ответы на популярные вопросы о SEO-продвижении и наших услугах.
        Не нашли ответ на свой вопрос? Свяжитесь с нами!
    </p>

    <div class="max-w-3xl mx-auto">
        @foreach($homepageFaqs as $faq)
            <div class="mb-4">
                <details class="group">
                    <summary class="flex items-center justify-between w-full p-4 bg-white rounded-md shadow-sm cursor-pointer hover:bg-gray-50">
                        <span class="font-medium text-gray-800">{{ $faq->question }}</span>
                        <i class="material-icons text-gray-400 group-open:rotate-180 transition-transform">expand_more</i>
                    </summary>
                    <div class="p-4 bg-gray-50 rounded-b-md shadow-sm">
                        <p class="text-gray-600">{{ $faq->answer }}</p>
                    </div>
                </details>
            </div>
        @endforeach
    </div>
</section>
@endif
{{-- End FAQ --}}
