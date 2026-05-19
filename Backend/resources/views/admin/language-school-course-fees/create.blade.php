@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
  <div class="flex justify-between py-10">
    <h2 class="text-2xl font-bold mb-4">Create Language Course Fee</h2>
    <a href="{{ route('admin.language-school-course-fees.index') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">Back to List</a>
  </div>

  @if ($errors->any())
    <div class="mb-4 text-red-700 bg-red-50 border border-red-200 rounded-md px-4 py-2">
      <ul class="list-disc pl-5 space-y-1 text-sm">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('admin.language-school-course-fees.store') }}" method="POST" class="space-y-4">
    @csrf

    <div>
      <label class="block text-sm font-medium text-gray-700">Language Course</label>
      <select name="language_school_course_id" id="language_school_course_id" class="mt-1 block w-full border rounded-md px-3 py-2" required>
        <option value="">Select Course</option>
        @foreach($courses as $course)
          <option value="{{ $course->id }}" @selected(old('language_school_course_id', $selectedCourseId ?? null) == $course->id)>
            {{ $course->name }}
            - {{ optional($course->branch->school)->name ?? 'N/A' }}
            - {{ optional($course->branch->city)->name ?? 'N/A' }}
            @if(($course->fees_count ?? 0) > 0)
              - updated
            @endif
          </option>
        @endforeach
      </select>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Price Split</label>
        <select name="price_split" class="mt-1 block w-full border rounded-md px-3 py-2" required>
          <option value="yes" @selected(old('price_split', 'yes') === 'yes')>Yes</option>
          <option value="no" @selected(old('price_split') === 'no')>No</option>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Valid From</label>
        <input type="date" name="valid_from" value="{{ old('valid_from') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Valid To</label>
        <input type="date" name="valid_to" value="{{ old('valid_to') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
      </div>
    </div>

    <div class="form-group">
      <div class="flex items-center justify-between">
        <label class="block text-sm font-medium text-gray-700">Weeks & Fees</label>
        <button type="button" id="add-week-fee"
          class="ti-btn ti-btn-outline border-green-500 text-green-500 hover:bg-green-500 hover:text-white text-xs px-3 py-1">
          Add New Week
        </button>
      </div>
      <div id="weeks-fees" class="week-grid mt-4 text-xs"></div>
      <p class="text-xs text-gray-500 mt-2">You can add up to 48 weeks. Leave a fee empty to skip that week.</p>
    </div>

    <div class="flex space-x-2">
      <button type="submit" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-success">Save</button>
      <a href="{{ route('admin.language-school-course-fees.index') }}" class="ti-btn rounded-full border">Cancel</a>
    </div>
  </form>
</div>

<style>
  .week-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
    gap: 0.5rem;
  }

  @media (min-width: 768px) {
    .week-grid {
      grid-template-columns: repeat(8, minmax(90px, 1fr));
    }
  }

  .week-card input {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
  }
</style>

<script>
  const MAX_WEEKS = 48;
  const weeksContainer = document.getElementById('weeks-fees');
  const courseSelect = document.getElementById('language_school_course_id');
  const addBtn = document.getElementById('add-week-fee');
  const prefill = @json($prefill ?? []);
  let currentWeeks = [];

  function addWeekRow(weekNumber, feeValue = '') {
    const wrapper = document.createElement('div');
    wrapper.className = 'border rounded-md p-2 bg-gray-50 text-xs flex flex-col week-card';
    wrapper.innerHTML = `
      <label class="block font-semibold text-gray-600 mb-1">Week ${weekNumber}</label>
      <input type="hidden" name="weeks[${weekNumber - 1}][week_number]" value="${weekNumber}">
      <input type="number" step="0.01" min="0" name="weeks[${weekNumber - 1}][fee]" value="${feeValue}"
        class="block w-full border rounded-md px-2 py-1" placeholder="Fee">
    `;
    weeksContainer.appendChild(wrapper);
  }

  function renderWeeks(prefillMap = {}) {
    weeksContainer.innerHTML = '';
    const weeks = Object.keys(prefillMap).map(n => parseInt(n, 10)).sort((a, b) => a - b);
    currentWeeks = weeks.length ? weeks : [1];
    currentWeeks.forEach(week => addWeekRow(week, prefillMap[week] ?? ''));
  }

  function addNextWeek() {
    let next = currentWeeks.length ? Math.max(...currentWeeks) + 1 : 1;
    if (next > MAX_WEEKS) {
      alert('You can only add up to 48 weeks.');
      return;
    }
    let lastFee = '';
    if (currentWeeks.length) {
      const lastWeek = Math.max(...currentWeeks);
      const lastInput = document.querySelector(`input[name="weeks[${lastWeek - 1}][fee]"]`);
      lastFee = lastInput ? lastInput.value : '';
    }
    currentWeeks.push(next);
    addWeekRow(next, lastFee);
  }

  async function loadCourseFees(courseId) {
    if (!courseId) {
      renderWeeks({});
      return;
    }

    try {
      const urlTemplate = `{{ route('admin.language-school-course-fees.fetch', ['languageSchoolCourse' => '__COURSE__']) }}`;
      const url = urlTemplate.replace('__COURSE__', encodeURIComponent(courseId));
      const response = await fetch(url, {
        headers: { 'Accept': 'application/json' }
      });
      if (!response.ok) throw new Error('Failed to load fees');
      const data = await response.json();
      const map = {};
      (data.fees || []).forEach(item => map[item.week_number] = item.fee);
      renderWeeks(map);
    } catch (error) {
      console.error(error);
      renderWeeks({});
    }
  }

  addBtn.addEventListener('click', addNextWeek);
  courseSelect.addEventListener('change', (event) => loadCourseFees(event.target.value));

  renderWeeks(prefill);
</script>
@endsection
