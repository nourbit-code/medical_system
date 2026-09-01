@extends('layouts.app')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
    <div>
        <h1 class="mb-0">{{ $appointment->medicalRecord ? 'Update patient EMR' : 'Complete patient visit' }}</h1>
    </div>
    <x-status-badge :status="$appointment->status" />
</div>

@if ($errors->any())
<div class="alert alert-danger">
    <strong>Please fix these items:</strong>
    <ul class="mb-0 mt-2">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
</div>
@endif

<div class="card p-4 mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="avatar" style="width:52px;height:52px;font-size:1.2rem;">
            {{ strtoupper(substr($appointment->patient->first_name, 0, 1)) }}</div>
        <div>
            <h2 class="h4 mb-1">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</h2>
            <div class="text-muted">
                Age: {{ $appointment->patient->age ?? 'Not provided' }}
                <span class="mx-2">·</span>
                Gender: {{ ucfirst($appointment->patient->gender ?? 'Not provided') }}
                <span class="mx-2">·</span>
                Visit: {{ $appointment->appointment_date->format('M d, Y') }}
            </div>
        </div>
    </div>
</div>

@php
$items = old('prescription_items', $prescriptionItems);
$items = count($items) ? $items : [[]];
@endphp

<form method="POST" action="{{ route('medical-records.store', $appointment) }}">
    @csrf
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card h-100 p-4">
                <label for="diagnosis" class="form-label fw-bold">Diagnosis <span class="text-danger">*</span></label>
                <textarea id="diagnosis" name="diagnosis" class="form-control" rows="6"
                    required>{{ old('diagnosis', optional($appointment->medicalRecord)->diagnosis) }}</textarea>
                @error('diagnosis')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100 p-4">
                <label for="treatment" class="form-label fw-bold">Treatment plan <span
                        class="text-danger">*</span></label>
                <textarea id="treatment" name="treatment" class="form-control" rows="6"
                    required>{{ old('treatment', optional($appointment->medicalRecord)->treatment) }}</textarea>
                @error('treatment')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100 p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <label class="form-label fw-bold mb-0">Electronic prescription</label>
                    <button type="button" id="addMedicine" class="btn btn-sm btn-outline-primary">+ Add
                        medicine</button>
                </div>

                <div id="medicineList">
                    @foreach ($items as $index => $item)
                    <div class="medicine-item border rounded-3 mb-2">
                        <button type="button"
                            class="btn w-100 text-start d-flex justify-content-between align-items-center p-3"
                            data-bs-toggle="collapse" data-bs-target="#medicineDetails{{ $index }}">
                            <span class="medicine-title fw-semibold">{{ $item['medicine'] ?? 'New medicine' }}</span>
                            <span class="text-primary">⌄</span>
                        </button>
                        <div id="medicineDetails{{ $index }}" class="collapse {{ $index === 0 ? 'show' : '' }}">
                            <div class="p-3 pt-0">
                                <input type="text" name="prescription_items[{{ $index }}][medicine]"
                                    class="form-control medicine-name mb-2" placeholder="Medicine name"
                                    value="{{ $item['medicine'] ?? '' }}">
                                <div class="row g-2">
                                    <div class="col-6"><input type="text" name="prescription_items[{{ $index }}][dose]"
                                            class="form-control" placeholder="Dose" value="{{ $item['dose'] ?? '' }}">
                                    </div>
                                    <div class="col-6"><input type="text"
                                            name="prescription_items[{{ $index }}][frequency]" class="form-control"
                                            placeholder="Frequency" value="{{ $item['frequency'] ?? '' }}"></div>
                                    <div class="col-6"><input type="text"
                                            name="prescription_items[{{ $index }}][duration]" class="form-control"
                                            placeholder="Duration" value="{{ $item['duration'] ?? '' }}"></div>
                                    <div class="col-6"><input type="text"
                                            name="prescription_items[{{ $index }}][instructions]" class="form-control"
                                            placeholder="Instructions" value="{{ $item['instructions'] ?? '' }}"></div>
                                </div>
                                <button type="button"
                                    class="btn btn-sm btn-link text-danger px-0 remove-medicine">Remove
                                    medicine</button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @error('prescription_items.*.medicine')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    <div class="d-flex flex-wrap gap-2 mt-4">
        <button type="submit" class="btn btn-primary px-4">Save EMR and complete visit</button>
        <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-outline-secondary px-4">Cancel</a>
    </div>
</form>

<template id="medicineTemplate">
    <div class="medicine-item border rounded-3 mb-2">
        <button type="button" class="btn w-100 text-start d-flex justify-content-between align-items-center p-3"
            data-bs-toggle="collapse" data-bs-target="#medicineDetails__INDEX__">
            <span class="medicine-title fw-semibold">New medicine</span><span class="text-primary">⌄</span>
        </button>
        <div id="medicineDetails__INDEX__" class="collapse show">
            <div class="p-3 pt-0">
                <input type="text" name="prescription_items[__INDEX__][medicine]"
                    class="form-control medicine-name mb-2" placeholder="Medicine name">
                <div class="row g-2">
                    <div class="col-6"><input type="text" name="prescription_items[__INDEX__][dose]"
                            class="form-control" placeholder="Dose"></div>
                    <div class="col-6"><input type="text" name="prescription_items[__INDEX__][frequency]"
                            class="form-control" placeholder="Frequency"></div>
                    <div class="col-6"><input type="text" name="prescription_items[__INDEX__][duration]"
                            class="form-control" placeholder="Duration"></div>
                    <div class="col-6"><input type="text" name="prescription_items[__INDEX__][instructions]"
                            class="form-control" placeholder="Instructions"></div>
                </div>
                <button type="button" class="btn btn-sm btn-link text-danger px-0 remove-medicine">Remove
                    medicine</button>
            </div>
        </div>
    </div>
</template>

<script>
const medicineList = document.getElementById('medicineList');
const medicineTemplate = document.getElementById('medicineTemplate');
let medicineIndex = {
    {
        count($items)
    }
};

document.getElementById('addMedicine').addEventListener('click', function() {
    const html = medicineTemplate.innerHTML.replaceAll('__INDEX__', medicineIndex);
    medicineList.insertAdjacentHTML('beforeend', html);
    medicineIndex++;
});

medicineList.addEventListener('input', function(event) {
    if (event.target.classList.contains('medicine-name')) {
        const item = event.target.closest('.medicine-item');
        item.querySelector('.medicine-title').textContent = event.target.value || 'New medicine';
    }
});

medicineList.addEventListener('click', function(event) {
    if (event.target.classList.contains('remove-medicine')) {
        const items = medicineList.querySelectorAll('.medicine-item');
        if (items.length > 1) {
            event.target.closest('.medicine-item').remove();
        }
    }
});
</script>
@endsection
