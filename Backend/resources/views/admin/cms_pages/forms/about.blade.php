@php
    $content = $cmsPage->content ?? [];
    $inputClass = "mt-1 block w-full border border-gray-200 rounded-md px-3 py-2 text-sm focus:border-primary focus:ring focus:ring-primary dark:bg-black/20 dark:border-white/10 dark:text-white";
    $labelClass = "block text-sm font-medium text-gray-700 dark:text-white mb-2";
@endphp

{{-- Hero Section --}}
<div class="box border border-gray-200 dark:border-white/10 mb-4">
    <div class="box-header bg-gray-50 dark:bg-black/20">
        <div class="box-title">Hero Section</div>
    </div>
    <div class="box-body">
        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12 md:col-span-4">
                <label class="{{ $labelClass }}">Badge Text</label>
                <input type="text" class="{{ $inputClass }}" name="content[hero][badge]"
                    value="{{ $content['hero']['badge'] ?? '' }}">
            </div>
            <div class="col-span-12 md:col-span-4">
                <label class="{{ $labelClass }}">Hero Title</label>
                <input type="text" class="{{ $inputClass }}" name="content[hero][title]"
                    value="{{ $content['hero']['title'] ?? '' }}">
            </div>
            <div class="col-span-12">
                <label class="{{ $labelClass }}">Hero Description</label>
                <textarea class="{{ $inputClass }}" name="content[hero][description]"
                    rows="3">{{ $content['hero']['description'] ?? '' }}</textarea>
            </div>
        </div>
    </div>
</div>

{{-- Director Message --}}
<div class="box border border-gray-200 dark:border-white/10 mb-4">
    <div class="box-header bg-gray-50 dark:bg-black/20">
        <div class="box-title">Director's Message</div>
    </div>
    <div class="box-body">
        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12 md:col-span-6">
                <label class="{{ $labelClass }}">Director Content Title</label>
                <input type="text" class="{{ $inputClass }}" name="content[director_message][title]"
                    value="{{ $content['director_message']['title'] ?? '' }}">
            </div>
            <div class="col-span-12 md:col-span-6">
                <label class="{{ $labelClass }}">Director Name</label>
                <input type="text" class="{{ $inputClass }}" name="content[director_message][name]"
                    value="{{ $content['director_message']['name'] ?? '' }}">
            </div>
            <div class="col-span-12 md:col-span-6">
                <label class="{{ $labelClass }}">Director Role</label>
                <input type="text" class="{{ $inputClass }}" name="content[director_message][role]"
                    value="{{ $content['director_message']['role'] ?? '' }}">
            </div>
            <div class="col-span-12 md:col-span-6">
                <label class="{{ $labelClass }}">Director Image</label>
                <input type="file"
                    class="{{ $inputClass }} file:mr-4 file:py-2 file:px-4 file:rounded-sm file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-focus"
                    name="director_image">
                @if(!empty($content['director_message']['image']))
                    <div class="mt-2 text-xs text-gray-500">
                        Current Image: <a href="{{ $content['director_message']['image'] }}" target="_blank"
                            class="text-primary hover:underline">View</a>
                        <input type="hidden" name="content[director_message][image]"
                            value="{{ $content['director_message']['image'] }}">
                    </div>
                @endif
            </div>

            <div class="col-span-12">
                <label class="{{ $labelClass }}">Message Paragraphs (One per line)</label>
                <textarea class="{{ $inputClass }}" name="director_paragraphs_raw"
                    rows="6">{{ implode("\n\n", $content['director_message']['paragraphs'] ?? []) }}</textarea>
                <small class="text-xs text-gray-500 dark:text-white/70">Paragraphs are separated by double newlines by
                    default.</small>
            </div>

            <div class="col-span-12 md:col-span-4">
                <label class="{{ $labelClass }}">Closing Text</label>
                <input type="text" class="{{ $inputClass }}" name="content[director_message][closing][text]"
                    value="{{ $content['director_message']['closing']['text'] ?? '' }}">
            </div>
            <div class="col-span-12 md:col-span-4">
                <label class="{{ $labelClass }}">Closing Name</label>
                <input type="text" class="{{ $inputClass }}" name="content[director_message][closing][name]"
                    value="{{ $content['director_message']['closing']['name'] ?? '' }}">
            </div>
            <div class="col-span-12 md:col-span-4">
                <label class="{{ $labelClass }}">Closing Position</label>
                <input type="text" class="{{ $inputClass }}" name="content[director_message][closing][position]"
                    value="{{ $content['director_message']['closing']['position'] ?? '' }}">
            </div>
        </div>
    </div>
</div>

