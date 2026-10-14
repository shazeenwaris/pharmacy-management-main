<?php

namespace App\Filament\Pharmacist\Pages;

use App\Models\HeldSale;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class HeldSales extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPause;

    protected static ?string $navigationLabel = 'Held Sales';

    protected static ?string $title = 'Held Sales';

    protected string $view = 'filament.pharmacist.pages.held-sales';

    protected function renderHeldSale(HeldSale $record): HtmlString
    {
        $cart = collect($record->cart ?? []);

        $rows = $cart->map(function ($item) {
            $name = e($item['medicine_name'] ?? $item['name'] ?? 'N/A');
            $unit = e($item['sale_unit'] ?? '-');
            $qty = $item['quantity'] ?? 0;
            $price = (float) ($item['unit_price'] ?? $item['price'] ?? 0);
            $lineTotal = (float) ($item['total'] ?? ($qty * $price));

            return "<tr>
                <td style='padding:8px; border-bottom:1px solid #eef2f3;'>{$name}</td>
                <td style='padding:8px; border-bottom:1px solid #eef2f3;'>{$unit}</td>
                <td style='padding:8px; border-bottom:1px solid #eef2f3; text-align:center;'>{$qty}</td>
                <td style='padding:8px; border-bottom:1px solid #eef2f3; text-align:right;'>" . format_currency($price) . "</td>
                <td style='padding:8px; border-bottom:1px solid #eef2f3; text-align:right; font-weight:600;'>" . format_currency($lineTotal) . "</td>
            </tr>";
        })->implode('');

        $customerName = e($record->customer->name ?? 'Walk-in');
        $heldByName = e($record->user->name ?? '-');
        $heldAt = $record->created_at->format('d M Y, h:i A');
        $note = $record->note ? e($record->note) : null;

        return new HtmlString("
            <div style='font-size:13px;'>
                <div style='display:flex; justify-content:space-between; margin-bottom:12px; color:#405A68;'>
                    <span><strong>Customer:</strong> {$customerName}</span>
                    <span><strong>Held By:</strong> {$heldByName}</span>
                    <span><strong>Held At:</strong> {$heldAt}</span>
                </div>

                <table style='width:100%; border-collapse:collapse;'>
                    <thead>
                        <tr style='text-align:left; border-bottom:2px solid #E2EEEE; font-size:11px; text-transform:uppercase; color:#718792;'>
                            <th style='padding:8px;'>Medicine</th>
                            <th style='padding:8px;'>Unit</th>
                            <th style='padding:8px; text-align:center;'>Qty</th>
                            <th style='padding:8px; text-align:right;'>Price</th>
                            <th style='padding:8px; text-align:right;'>Total</th>
                        </tr>
                    </thead>
                    <tbody>{$rows}</tbody>
                </table>

                <div style='margin-top:12px; text-align:right; font-size:12px; color:#718792;'>
                    Discount: " . format_currency($record->discount) . " &nbsp;|&nbsp; Tax: " . format_currency($record->tax) . " &nbsp;|&nbsp; <strong>Total: " . format_currency($record->grand_total) . "</strong>
                </div>

                " . ($note ? "<div style='margin-top:10px; padding:8px; background:#F8FAFA; border-radius:6px; font-size:12px; color:#405A68;'><strong>Note:</strong> {$note}</div>" : "") . "
            </div>
        ");
    }

    protected function renderResumeSummary(HeldSale $record): HtmlString
    {
        $customerName = e($record->customer->name ?? 'Walk-in');
        $customerPhone = e($record->customer->phone ?? '-');
        $heldByName = e($record->user->name ?? '-');
        $heldAt = $record->created_at->format('d M Y, h:i A');
        $method = e(ucfirst($record->payment_method ?? '-'));

        $grandTotal = (float) $record->grand_total;
        $paid = (float) $record->paid_amount;
        $due = max(0, $grandTotal - $paid);

        $dueColor = $due > 0 ? '#C0392B' : '#1E8449';

        $summary = "
            <div style='font-size:13px; margin-bottom:14px;'>
                <div style='display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:12px; color:#405A68;'>
                    <span><strong>Customer:</strong> {$customerName}</span>
                    <span><strong>Phone:</strong> {$customerPhone}</span>
                    <span><strong>Held By:</strong> {$heldByName}</span>
                    <span><strong>Held At:</strong> {$heldAt}</span>
                    <span><strong>Payment Method:</strong> {$method}</span>
                </div>

                <div style='display:grid; grid-template-columns:repeat(4,1fr); gap:8px; text-align:center;'>
                    <div style='padding:10px; background:#F8FAFA; border-radius:6px;'>
                        <div style='font-size:11px; color:#718792;'>SUBTOTAL</div>
                        <div style='font-weight:700;'>" . format_currency($record->subtotal) . "</div>
                    </div>
                    <div style='padding:10px; background:#F8FAFA; border-radius:6px;'>
                        <div style='font-size:11px; color:#718792;'>TOTAL</div>
                        <div style='font-weight:700;'>" . format_currency($grandTotal) . "</div>
                    </div>
                    <div style='padding:10px; background:#F8FAFA; border-radius:6px;'>
                        <div style='font-size:11px; color:#718792;'>PAID</div>
                        <div style='font-weight:700;'>" . format_currency($paid) . "</div>
                    </div>
                    <div style='padding:10px; background:#F8FAFA; border-radius:6px;'>
                        <div style='font-size:11px; color:#718792;'>DUE (BAQI)</div>
                        <div style='font-weight:700; color:{$dueColor};'>" . format_currency($due) . "</div>
                    </div>
                </div>
            </div>
        ";

        return new HtmlString($summary . $this->renderHeldSale($record)->toHtml());
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(HeldSale::query()->with(['user', 'customer']))
            ->columns([
                TextColumn::make('hold_number')
                    ->label('Reference')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->default('Walk-in'),

                TextColumn::make('user.name')
                    ->label('Held By')
                    ->default('-'),

                TextColumn::make('grand_total')
                    ->label('Total')
                    ->formatStateUsing(fn ($state) => format_currency($state))
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Held At')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('View')
                    ->icon(Heroicon::OutlinedEye)
                    ->color('info')
                    ->modalHeading(fn (HeldSale $record) => 'Held Sale — ' . $record->hold_number)
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close')
                    ->modalContent(fn (HeldSale $record) => $this->renderHeldSale($record)),

                Action::make('resume')
                    ->label('Resume')
                    ->icon(Heroicon::OutlinedPlay)
                    ->color('success')
                    ->modalHeading(fn (HeldSale $record) => 'Held Sale — ' . $record->hold_number)
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close')
                    ->modalWidth('3xl')
                    ->modalContent(fn (HeldSale $record) => $this->renderResumeSummary($record)),

                Action::make('delete')
                    ->label('Discard')
                    ->icon(Heroicon::OutlinedTrash)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (HeldSale $record) {
                        $record->delete();

                        Notification::make()
                            ->title('Held sale discarded')
                            ->success()
                            ->send();
                    }),
            ])
            ->actionsColumnLabel('Actions')
            ->defaultSort('created_at', 'desc');
    }
}