<x-form-modal
    :title="isset($askedQuestion) ? 'Update asked question':'Add new asked question'"
    action="{{ isset($askedQuestion) ? route('frontend.asked-question.update', $askedQuestion->id) : route('frontend.asked-question.store')}}"
    :button="isset($askedQuestion) ? 'Update':'Submit'"
    id="fileForm"
    size="lg"
>
    @isset($askedQuestion)
        @method('PUT')
    @endisset

    <x-form-group
        name="question"
        placeholder="Enter question..."
        :value="$askedQuestion->question ?? ''"
    />

    <x-form-group
        name="answer"
        isType="textarea"
        placeholder="Write answer..."
        rows="5"
    >
        {{ $askedQuestion->answer ?? '' }}
    </x-form-group>

</x-form-modal>
