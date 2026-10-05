{{-- FAQ section --}}
@if (isset($servicesFaqs) && $servicesFaqs && $servicesFaqs->count() > 0)
    <section class="section-bg">
        <h2 class="section-title">Часто задаваемые вопросы</h2>
        <p class="section-subtitle">
            Ответы на популярные вопросы о наших услугах.
            Не нашли ответ? Свяжитесь с нами для консультации!
        </p>

        <div class="max-w-3xl mx-auto">
            @foreach ($servicesFaqs as $faq)
                <div class="mb-4">
                    <details class="group">
                        <summary
                            class="faq-summary flex w-full cursor-pointer items-center justify-between bg-white p-4 shadow-sm hover:bg-gray-50">
                            <span class="min-w-0">{{ $faq->question }}</span>
                            <i
                                class="material-icons shrink-0 text-gray-400 transition-transform group-open:rotate-180">expand_more</i>
                        </summary>
                        <div class="p-4 bg-gray-50 shadow-sm">
                            <p>{{ $faq->answer }}</p>
                        </div>
                    </details>
                </div>
            @endforeach
        </div>
    </section>
@elseif(isset($homepageFaqs) && $homepageFaqs && $homepageFaqs->count() > 0)
    @php($faqPage = \App\Support\StructuredData::faqPage($homepageFaqs))
    @if ($faqPage)
        <script type="application/ld+json">
            {!! \App\Support\StructuredData::encode($faqPage) !!}
        </script>
    @endif
    <section class="section-bg">
        <h2 class="section-title">Часто задаваемые вопросы</h2>
        <p class="section-subtitle">
            Ответы на популярные вопросы о SEO-продвижении и наших услугах.
            Не нашли ответ на свой вопрос? Свяжитесь с нами!
        </p>

        <div class="max-w-3xl mx-auto">
            @foreach ($homepageFaqs as $faq)
                <div class="mb-4">
                    <details class="group">
                        <summary
                            class="faq-summary flex w-full cursor-pointer items-center justify-between bg-white p-4 shadow-sm hover:bg-gray-50">
                            <span class="min-w-0">{{ $faq->question }}</span>
                            <i
                                class="material-icons shrink-0 text-gray-400 transition-transform group-open:rotate-180">expand_more</i>
                        </summary>
                        <div class="p-4 bg-gray-50 shadow-sm">
                            <p>{{ $faq->answer }}</p>
                        </div>
                    </details>
                </div>
            @endforeach
        </div>
    </section>
@endif
{{-- End FAQ --}}