{{-- CEO Message --}}
<div class="box border border-gray-200 dark:border-white/10 mb-4">
    <div class="box-header bg-gray-50 dark:bg-black/20">
        <div class="box-title">CEO's Message</div>
    </div>
    <div class="box-body">
        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12 md:col-span-6">
                <label class="{{ $labelClass }}">CEO Content Title</label>
                <input type="text" class="{{ $inputClass }}" name="content[ceo_message][title]"
                    value="{{ $content['ceo_message']['title'] ?? '' }}">
            </div>
            <div class="col-span-12 md:col-span-6">
                <label class="{{ $labelClass }}">CEO Name</label>
                <input type="text" class="{{ $inputClass }}" name="content[ceo_message][name]"
                    value="{{ $content['ceo_message']['name'] ?? '' }}">
            </div>
            <div class="col-span-12 md:col-span-6">
                <label class="{{ $labelClass }}">CEO Role</label>
                <input type="text" class="{{ $inputClass }}" name="content[ceo_message][role]"
                    value="{{ $content['ceo_message']['role'] ?? '' }}">
            </div>
            <div class="col-span-12 md:col-span-6">
                <label class="{{ $labelClass }}">CEO Image</label>
                <input type="file"
                    class="{{ $inputClass }} file:mr-4 file:py-2 file:px-4 file:rounded-sm file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-focus"
                    name="ceo_image">
                @if(!empty($content['ceo_message']['image']))
                    <div class="mt-2 text-xs text-gray-500">
                        Current Image: <a href="{{ $content['ceo_message']['image'] }}" target="_blank"
                            class="text-primary hover:underline">View</a>
                        <input type="hidden" name="content[ceo_message][image]"
                            value="{{ $content['ceo_message']['image'] }}">
                    </div>
                @endif
            </div>

            <div class="col-span-12">
                <label class="{{ $labelClass }}">Message Paragraphs (One per line)</label>
                <textarea class="{{ $inputClass }}" name="ceo_paragraphs_raw"
                    rows="6">{{ implode("\n\n", $content['ceo_message']['paragraphs'] ?? []) }}</textarea>
            </div>

            <div class="col-span-12 md:col-span-4">
                <label class="{{ $labelClass }}">Closing Text</label>
                <input type="text" class="{{ $inputClass }}" name="content[ceo_message][closing][text]"
                    value="{{ $content['ceo_message']['closing']['text'] ?? '' }}">
            </div>
            <div class="col-span-12 md:col-span-4">
                <label class="{{ $labelClass }}">Closing Name</label>
                <input type="text" class="{{ $inputClass }}" name="content[ceo_message][closing][name]"
                    value="{{ $content['ceo_message']['closing']['name'] ?? '' }}">
            </div>
            <div class="col-span-12 md:col-span-4">
                <label class="{{ $labelClass }}">Closing Position</label>
                <input type="text" class="{{ $inputClass }}" name="content[ceo_message][closing][position]"
                    value="{{ $content['ceo_message']['closing']['position'] ?? '' }}">
            </div>
        </div>
    </div>
</div>

{{-- Team Section --}}
<div class="box border border-gray-200 dark:border-white/10 mb-4">
    <div class="box-header bg-gray-50 dark:bg-black/20">
        <div class="box-title">Team Section</div>
    </div>
    <div class="box-body">
        <div class="grid grid-cols-12 gap-6 mb-3">
            <div class="col-span-12 md:col-span-6">
                <label class="{{ $labelClass }}">Team Badge</label>
                <input type="text" class="{{ $inputClass }}" name="content[team][badge]"
                    value="{{ $content['team']['badge'] ?? '' }}">
            </div>
            <div class="col-span-12 md:col-span-6">
                <label class="{{ $labelClass }}">Team Title</label>
                <input type="text" class="{{ $inputClass }}" name="content[team][title]"
                    value="{{ $content['team']['title'] ?? '' }}">
            </div>
        </div>

        <h6 class="font-bold mb-3 text-gray-800 dark:text-white">Members</h6>
        <div id="team-members">
            @foreach($content['team']['members'] ?? [] as $index => $member)
                <div class="border border-gray-200 dark:border-white/10 p-4 mb-3 rounded-sm relative">
                    <div class="grid grid-cols-12 gap-6">
                        <div class="col-span-12 md:col-span-3">
                            <label class="{{ $labelClass }}">Name</label>
                            <input type="text" class="{{ $inputClass }}" name="content[team][members][{{$index}}][name]"
                                value="{{ $member['name'] }}">
                        </div>
                        <div class="col-span-12 md:col-span-3">
                            <label class="{{ $labelClass }}">Role</label>
                            <input type="text" class="{{ $inputClass }}" name="content[team][members][{{$index}}][role]"
                                value="{{ $member['role'] }}">
                        </div>
                        <div class="col-span-12 md:col-span-3">
                            <label class="{{ $labelClass }}">Image</label>
                            <input type="file" class="{{ $inputClass }}" name="team_members_{{$index}}_image">
                            <input type="hidden" name="content[team][members][{{$index}}][image]"
                                value="{{ $member['image'] }}">
                            @if($member['image'])
                                <div class="mt-1 text-xs"><a href="{{ $member['image'] }}" class="text-primary"
                                        target="_blank">Current</a></div>
                            @endif
                        </div>
                        <div class="col-span-12">
                            <label class="{{ $labelClass }}">Description</label>
                            <input type="text" class="{{ $inputClass }}" name="content[team][members][{{$index}}][desc]"
                                value="{{ $member['desc'] }}">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>