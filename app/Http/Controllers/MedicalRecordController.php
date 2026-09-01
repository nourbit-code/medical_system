<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    public function create(Appointment $appointment)
    {
        if (
            !in_array($appointment->status, ['in_progress', 'completed'])
            || (auth()->user()->role === 'doctor'
                && $appointment->doctor_id !== optional(auth()->user()->doctor)->id)
            || auth()->user()->role === 'patient'
        ) {
            abort(403);
        }

        $appointment->load('patient', 'medicalRecord');
        $prescriptionItems = $this->prescriptionItems($appointment->medicalRecord?->prescription);

        return view('medical_records.create', compact('appointment', 'prescriptionItems'));
    }

    public function store(Request $request, Appointment $appointment)
    {
        if (
            !in_array($appointment->status, ['in_progress', 'completed'])
            || (auth()->user()->role === 'doctor'
                && $appointment->doctor_id !== optional(auth()->user()->doctor)->id)
            || auth()->user()->role === 'patient'
        ) {
            abort(403);
        }

        $data = $request->validate([
            'diagnosis' => 'required|string',
            'treatment' => 'required|string',
            'prescription_items' => 'nullable|array',
            'prescription_items.*.medicine' => 'nullable|string|max:255',
            'prescription_items.*.dose' => 'nullable|string|max:255',
            'prescription_items.*.frequency' => 'nullable|string|max:255',
            'prescription_items.*.duration' => 'nullable|string|max:255',
            'prescription_items.*.instructions' => 'nullable|string|max:500',
        ]);

        $data['prescription'] = $this->formatPrescription($request->input('prescription_items', []));
        unset($data['prescription_items']);

        $appointment->medicalRecord()->updateOrCreate([], $data);
        $appointment->update(['status' => 'completed']);

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'EMR saved and appointment completed successfully.');
    }

    private function formatPrescription(array $items): ?string
    {
        $lines = [];

        foreach ($items as $item) {
            if (empty($item['medicine'])) {
                continue;
            }

            $parts = ['Medicine: ' . $item['medicine']];

            foreach (['dose' => 'Dose', 'frequency' => 'Frequency', 'duration' => 'Duration', 'instructions' => 'Instructions'] as $field => $label) {
                if (!empty($item[$field])) {
                    $parts[] = $label . ': ' . $item[$field];
                }
            }

            $lines[] = implode(' | ', $parts);
        }

        return $lines ? implode("\n", $lines) : null;
    }

    private function prescriptionItems(?string $prescription): array
    {
        if (!$prescription) {
            return [[]];
        }

        return collect(explode("\n", $prescription))->map(function ($line) {
            $item = [];

            foreach (explode(' | ', $line) as $part) {
                if (!str_contains($part, ': ')) {
                    $item['medicine'] = $part;
                    continue;
                }

                [$label, $value] = array_pad(explode(': ', $part, 2), 2, '');
                $item[strtolower($label)] = $value;
            }

            return $item;
        })->all();
    }
}