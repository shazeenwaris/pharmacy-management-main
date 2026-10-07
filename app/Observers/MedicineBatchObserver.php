<?php

namespace App\Observers;

use App\Models\MedicineBatch;
use App\Models\MedicineBatchPackaging;
use App\Models\Unit;

class MedicineBatchObserver
{
    public function saved(MedicineBatch $batch): void
    {
        $medicine = $batch->medicine;

        if (! $medicine) {
            return;
        }

        $type = $medicine->packaging_type ?? 'strip';
        $baseUnitId = $medicine->unit_id;

        if (! $baseUnitId) {
            return;
        }

        $packagings = [];

        // Base unit (Tablet / Piece / Unit) — always sellable, conversion = 1
        $packagings[] = [
            'unit_id' => $baseUnitId,
            'conversion_factor' => 1,
            'sort_order' => 0,
        ];

        if ($type === 'strip') {
            $unitsPerStrip = max((int) $batch->units_per_strip, 1);
            $stripsPerBox = max((int) $batch->strips_per_box, 1);

            if ($unitsPerStrip > 1) {
                $stripUnit = Unit::firstOrCreate(
                    ['name' => 'Strip'],
                    ['status' => true]
                );

                $packagings[] = [
                    'unit_id' => $stripUnit->id,
                    'conversion_factor' => $unitsPerStrip,
                    'sort_order' => 1,
                ];
            }

            if ($stripsPerBox > 1) {
                $boxUnit = Unit::firstOrCreate(
                    ['name' => 'Box'],
                    ['status' => true]
                );

                $packagings[] = [
                    'unit_id' => $boxUnit->id,
                    'conversion_factor' => $stripsPerBox * $unitsPerStrip,
                    'sort_order' => 2,
                ];
            }
        } elseif ($type === 'pack') {
            $piecesPerPack = max((int) $batch->units_per_strip, 1);

            if ($piecesPerPack > 1) {
                $packUnit = Unit::firstOrCreate(
                    ['name' => 'Pack'],
                    ['status' => true]
                );

                $packagings[] = [
                    'unit_id' => $packUnit->id,
                    'conversion_factor' => $piecesPerPack,
                    'sort_order' => 1,
                ];
            }
        }

        foreach ($packagings as $data) {
            MedicineBatchPackaging::updateOrCreate(
                [
                    'medicine_batch_id' => $batch->id,
                    'unit_id' => $data['unit_id'],
                ],
                [
                    'conversion_factor' => $data['conversion_factor'],
                    'can_sell' => true,
                    'sort_order' => $data['sort_order'],
                ]
            );
        }
    }
}