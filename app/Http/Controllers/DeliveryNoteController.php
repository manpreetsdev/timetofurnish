<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class DeliveryNoteController extends Controller
{
    // Admin panel: any order
    public function admin(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        return $this->render($order, $request);
    }

    // Seller panel: only the seller's own orders
    public function seller(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if ((int) $order->seller_id !== (int) auth()->id()) {
            abort(403);
        }

        return $this->render($order, $request);
    }

    private function render(Order $order, Request $request)
    {
        $download = $request->boolean('download');
        ini_set('memory_limit', '512M');

        $order->loadMissing(['orderDetails.product', 'shop.user.addresses.country', 'shop.user.addresses.state', 'shop.user.addresses.city']);

        // Responsive on-screen version (mobile friendly); the PDF stays the default
        if ($request->query('format') === 'html') {
            return view('backend.invoices.delivery_note', ['order' => $order, 'screen' => true]);
        }

        $tempDir = storage_path('app/mpdf-delivery-notes/' . uniqid('run-', true));
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        $html = view('backend.invoices.delivery_note', ['order' => $order])->render();

        set_error_handler(function ($severity, $message) {
            return str_contains($message, 'unserialize(): Extra data starting at offset');
        });

        try {
            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'tempDir' => $tempDir,
                'font_path' => public_path('assets/fonts/'),
                'font_data' => [
                    'roboto' => [
                        'R' => 'Roboto-Regular.ttf',
                        'useOTL' => 0xFF,
                        'useKashida' => 75,
                    ],
                ],
                'margin_left' => 0,
                'margin_right' => 0,
                'margin_top' => 0,
                'margin_bottom' => 16,
                'margin_footer' => 0,
                'default_font' => 'roboto',
            ]);
            $mpdf->showImageErrors = false;
            $mpdf->SetAutoPageBreak(true, 15);
            $mpdf->WriteHTML($html);
            $output = $mpdf->Output('', \Mpdf\Output\Destination::STRING_RETURN);
        } finally {
            restore_error_handler();
        }

        $filename = 'delivery-note-' . $order->code . '.pdf';

        return response($output, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => ($download ? 'attachment' : 'inline') . '; filename="' . $filename . '"',
        ]);
    }
}
